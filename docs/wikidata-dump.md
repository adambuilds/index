# Neo4j vs. Wikidata Dump Storage Footprint

When you import the full **Wikidata** JSON dump into **Neo4j**, the on‑disk size of the graph ends up far smaller than the original, even after decompression. The apparent “missing” hundreds of gigabytes are mostly formatting overhead that Neo4j eliminates or tokenises.

| Layer                       | What’s stored                                                              | Why it bloats in JSON / shrinks in Neo4j                                                                    |
| --------------------------- | -------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------- |
| **Syntax & whitespace**     | `{ } [ ] " ,` plus new‑lines & indents                                     | Pure overhead; every value is wrapped in punctuation that Neo4j doesn’t need.                               |
| **Repeated keys & vocab**   | Strings like `"labels"`, `"descriptions"`, `"id"` repeated **110 M** times | Neo4j writes each key *once* to a token table and stores a 4‑byte id everywhere else.                       |
| **Numbers & booleans**      | ASCII digits inside quotes                                                 | Stored as binary primitives (4–8 bytes) without quotes.                                                     |
| **IRIs & entity IDs**       | Full URLs such as `http://www.wikidata.org/entity/Q42`                     | Importers strip the common prefix and keep just the numeric id (`42`).                                      |
| **Redundant dump fields**   | Revision metadata, 300+ sitelinks, JSON comments                           | Often dropped or collapsed during import (e.g. keep only English label).                                    |
| **Store‑level compression** | Dynamic string blocks, record padding                                      | Small strings share pages; large strings spill into linked blocks—nothing equivalent in the flat JSON file. |

## Rough back‑of‑napkin ratio

```text
JSON braces & commas               ~10 %
Indentation & newlines             ~15 %
Repeated property keys             ~25 %
ASCII numbers vs binary            ~10 %
Data omitted during import         10–30 %
-----------------------------------------
Total “waste” removed              60–80 %
```

## Example size breakdown

| Stage                                   | Typical size    |
| --------------------------------------- | --------------- |
| Compressed dump (`latest-all.json.bz2`) | **≈ 90 GB**     |
| Decompressed JSON                       | **≈ 500 GB**    |
| Intermediate CSV/Turtle                 | **≈ 600 GB**    |
| Neo4j store after import (with indexes) | **≈ 85–110 GB** |

> Once the graph is live and you delete the dump + staging files, daily operating footprint falls to **≈ 100 GB**.

## Key takeaway

Neo4j’s compact binary record format, tokenised property keys, and the ability to drop unneeded Wikidata fields combine to reduce storage needs by an order of magnitude compared with the raw, line‑oriented JSON dump.
