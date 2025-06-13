Below is a **Laravel-first “starter map”** that turns the Index.one concepts into concrete Eloquent models, their tables/columns, key relationships, and the controllers/routes you’d scaffold next.

_It is purposely lean: just enough to hit the “homepage-for-every-object” MVP without boxing you in once you shift storage to Neo4j later._

---

## **1. High-level domain slice**

```
┌────────────┐
│  Tenant    │  ←─ optional, only present for private sub-graphs
└────┬───────┘
     │ 1            ┌─────────────────────────┐
     ▼              │  Thing (abstract)       │  ← every resolvable object
┌────────────┐      │  id (ulid)  PK          │
│   User     │──────┤  tenant_id FK nullable  │
└────────────┘  *n  │  type (morph)           │
                   │  canonical_slug          │
                   │  default_name            │
                   │  licence                 │
                   │  provenance_source       │
                   └─────────┬───────────────┘
                             │ STI
            ┌────────────────┼───────────────────────────────┐
            ▼                ▼                               ▼
      Element            Ingredient                       Organization
      Product            Person                           Location
      ItemCopy (Instance)   … (add more later)
```

Additional tables:

|**Table**|**Purpose**|
|---|---|
|**aliases**|alternate names → thing_id, alias, language, is_primary|
|**edges**|generic graph edge → subject_id, predicate, object_id, properties (jsonb)|
|**tags** / **taggables**|quick many-to-many labels (e.g. “allergen”)|
|**timeline_events**|comments / edits / GPS pings attached to any thing|

---

## **2. Minimal migrations**

```
// 2024_06_07_000001_create_things.php
Schema::create('things', function (Blueprint $t) {
    $t->ulid('id')->primary();
    $t->foreignUlid('tenant_id')->nullable()->constrained()->cascadeOnDelete();
    $t->string('type');               // "element", "product" ...
    $t->string('canonical_slug')->unique();
    $t->string('default_name');
    $t->string('licence')->nullable();
    $t->json('provenance_source')->nullable();
    $t->timestamps();
});
```

_(repeat for_ _aliases__,_ _edges__, etc.)_

---

## **3. Eloquent models (abridged)**

```
// app/Models/Thing.php
abstract class Thing extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table   = 'things';
    public    $incrementing = false;
    protected $keyType = 'string';

    /** Polymorphic STI */
    public function newFromBuilder($attrs = [], $connection = null)
    {
        $class = match($attrs->type ?? null) {
            'element'     => Element::class,
            'ingredient'  => Ingredient::class,
            'product'     => Product::class,
            default       => self::class,
        };
        return (new $class)->setRawAttributes((array) $attrs, true);
    }

    /* --- Common relationships --- */
    public function aliases()  { return $this->hasMany(Alias::class); }

    public function outgoing() { return $this->hasMany(Edge::class,'subject_id'); }
    public function incoming() { return $this->hasMany(Edge::class,'object_id'); }

    /* Canonical URL accessor */
    public function getPathAttribute(): string
    {
        return "/{$this->canonical_slug}";
    }
}
```

Concrete subclasses extend Thing but add zero code unless you need custom scopes.

---

## **4. Controllers & routes**

|**Controller**|**Responsibility**|**Route examples**|
|---|---|---|
|**ThingController**|generic CRUD, shows inspector page|GET /{slug} – showPOST /api/things|
|**AliasController**|add / remove aliases (AJAX)|POST /{id}/aliases|
|**EdgeController**|create semantic links|POST /api/edges|
|**ImportController**|USDA → graph Artisan or queue job|php artisan import:usda|
|**TenantController**|spin up private spaces (admin only)|POST /admin/tenants|

```
Route::get('/{slug}', [ThingController::class,'show'])->name('thing.show');
Route::middleware('auth')->group(function () {
    Route::resource('things', ThingController::class)->except('show');
    Route::post('things/{thing}/aliases', [AliasController::class,'store']);
});
```

---

## **5. Blade “Inspector” view (sketch)**

```
/resources/views/things/show.blade.php
-------------------------------------------------
<h1>{{ $thing->default_name }}</h1>
<p><code>{{ $thing->canonical_slug }}</code></p>

<section>
    <h2>Aliases</h2>
    <ul>
        @foreach ($thing->aliases as $a) <li>{{ $a->alias }}</li> @endforeach
    </ul>
</section>

<section>
    <h2>Relationships</h2>
    @foreach ($thing->outgoing as $edge)
        <p>{{ $edge->predicate }} → <a href="{{ $edge->object->path }}">{{ $edge->object->default_name }}</a></p>
    @endforeach
</section>
```

Add an Alpine.js role-switch dropdown to filter sections later.

---

## **6. Multi-tenancy stub**

- Add tenant_id to Thing, Alias, Edge.
    
- A simple BelongsTo relationship on Thing.
    
- Middleware SetTenant that pulls sub-domain acme.index.one to set App::setTenant($tenant) and applies a global ->whereTenant() scope.
    

  

_No tenancy package needed for the MVP._

---

## **7. Where to go next**

|**After scaffolding**|**Why**|
|---|---|
|**Cypher export job** – keep MySQL authoritative for now but push nightly snapshots to Neo4j for future graph exploration.|Decouples UI from long-term storage choice.|
|**Full-text search** via Laravel Scout + MeiliSearch (public) + per-tenant filter.|Allows “alias resolution” UX.|
|**Seeders** for periodic table, ISO countries, sample ingredients.|Instant demo value.|
|**Edge predicates enum** (PART_OF, HAS_INGREDIENT, ALIAS_OF, …) stored in config.|Keeps data consistent.|
|**Policy classes** for per-tenant ACL.|Step toward paid private instances.|

---

### **One-liner takeaway**

  

> **Model everything as Thing + Alias + Edge, keep the schema tiny, expose it through a single ThingController, and you have 80 % of the “homepage-for-every-object” experience in place.**

  

Refinements (comments, GPS, NFTs, monetisation) bolt on without schema rewrites once this core is solid.