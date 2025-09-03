Here’s a compact, copy-pasteable guide to wire up routes like /thing/* or /{type}/ that read directly from Wikidata for a proof-of-concept/MVP. It uses the public Wikidata APIs with caching, retry/backoff, and simple views. No DB ingest required.

0) Install & env

composer require guzzlehttp/guzzle:^7

.env (tweak as needed):

WIKIDATA_BASE=https://www.wikidata.org
WIKIDATA_SPARQL=https://query.wikidata.org/sparql
WIKIDATA_USER_AGENT="YourApp/0.1 (contact@example.com)"
WIKIDATA_CACHE_TTL=43200   # 12h

1) Routes

routes/web.php

use App\Http\Controllers\ThingController;
use App\Http\Controllers\TypeController;

Route::get('/', fn() => view('welcome'));

// /thing/Q42 → entity details (labels, descriptions, claims snippet)
Route::get('/thing/{qid}', [ThingController::class, 'show'])
     ->where('qid', 'Q[0-9]+');

// /{type}/ → list entities of a type (e.g., /human/ or /city/)
Route::get('/{type}', [TypeController::class, 'index'])
     ->where('type', '[A-Za-z-]+');

2) Service class (Wikidata client)

app/Services/Wikidata.php

<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Wikidata
{
    private Client $http;
    private string $base;
    private string $sparql;
    private string $ua;
    private int $ttl;

    public function __construct()
    {
        $this->base  = rtrim(config('services.wikidata.base'), '/');
        $this->sparql= rtrim(config('services.wikidata.sparql'), '/');
        $this->ua    = config('services.wikidata.ua');
        $this->ttl   = (int) config('services.wikidata.ttl', 43200);
        $this->http  = new Client([
            'timeout' => 20,
            'headers' => ['User-Agent' => $this->ua, 'Accept' => 'application/json'],
        ]);
    }

    /** Entity JSON (labels/descriptions/claims) */
    public function entity(string $qid): array
    {
        $key = "wd:entity:$qid";
        return Cache::remember($key, $this->ttl, function () use ($qid) {
            $url = "{$this->base}/wiki/Special:EntityData/{$qid}.json";
            return $this->getJsonWithRetry($url);
        });
    }

    /** Convenience: flattened label/description in a language (fallback en) */
    public function entityBasics(string $qid, string $lang = 'en'): array
    {
        $j = $this->entity($qid);
        $ent = $j['entities'][$qid] ?? [];
        $label = $ent['labels'][$lang]['value'] ?? ($ent['labels']['en']['value'] ?? $qid);
        $desc  = $ent['descriptions'][$lang]['value'] ?? ($ent['descriptions']['en']['value'] ?? null);
        return compact('label', 'desc', 'raw') + ['raw' => $ent];
    }

    /** SPARQL query (returns rows of bindings) */
    public function sparql(string $query): array
    {
        $key = 'wd:sparql:' . md5($query);
        return Cache::remember($key, $this->ttl, function () use ($query) {
            $resp = $this->http->post($this->sparql, [
                'form_params' => ['query' => $query],
                'headers'     => ['Accept' => 'application/sparql-results+json'],
            ]);
            $json = json_decode($resp->getBody()->getContents(), true);
            return $json['results']['bindings'] ?? [];
        });
    }

    /** --- internals --- */

    private function getJsonWithRetry(string $url, int $tries = 3, int $backoffMs = 500): array
    {
        for ($i = 0; $i < $tries; $i++) {
            try {
                $resp = $this->http->get($url);
                return json_decode($resp->getBody()->getContents(), true) ?? [];
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                if ($e->getResponse()?->getStatusCode() === 429 && $i < $tries-1) {
                    usleep($backoffMs * 1000);
                    $backoffMs *= 2;
                    continue;
                }
                throw $e;
            }
        }
        return [];
    }
}

3) Config binding

config/services.php

return [
    // ...
    'wikidata' => [
        'base'  => env('WIKIDATA_BASE', 'https://www.wikidata.org'),
        'sparql'=> env('WIKIDATA_SPARQL', 'https://query.wikidata.org/sparql'),
        'ua'    => env('WIKIDATA_USER_AGENT', 'YourApp/0.1 (contact@example.com)'),
        'ttl'   => (int) env('WIKIDATA_CACHE_TTL', 43200),
    ],
];

4) Controllers

A) /thing/{qid} (single entity)

app/Http/Controllers/ThingController.php

<?php

namespace App\Http\Controllers;

use App\Services\Wikidata;

class ThingController extends Controller
{
    public function __construct(private Wikidata $wd) {}

