# ADR-002: Next.js web UI + Laravel API (mobile-ready)

**Status:** Accepted  
**Date:** 2026-06-08  
**Deciders:** Product / tech lead (Walid)

## Context

V2 replaces the Laravel 5.7 Blade monolith. We need a modern web UI and plan to ship a **mobile app later**. The team prefers **Next.js** for V2 web rather than Livewire or Inertia.

Requirements:

- Port domain logic (invoices, campaigns, SMS, CMI) without another full rewrite when mobile ships.
- One authorization model (`store_id` scoping, roles: admin / owner / employer).
- Shared MySQL schema during cutover from V1.

## Decision

Adopt a **decoupled monorepo**:

| App | Path | Role |
|-----|------|------|
| **API** | `apps/nexa-api/` | Laravel 11, PHP 8.3 — **API-only** (no Blade UI) |
| **Web** | `apps/nexa-web/` | Next.js 16+ App Router, TypeScript, Tailwind, **shadcn/ui** |

**Integration rules:**

1. All business rules live in Laravel (Services, Policies, Form Requests, Jobs).
2. Web and future mobile consume the **same REST JSON API** (`/api/v1/*`).
3. **Auth:** Laravel Sanctum — cookie/session (SPA) for Next.js web; **personal access tokens** for mobile (added when mobile starts).
4. Responses use **API Resources** with stable shapes; no raw Eloquent in JSON.
5. Next.js does **not** duplicate validation or authorization — it displays API errors and handles UX only.

## Consequences

### Positive

- Mobile app reuses the same API; no second backend.
- Clear frontend/backend boundary; Next.js team can work in parallel after API contracts exist.
- Modern UX (shadcn/ui, RSC where appropriate) without fighting Blade/jQuery legacy.
- API contract tests protect both web and mobile clients.

### Negative

- Two runtimes in dev (PHP + Node); CI runs both pipelines.
- Slightly longer Phase 0 bootstrap vs Livewire-only.
- CORS, Sanctum cookie domain, and deployment (two apps or reverse proxy) must be configured once.

### Follow-up

- [ ] V2-PR-01: Scaffold `apps/nexa-api`
- [ ] V2-PR-01b: Scaffold `apps/nexa-web`
- [ ] V2-PR-05: Sanctum SPA auth + login API for Next.js
- [ ] Document local dev: API on `:8000`, web on `:3000`, env `NEXT_PUBLIC_API_URL`
- [ ] Phase 5 (post-cutover): mobile app + token auth endpoints

## Alternatives considered

| Option | Rejected because |
|--------|------------------|
| Livewire 3 + Flux | Team preference for Next.js; does not match chosen frontend stack |
| Inertia + Vue/React inside Laravel | Still couples UI deploy to PHP; less ideal for separate mobile client |
| Next.js Route Handlers as BFF only | Duplicates logic; Laravel already owns domain — API stays single source of truth |
| API-only from day one, no Next until later | Delays user-facing progress; web is still the primary client for cutover |
