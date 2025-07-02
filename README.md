[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F53949abd-a003-47ea-8abe-86c312a2b554%3Flabel%3D1%26commit%3D1&style=flat-square)](https://forge.laravel.com/servers/863298/sites/2542382)

# ![index](public/index-one-h.svg)

## index.one — Laravel 12 + Livewire Starter Kit  
_Revision: 2025-07-02_

> A public showcase (and eventual multi-tenant platform) for a **universal index of immutable things**.  
> Built on Laravel 12, Livewire v3, Alpine.js, and Tailwind CSS.

---

## Table of Contents
1. [Project Vision](#project-vision)  
2. [Technical Stack](#technical-stack)  
3. [Domain Model](#domain-model)  
4. [Repository & Folder Structure](#repository--folder-structure)  
5. [Local Development Setup](#local-development-setup)  
6. [Running & Testing](#running--testing)  
7. [Future Roadmap](#future-roadmap)  
8. [Contributing](#contributing)  
9. [License](#license)  

---

## Project Vision
`index.one` aims to provide a human‑readable landing page for **every immutable Thing**—elements, compounds, standards, etc.—and their relationships.  

The MVP uses **SQL + Eloquent** for storage while abstracting a repository layer so the core models can be synced to **Neo4j** later without disruptive renames.

---

## Technical Stack

| Layer | Choice | Notes |
|-------|--------|-------|
| Framework | **Laravel 12** | LTS release (PHP ≥ 8.3) |
| UI | **Livewire v3 Starter Kit** | Server‑driven reactive UI; ships with Alpine.js & Tailwind |
| Auth | Laravel Breeze (Livewire preset) | Out‑of‑the‑box login, registration, email verification |
| DB | MySQL / MariaDB (default) | Any SQL database supported by Laravel works |
| Testing | PHPUnit 10 + Pest (optional) | TDD/BDD friendly |
| Dev Ops | Sail / Docker Compose (recommended) | Reproducible local environment |

---

## Domain Model

_For detailed fields and indexes, see [`docs/index-one-model-manifest.md`](docs/index-one-model-manifest.md)._

| Model | Purpose | Key Relationships |
|-------|---------|-------------------|
| **Thing** | Canonical record of an immutable concept | `hasMany Property`, `hasMany outgoing Relation`, `hasMany incoming Relation` |
| **Property** | Typed key‑value pairs describing a Thing | `belongsTo Thing` |
| **Relation** | Edge connecting two Things with semantics & weight | `belongsTo from Thing`, `belongsTo to Thing` |
| **User** | Auth & attribution (Breeze defaults) | `hasMany Message` |
| **Message** | Polymorphic comments / audit notes | `morphTo author`, `morphTo subject` |

_Planned but **deferred**_: Tagging, Revision history, Fine‑grained ACL.

---

## Repository & Folder Structure
```text
index-one/
├─ app/
│  ├─ Domain/            # Domain entities & value objects (future DDD)
│  ├─ Models/            # Eloquent models (Thing, Property, …)
│  ├─ Livewire/          # UI components
│  └─ Repositories/      # Storage abstractions (SQL ⇆ Neo4j)
├─ database/
│  ├─ migrations/
│  ├─ seeders/
│  └─ factories/
├─ docs/
│  └─ index-one-model-manifest.md
├─ resources/
│  ├─ views/
│  └─ css/js/
├─ tests/
│  └─ Feature/Unit/
└─ docker-compose.yml    # Optional Sail override
```

---

## Local Development Setup

### 1. Prerequisites
* PHP 8.3+ with BCMath & PDO extensions  
* Node 18+ & PNPM/Yarn  
* MySQL 8 / MariaDB 10.6+  
* Composer 2.7+  
*(or simply `docker compose up -d` with Laravel Sail)*

### 2. Clone & Install
```bash
git clone https://github.com/your-org/index.one.git
cd index.one

# PHP dependencies
composer install

# JS & CSS
pnpm install && pnpm run dev   # or yarn / npm

# Copy env and generate key
cp .env.example .env
php artisan key:generate
```

### 3. Configure Environment
Edit `.env` for database credentials:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=index_one
DB_USERNAME=root
DB_PASSWORD=secret
```

### 4. Migrate & Seed
```bash
php artisan migrate
php artisan db:seed --class=RelationTypeSeeder   # seeds is_a, part_of, etc.
```

---

## Running & Testing
```bash
# Start dev server
php artisan serve

# Livewire hot‑reloading
pnpm run dev   # Vite

# Run test suite
php artisan test          # PHPUnit
# or
pest                      # if Pest installed
```
Visit **http://localhost:8000**.

---

## Future Roadmap

| Milestone | Description |
|-----------|-------------|
| **Graph Sync** | Repository pattern to mirror Eloquent writes to Neo4j |
| **Tagging & Facets** | Many‑to‑many Tag model; UI filters |
| **Revision History** | ThingRevision & RelationRevision tables with diff viewer |
| **Public API** | Read‑only REST & GraphQL endpoints |
| **Multi‑Tenant** | Tenant‑aware models using Laravel 12 “Pennant” features |
| **OAuth 2.0** | Social / enterprise SSO via Laravel Passport |

---

## Contributing
1. Fork the repo & create a branch (`git checkout -b feature/my-awesome-thing`)  
2. Commit with **semantic‑release** style commits  
3. Push & open a PR describing **what** and **why**  
4. Ensure tests pass (`php artisan test`) and add new tests when reasonable

---

## License
Code released under the **MIT License** unless noted otherwise.  
© 2025 index.one contributors
