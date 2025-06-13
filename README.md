[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F53949abd-a003-47ea-8abe-86c312a2b554%3Flabel%3D1%26commit%3D1&style=flat-square)](https://forge.laravel.com/servers/863298/sites/2542382)

# ![index.one](public/images/index-one-h.svg)

**Index.one** is a pathfinder toward a universal knowledge graph—"homepage for everything." Every object resolves at a clean, human-readable URL linking to the best snapshot of what we know about it.

> The centre of Index.one is identity resolution. A thing can be talked about in many ways; Index.one is the switchboard that says “all these names/IDs point to the same node.”

---

### Why Index.one?

- **Graph-shaped data.** Relationships like `PRODUCT → hasIngredient → SUBSTANCE` appear everywhere, so we use a property graph to capture aliases, versions, and timelines without brittle SQL joins.
- **Namespace & alias resolution.** Reverse-DNS names, UUIDs, QR codes, and Wikipedia-style slugs give each item stable machine IDs plus readable names.
- **Public core, private satellites.** Index.one hosts immutable public concepts, while companies can extend the graph on their own subdomain.
- **Role-aware views.** The same object shows different actions or fields depending on whether you’re a user, consumer, or technician.

### Layered architecture

1. **Core Knowledge Graph service.** Single authoritative database and API backed by Neo4j.
2. **Tenant layer.** Multi-tenant wrapper so `index.{tenant}.com` can mount extra sub-graphs, self‑hosted or SaaS.
3. **Presentation & Interaction layer.** Laravel renders human pages, QR codes, and comments, talking to the graph via a thin client library.

Model everything as **Thing + Alias + Edge** and expose it through a single `ThingController`—80 % of the “homepage-for-every-object” experience with a tiny schema.

### Project status

This repo is an early-stage sandbox for those ideas. Deployed live at [https://index.one](https://index.one) but designed to be portable for private instances.

---

_Pull requests and feature ideas welcome! Let’s build the API for reality, one node at a time._

