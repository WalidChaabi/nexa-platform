# V2 GitHub Execution Plan

Actionable PR catalog for the V2 rebuild — mirrors the V1 approach in [git-pr-and-release-workflow.md](./git-pr-and-release-workflow.md#6-pr-catalog--v1-phase-0-reference).

**Last updated:** 2026-06-08

---

## Current state (audit)

| Item | V1 | V2 (today) |
|------|----|------------|
| PR catalog in docs | Section 6 — 10 PRs with branches | Section 7 — partial; full list in [v2-platform-rebuild.md](./v2-platform-rebuild.md) |
| Branches pushed | `v1/*` branches on GitHub | Only `v2/chore/monorepo-layout` (+ `v2/main`) |
| Open PRs | #1–#7 (security stack) | **#8 only** (monorepo scaffold) |
| GitHub milestones | Documented, **not created** | Same — not created |
| GitHub labels (`track/v2`, etc.) | Documented, **not created** | Default labels only |
| PR / issue templates | `.github/pull_request_template.md` | Present |
| CI workflows | `v1-ci.yml` | `v1-ci.yml` + `v2-ci.yml` (skeleton) |
| Uncommitted local work | — | **All Phase 0–1 feature work** (auth, schema, Docker, policies, invoices) |

### Local-only work (not committed, not pushed)

Everything below lives on branch `v2/chore/monorepo-layout` as unstaged/untracked changes:

- Docker dev stack (`docker-compose.yml`, API/Web Dockerfiles)
- Sanctum SPA auth + role middleware (API + Next.js login/dashboards)
- Policy layer (Store, Invoice, Customer, Product, Campaign, Rdv)
- Full V1 schema port (migrations `2026_06_09_*`)
- Invoices API (controller, service, resources, tests)
- Employer invoices UI (list, detail, cancel)
- shadcn/ui setup on `sherin-web`

**Risk:** one laptop failure or accidental `git restore` loses all of this until it is committed and pushed.

---

## Base branch strategy

Documented target: **`v2/main`** for all V2 feature PRs.

| Branch | Tip commit | Notes |
|--------|------------|-------|
| `main` | `ef40af3` — initial import only | Production V1 line (old) |
| `v2/main` | `f4e8f64` — monorepo layout | V2 integration branch |
| `v2/chore/monorepo-layout` | same as `v2/main` + **local changes** | PR #8 head |

**PR #8** currently targets `main`, not `v2/main`. Decide before merge:

- **Option A (recommended):** Retarget PR #8 → `v2/main`, merge monorepo there first; keep `main` for V1 maintenance until cutover.
- **Option B:** Merge PR #8 → `main` and treat `main` as the unified monorepo (folds V1 security stack + V2 scaffold in one step).

---

## V2 PR catalog — Phase 0 (bootstrap)

Execute in order. Status reflects **2026-06-08** workspace.

| ID | Branch | PR title | Depends on | Status | Notes |
|----|--------|----------|------------|--------|-------|
| V2-PR-01 | `v2/chore/monorepo-layout` | `chore(v2): monorepo layout — legacy/v1, sherin-api, sherin-web` | — | **PR #8 open** | Pushed; merge first |
| V2-PR-01b | `v2/chore/docker-dev` | `chore(docker): add MySQL + API + web compose stack for local dev` | V2-PR-01 | **Local only** | Split from current uncommitted work |
| V2-PR-02 | `v2/chore/ci` | `chore(ci): wire v2-ci to API tests and web lint/build` | V2-PR-01 | Not started | `v2-ci.yml` exists but not wired to real checks |
| V2-PR-03 | `v2/docs/adr` | `docs(adr): confirm ADR-002 and local setup docs` | V2-PR-01 | Partial | ADR exists; no dedicated PR |
| V2-PR-04 | `v2/db/port-migrations` | `db(v2): port V1 schema with FKs and indexes` | V2-PR-01 | **Local only** | Migrations `2026_06_09_*` + `MigrateFreshTest` |
| V2-PR-05 | `v2/feat/auth-roles` | `feat(auth): Sanctum SPA login, roles, and Next.js auth UI` | V2-PR-04 | **Local only** | API auth + web login + dashboards |
| V2-PR-06 | `v2/feat/policies` | `feat(auth): store-scoped policies and feature tests` | V2-PR-05 | **Local only** | Policies + `StoreScopedPolicyTest` |

### Suggested split of current local work → PRs

```
v2/chore/monorepo-layout     [merged via PR #8]
    └── v2/chore/docker-dev          → V2-PR-01b
            └── v2/db/port-migrations → V2-PR-04
                    └── v2/feat/auth-roles    → V2-PR-05
                            └── v2/feat/policies → V2-PR-06
```

Each branch contains **only** its concern (stacked PRs, same pattern as V1).

---

## V2 PR catalog — Phase 1 (platform)

| ID | Branch | PR title | Depends on | Status |
|----|--------|----------|------------|--------|
| V2-PR-07 | `v2/feat/gateways` | `feat(integrations): BulkSMS and CMI gateway interfaces` | V2-PR-06 | Not started |
| V2-PR-08 | `v2/chore/logging` | `chore(logging): structured log channels for API` | V2-PR-06 | Not started |
| V2-PR-09 | `v2/feat/queues` | `feat(queue): database queue + failed jobs baseline` | V2-PR-06 | Not started |

---

## V2 PR catalog — Phase 2 (modules)

| ID | Branch | PR title | Depends on | Status |
|----|--------|----------|------------|--------|
| V2-PR-13a | `v2/feat/invoices-models` | `feat(invoice): models, factories, and demo seeder` | V2-PR-06 | **Local only** |
| V2-PR-13b | `v2/feat/invoices-api` | `feat(invoice): API v1 list/create/show/cancel + IDOR tests` | V2-PR-13a | **Local only** |
| V2-PR-13c | `v2/feat/invoices-web` | `feat(invoice-web): employer invoice list and detail pages` | V2-PR-13b | **Local only** |
| V2-PR-10 | `v2/feat/stores` | `feat(store): store management API + UI` | V2-PR-06 | Not started |
| V2-PR-11 | `v2/feat/customers` | `feat(customer): CRM API + UI` | V2-PR-10 | Not started |
| V2-PR-12 | `v2/feat/products` | `feat(product): catalog API + UI` | V2-PR-10 | Not started |

Full module list (payments, campaigns, RDV, reports, admin): [v2-platform-rebuild.md § V2 PR roadmap](./v2-platform-rebuild.md#v2-pr-roadmap-full-catalog).

---

## GitHub repository setup (one-time)

Mirror V1 hygiene from [git-pr-and-release-workflow.md §4](./git-pr-and-release-workflow.md#4-github-setup-repository-hygiene).

### Milestones to create

| Milestone | Track | Target |
|-----------|-------|--------|
| `V2 Phase 0 — Bootstrap` | V2 | Week 4 |
| `V2 Phase 1 — Platform` | V2 | Month 2 |
| `V2 Phase 2 — Business modules` | V2 | Month 4 |
| `V2 Phase 3 — Cutover` | V2 | Month 6 |

### Labels to create

```
track/v1, track/v2
type/security, type/bug, type/feature, type/chore, type/docs
priority/critical, priority/high, priority/medium
area/auth, area/invoice, area/campaign, area/payment, area/infra, area/docker
status/blocked, status/needs-review
```

### Issues (optional but recommended)

One GitHub issue per PR ID (`V2-PR-04`, etc.) with:

- Label: `track/v2`, `type/feature` or `type/chore`
- Milestone: matching phase
- Link in PR description: `Fixes #N`

### Protected branches

On `v2/main` (when team is ready):

- Require PR before merge
- Require `v2-ci` green
- No force push

---

## Immediate action checklist

Do these in order to align V2 with the V1 GitHub discipline:

- [ ] **1. Merge or retarget PR #8** — decide base (`v2/main` vs `main`)
- [ ] **2. Commit local work** — split into stacked branches (see split above); never one 5k-line PR
- [ ] **3. Push branches** — `git push -u origin <branch>` for each
- [ ] **4. Open PRs** — titles follow conventional commits; use `.github/pull_request_template.md`
- [ ] **5. Create milestones + labels** on GitHub (UI or `gh api`)
- [ ] **6. Open tracking issues** for V2-PR-04 through V2-PR-13c
- [ ] **7. Wire `v2-ci.yml`** — run `php artisan test` in `apps/sherin-api` on every V2 PR

### Commands (after splitting locally)

```bash
# Example: push first feature branch
git checkout v2/db/port-migrations
git push -u origin HEAD
gh pr create --base v2/main --title "db(v2): port V1 schema with FKs and indexes" \
  --body "$(cat <<'EOF'
## Summary
- Port remaining V1 tables into batched migrations
- Add FKs, indexes, and migrate:fresh test coverage

## Track
- [x] V2 (rebuild)

## Test plan
- [x] `cd apps/sherin-api && php artisan test`
EOF
)"
```

---

## Comparison to V1 (what we had)

V1 shipped **7 stacked security PRs** (#1–#7), each on its own branch, with a documented catalog and execution order. V2 has the **documentation** (rebuild plan + git workflow) but not the **GitHub execution**:

| V1 done | V2 missing |
|---------|------------|
| Branches pushed per concern | Single branch, unpushed feature dump |
| PR per security fix | 1 PR (#8), features not in PRs |
| Execution order in docs | Same in docs, not followed in git |
| Test plan in each PR | Tests exist locally, not in CI on PRs |

---

## Definition of done — V2 Phase 0 (GitHub)

- [ ] PR #8 merged to chosen base branch
- [ ] V2-PR-01b through V2-PR-06 merged to `v2/main` (stacked)
- [ ] V2-PR-13a–c merged (invoices slice)
- [ ] All work committed and pushed (no local-only feature branches)
- [ ] Milestones + `track/v2` labels on GitHub
- [ ] `v2-ci` green on `v2/main`

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-08 | Initial V2 GitHub execution plan and local-work audit |