    public function show(string $qid)
    {
        $lang = request('lang', 'en');
        $basics = $this->wd->entityBasics($qid, $lang);
        $raw = $basics['raw'];

        // Pull a small slice of claims (example: instance of, P31)
        $claims = $raw['claims']['P31'] ?? [];
        $instances = collect($claims)->map(function ($c) {
            $v = $c['mainsnak']['datavalue']['value'] ?? null;
            return is_array($v) && isset($v['id']) ? $v['id'] : null;
        })->filter()->values();

        return view('thing.show', [
            'qid'       => $qid,
            'label'     => $basics['label'],
            'desc'      => $basics['desc'],
            'instances' => $instances,  // array of Q-ids
            'entity'    => $raw,        // raw dump (for debugging)
        ]);
    }
}

B) /{type} (simple list by type using SPARQL)

Example: map human → Q5, city → Q515 (adjust to your demo needs).
app/Http/Controllers/TypeController.php

<?php

namespace App\Http\Controllers;

use App\Services\Wikidata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    public function __construct(private Wikidata $wd) {}

    public function index(Request $req, string $type)
    {
        $map = [
            'human' => 'Q5',
            'city'  => 'Q515',
            'book'  => 'Q571',
        ];
        abort_unless(isset($map[$type]), 404);

        $qid = $map[$type];
        $lang = $req->get('lang', 'en');
        $limit = min((int) $req->get('limit', 20), 100);

        $sparql = <<<SPARQL
SELECT ?item ?itemLabel WHERE {
  ?item wdt:P31 wd:$qid .
  SERVICE wikibase:label { bd:serviceParam wikibase:language "$lang,en". }
}
LIMIT $limit
SPARQL;

        $rows = $this->wd->sparql($sparql);

        $items = collect($rows)->map(fn($r) => [
            'uri'   => $r['item']['value'],
            'qid'   => Str::after($r['item']['value'], 'http://www.wikidata.org/entity/'),
            'label' => $r['itemLabel']['value'] ?? null,
        ])->all();

        return view('type.index', compact('type', 'items', 'qid'));
    }
}

5) Minimal Blade views

resources/views/thing/show.blade.php

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
  <h1 class="text-2xl font-semibold">{{ $label }} <span class="text-gray-500">({{ $qid }})</span></h1>
  @if($desc)<p class="text-gray-700 mb-3">{{ $desc }}</p>@endif

  @if(count($instances))
    <p class="mb-2"><strong>Instance of:</strong>
      @foreach($instances as $i)
        <a class="underline" href="{{ url('/thing/'.$i) }}">{{ $i }}</a>@if(!$loop->last),@endif
      @endforeach
    </p>
  @endif

  <details class="mt-4">
    <summary class="cursor-pointer underline">Raw entity JSON (debug)</summary>
    <pre class="text-xs overflow-x-auto">{{ json_encode($entity, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
  </details>
</div>
@endsection

resources/views/type/index.blade.php

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
  <h1 class="text-2xl font-semibold">Type: {{ ucfirst($type) }} (wd:{{ $qid }})</h1>
  <ul class="list-disc ml-5 mt-3">
    @foreach($items as $it)
      <li>
        <a class="underline" href="{{ url('/thing/'.$it['qid']) }}">
          {{ $it['label'] ?? $it['qid'] }} ({{ $it['qid'] }})
        </a>
      </li>
    @endforeach
  </ul>
</div>
@endsection

(Use your preferred layout; above is intentionally minimal.)

6) Middleware/rate-limit niceties (optional but wise)
	•	Enable HTTP response caching where appropriate.
	•	Add a simple throttle to your controllers during POC:

// app/Http/Middleware/SimpleThrottle.php (optional)

Or use Laravel’s built-in ThrottleRequests on routes.

7) Testing smoke checks (very light)

tests/Feature/WikidataRoutesTest.php

<?php

it('loads a known entity', function () {
    $resp = $this->get('/thing/Q42');
    $resp->assertStatus(200)->assertSee('Q42');
});

it('lists humans', function () {
    $resp = $this->get('/human?limit=3');
    $resp->assertStatus(200);
});

8) Notes for a smooth POC
	•	User-Agent is required; keep it identifiable (set in .env).
	•	Respect public WDQS limits: 60s/query, 5 concurrent per IP. The caching layer above dramatically reduces calls.
	•	Add stale-while-revalidate behavior later if you want snappier updates.
	•	For client interactivity, you can expose JSON versions of these endpoints via /api/* and sprinkle Alpine.js to hydrate views without changing this backend.

That’s it—you now have /thing/Q… and /{type} routes reading live from Wikidata with sane caching and minimal code.