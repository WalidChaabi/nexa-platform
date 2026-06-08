# Git, PR & Release Workflow

Senior-team conventions for branches, commits, pull requests, reviews, and releases across V1 and V2.

---

## 1. Branching strategy

### V1 (maintenance on `main`)

```
main
 ├── v1/phase-0-security          # Security sprint (squash or merge per PR)
 ├── v1/fix/customer-admin-list   # One concern per branch
 ├── v1/hotfix/sqli-campaign      # Production emergencies → main
 └── release/v1.x.y               # Optional tagged maintenance releases
```

### V2 (rebuild on `v2/main`)

```
v2/main
 ├── v2/chore/bootstrap-laravel11
 ├── v2/feat/auth-and-roles
 ├── v2/feat/invoices-module
 └── v2/feat/campaigns-module
```

**Rules:**

- Branch from the correct base (`main` for V1, `v2/main` for V2).
- Keep branches short-lived (target &lt; 5 days, &lt; 400 lines changed).
- One logical change per PR. Split large work into stacked PRs if needed.
- Never force-push `main` or `v2/main`.
- Hotfixes: branch from `main` → PR → merge → tag `v1.x.y` → cherry-pick to `v2/main` if the fix applies.

---

## 2. Commit message format

Use **[Conventional Commits](https://www.conventionalcommits.org/)** — enables changelog generation and clear history.

```
<type>(<scope>): <imperative summary>

[optional body — what and why, not how]

[optional footer: Fixes #123, BREAKING CHANGE:, Security:]
```

### Types

| Type | When |
|------|------|
| `feat` | New behavior (V2 modules) |
| `fix` | Bug fix |
| `security` | Security remediation (always use for V1 Phase 0) |
| `refactor` | Behavior unchanged |
| `test` | Tests only |
| `docs` | Documentation |
| `chore` | Tooling, deps, CI |
| `perf` | Performance |
| `db` | Migrations, indexes (V2) |

### Scopes (examples)

`auth`, `invoice`, `campaign`, `payment`, `customer`, `store`, `sms`, `ci`, `deps`, `routes`, `config`

### Examples

```bash
security(routes): remove unauthenticated /clear and debug campaign endpoints

fix(customer): define clients and stores for admin index view

security(campaign): bind store_id in CampaignDetailController raw queries

feat(v2-invoice): add InvoicePolicy and store-scoped queries

db(v2-schema): add indexes on invoices.store_id and customers.phone

chore(ci): add GitHub Actions workflow for Pest and Pint
```

### Commit hygiene

- Commit early, but **squash fixup commits** before merge unless the team agrees on merge commits.
- Never commit `.env`, credentials, `gb/`, or `vendor/`.
- Co-author bots only when appropriate (`Co-authored-by: Cursor <...>` is optional; prefer human attribution).

---

## 3. Pull request conventions

### PR title

Same format as commit summary:

```
security(routes): remove public /clear and debug SMS triggers
```

### PR size guidelines

| Size | Lines (approx) | Review time |
|------|----------------|-------------|
| XS | &lt; 50 | Same day |
| S | 50–200 | 1 reviewer |
| M | 200–400 | 1–2 reviewers |
| L | 400+ | **Split the PR** |

Security PRs stay **small and reviewable** — one vulnerability class per PR when possible.

### PR description template

Create `.github/pull_request_template.md` (see Phase 0 chore below):

```markdown
## Summary
<!-- 1–3 bullets: what and why -->

## Type
- [ ] security
- [ ] fix
- [ ] feat
- [ ] refactor
- [ ] chore
- [ ] docs

## Track
- [ ] V1 (maintenance)
- [ ] V2 (rebuild)

## Changes
<!-- List files/areas touched -->

## Security impact
- [ ] No security impact
- [ ] Closes exposure (describe)
- [ ] Requires secret rotation (describe)
- [ ] Requires deployment coordination

## Test plan
- [ ] `php artisan test` (or `./vendor/bin/pest`)
- [ ] Manual steps listed below
- [ ] N/A (docs-only)

### Manual verification
1. ...

## Checklist
- [ ] No secrets in diff
- [ ] No `dd()` / debug routes added
- [ ] Form validation / authorization considered
- [ ] Docs updated if behavior or setup changed
- [ ] Migration rollback considered (if applicable)

## Linked issues
Fixes #
```

### Review requirements

| Track | Approvals | CI |
|-------|-----------|-----|
| V1 security | 1 senior + security checklist | Required green |
| V1 fix | 1 engineer | Required green |
| V2 feat | 1 engineer + tests in PR | Required green |
| V2 db migration | 2 engineers | Required green |

Use **GitHub CODEOWNERS** when team grows:

```
# .github/CODEOWNERS
* @sherin-tech-lead
app/Http/Controllers/ @sherin-backend
app/Services/ @sherin-backend
routes/ @sherin-backend
docs/ @sherin-tech-lead
```

---

## 4. GitHub setup (repository hygiene)

### Milestone structure

| Milestone | Track | Target |
|-----------|-------|--------|
| `V1 Phase 0 — Security` | V1 | Week 2 |
| `V1 Phase 1 — Critical bugs` | V1 | Week 4 |
| `V2 Phase 0 — Bootstrap` | V2 | Week 4 |
| `V2 Phase 1 — Domain core` | V2 | Month 2 |
| `V2 Phase 2 — Business modules` | V2 | Month 4 |
| `V2 Phase 3 — Cutover` | V2 | Month 6 |

### Labels

```
track/v1, track/v2
type/security, type/bug, type/feature, type/chore, type/docs
priority/critical, priority/high, priority/medium
area/auth, area/invoice, area/campaign, area/payment, area/infra
status/blocked, status/needs-rotation
```

### Issue → branch → PR flow

1. Open GitHub Issue with label + milestone.
2. Branch: `v1/fix/issue-42-customer-admin` or `v2/feat/issue-100-invoice-policy`.
3. PR title references issue: `fix(customer): admin list undefined vars (fixes #42)`.
4. Squash merge to keep `main` / `v2/main` readable (team preference; document in README).

### Protected branches

Configure on GitHub for `main` and `v2/main`:

- Require PR before merge
- Require status checks: `test`, `lint` (when CI exists)
- Require linear history (optional)
- Block force push

---

## 5. Release & deployment

### V1 releases

Tag maintenance releases after Phase 0:

```bash
git tag -a v1.0.1-security -m "Phase 0 security remediation"
git push origin v1.0.1-security
```

**Release notes** (GitHub Releases) must include:

- Security fixes (without exploit details)
- Required `.env` changes
- Secret rotation steps
- Deployment order (migrate → config cache → restart queue)

### V2 releases

Pre-cutover: `v2.0.0-alpha.N`, `v2.0.0-beta.N`  
Production cutover: `v2.0.0`

### Deployment checklist (every production deploy)

- [ ] `.env` variables documented in `.env.example`
- [ ] `php artisan config:cache` only after env verified
- [ ] Queue worker restarted if jobs changed
- [ ] Scheduler cron verified (`* * * * * php artisan schedule:run`)
- [ ] Rollback tag identified before deploy

---

## 6. PR catalog — V1 Phase 0 (reference)

Execute in order; dependencies noted.

| PR # | Branch | Title | Depends on |
|------|--------|-------|------------|
| V1-PR-01 | `v1/security/remove-phishing-kit` | `security(repo): remove gb phishing kit from repository` | — |
| V1-PR-02 | `v1/security/externalize-secrets` | `security(config): move SMS and CMI credentials to env only` | Ops: rotate keys |
| V1-PR-03 | `v1/security/close-public-routes` | `security(routes): remove /clear and unauthenticated debug endpoints` | — |
| V1-PR-04 | `v1/security/fix-sqli-campaign-detail` | `security(campaign): parameterize store_id in CampaignDetailController` | — |
| V1-PR-05 | `v1/security/mass-assignment` | `security(models): restrict fillable on User and Customer` | — |
| V1-PR-06 | `v1/security/get-to-post-deletes` | `security(routes): replace GET delete routes with DELETE/POST` | — |
| V1-PR-07 | `v1/security/stop-logging-secrets` | `security(sms): redact tokens from CampaignService logs` | V1-PR-02 |
| V1-PR-08 | `v1/security/idor-invoices` | `security(invoice): enforce store ownership on show/print/cancel` | — |
| V1-PR-09 | `v1/chore/github-templates` | `chore(github): add PR template, issue templates, and CI skeleton` | — |
| V1-PR-10 | `v1/fix/remove-dd-calls` | `fix(debug): remove active dd() in Repport and Invoice controllers` | — |

---

## 7. PR catalog — V2 (reference)

**Execution tracker (status, branches, local-work split):** [V2 GitHub Execution Plan](./v2-github-execution-plan.md)

### Phase 0 — bootstrap

| PR # | Branch | Title | Status (2026-06-08) |
|------|--------|-------|---------------------|
| V2-PR-01 | `v2/chore/monorepo-layout` | `chore(v2): monorepo layout — legacy/v1, sherin-api, sherin-web` | PR **#8** open |
| V2-PR-01b | `v2/chore/docker-dev` | `chore(docker): MySQL + API + web compose stack` | Local only |
| V2-PR-02 | `v2/chore/ci` | `chore(ci): wire v2-ci to API tests and web build` | Not started |
| V2-PR-03 | `v2/docs/adr` | `docs(adr): architecture decision records for V2 stack` | Partial |
| V2-PR-04 | `v2/db/port-migrations` | `db(v2): port and normalize migrations with indexes and FKs` | Local only |
| V2-PR-05 | `v2/feat/auth-roles` | `feat(auth): Sanctum SPA login and Next.js auth UI` | Local only |
| V2-PR-06 | `v2/feat/policies` | `feat(auth): store-scoped policies and tests` | Local only |

### Phase 2 — invoices (first module slice)

| PR # | Branch | Title | Status (2026-06-08) |
|------|--------|-------|---------------------|
| V2-PR-13a | `v2/feat/invoices-models` | `feat(invoice): models, factories, demo seeder` | Local only |
| V2-PR-13b | `v2/feat/invoices-api` | `feat(invoice): API list/create/show/cancel` | Local only |
| V2-PR-13c | `v2/feat/invoices-web` | `feat(invoice-web): employer invoice pages` | Local only |

Full module PR list: [V2 Platform Rebuild Plan](./v2-platform-rebuild.md).

---

## 8. Stacked PRs (advanced)

For large V2 modules (e.g. Invoices):

```
v2/feat/invoices-01-models     → v2/main
v2/feat/invoices-02-policy    → v2/feat/invoices-01-models
v2/feat/invoices-03-service   → v2/feat/invoices-02-policy
v2/feat/invoices-04-ui        → v2/feat/invoices-03-service
```

Merge sequentially after each review. Final PR merges into `v2/main`.

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-08 | Initial git/PR workflow documentation |
| 2026-06-08 | Link V2 execution plan; expand Phase 0 + invoices PR status |
