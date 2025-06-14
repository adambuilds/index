# AGENTS.md Guide — index.one

> **Audience**  Developers and AI agents working with this repository. \
> **Goal**  Provide the context needed to navigate, modify, and extend the code‑base while preserving core design principles.

---

## Project Purpose

**Index** is a multi‑tenant, *homepage‑for‑everything* platform. Each Thing (Element, Ingredient, Product, Physical Asset, etc.) receives:

1. A globally‑unique, human‑readable URL — e.g. `/element/Na`, `/ingredient/sugar`.
2. A canonical JSON representation exposed via API.
3. A role‑aware HTML *Inspector* page for interactive exploration.

The public instance [https://index.one](https://index.one) exposes immutable, open‑license concepts. Private tenants mount their own sub‑graphs at `https://index.<tenant>.com`, re‑using the same schema.

> **Mantra** Resolve identity first, bolt on features second.

---

## Repository Structure

```
/                ← root (contains this file)
├── app/
│   ├── Models/          ← Eloquent domain models (Thing, Alias, Edge …)
│   ├── Http/
│   │   ├── Controllers/ ← Thin, resource‑style controllers
│   │   └── Middleware/  ← e.g. SetTenant.php
│   └── Providers/       ← Service & event providers
├── database/
│   ├── migrations/      ← Version‑controlled schema
│   └── seeders/         ← e.g. PeriodicTableSeeder.php
├── resources/
│   └── views/           ← Blade templates (Inspector UI)
├── routes/
│   ├── web.php          ← Public / tenant SSR routes
│   └── api.php          ← JSON API (GraphQL lives in /graphql)
├── tests/               ← PHPUnit + Pest tests
```

*Modify only the directories shown above. Bootstrap scripts, compiled assets, and **`vendor/`** are off‑limits unless explicitly referenced.*

## Architectural Overview

### Models and Relationships

- **Subject**: Core entity, e.g. Element, Ingredient, Product.

## Coding Conventions

### 4.1 General Guidelines

- **Language** PHP 8.3 with `declare(strict_types=1)`.
- **Framework** Laravel 11 LTS.
- **Style** Run `./vendor/bin/pint` before committing (Laravel Pint defaults).
- **Commits** Follow [Conventional Commits](https://www.conventionalcommits.org/) — enables semantic‑release.
- **Branches** `main` (deploy), `dev` (integration), feature branches off `dev`.
- **Environment** Commit `.env.example` only — never real secrets.

### 4.2 Architectural Rules

1. **Thin Controllers, Fat Services** — move non‑trivial logic to dedicated service classes.
2. **Views Are Presentation‑Only** — no business logic in Blade; lightweight view helpers are fine.
3. **Graph Mutations via **`` — never manipulate the `edges` table directly in controllers.
4. **Multi‑Tenancy Scope** — always apply `forCurrentTenant()` (global scope) or equivalent on tenant‑scoped queries.

---

### CSS/Styling Standards for Index

- Index should use Tailwind CSS for styling as documented in AGENTS.md
- Follow utility-first approach in all Index style implementations
- Index should use custom CSS only when necessary

## 5  Testing & Continuous Integration

```bash
# run the full suite
./vendor/bin/pest

# run a single test file
./vendor/bin/pest tests/Feature/ThingTest.php
```

CI (GitHub Actions) executes **lint → tests → Pint → build** on every push.\
Target ≥ 80 % line coverage on code you touch.

---

## 6  Programmatic Checks

Before opening a pull request, ensure all local checks pass:

```bash
# Static analysis (Larastan)
composer analyse

# Linting & style fix
./vendor/bin/pint

# Tests
./vendor/bin/pest
```

---

## Pull‑Request Checklist

1. Title follows Conventional Commits.
2. All programmatic checks and CI are green.
3. Include migration & seeder if the schema changed.
4. Update **AGENTS.md** if architectural rules or structure changed.
5. Keep the PR focused on a single concern.