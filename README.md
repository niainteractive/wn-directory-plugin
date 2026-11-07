# Nia Directory

A reusable directory plugin for Winter CMS. Nested categories, tagged entries, and
directed relations between entries.

Built by [Nia Interactive](https://niainteractive.com) to power a catalogue of alternatives
to the products and services movement organizations buy — but it is deliberately generic, and
will run any directory: partners, vendors, resources, publications.

## What it does

- **Entries** — the things being catalogued. Each has a kind (product, service, software,
  organization), a body, a price note, provenance sources, and a verification date.
- **Categories** — a tree, unlimited depth. An entry may sit in many categories with one
  marked primary.
- **Relations** — directed edges between entries. `source replaces target`. Edges are
  explicit, never inferred, and a cycle is rejected at save time. So "Proton replaces Gmail"
  and "Tuta replaces Proton" can coexist, while "Gmail replaces Proton" is impossible.
- **Tags** — grouped (ownership, licence, ideology, reason). Stored from day one; hidden from
  the frontend until you turn them on.
- **Listing scope** — `listed` entries appear in category browsing; `reference` entries have a
  page and can be relation targets, but never appear in a category list.

## Install

```
composer require niainteractive/wn-directory-plugin
php artisan winter:up
```

Or clone into `plugins/niainteractive/directory` and run `php artisan winter:up`.

## Components

| Component | Purpose |
|---|---|
| `directoryCategories` | The category tree |
| `directoryList` | Published entries, optionally filtered by category slug |
| `directoryEntry` | One entry, its alternatives, and what it replaces |
| `directoryReplaceIndex` | A–Z list of entries that something else can replace |

## Settings

Backend → Settings → Nia Directory. Labels, entries per page, when an entry goes stale, and
whether tags are shown.

## Licence

GPL-3.0-or-later.
