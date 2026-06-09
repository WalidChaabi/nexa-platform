# Nexa Improvement Program

**Repository:** `nexa-platform`  
**Strategy:** Parallel tracks — **V1** (production risk containment) and **V2** (platform rebuild)  
**Last updated:** 2026-06-09

---

## Purpose

This folder is the single source of truth for how we evolve Nexa from a legacy Laravel 5.7 monolith into a maintainable, secure, testable platform — without throwing away working domain logic.

| Document | Audience | Use when |
|----------|----------|----------|
| [V1 — Security & Maintenance Plan](./v1-security-and-maintenance.md) | DevOps, backend, security | Production is live and must stay safe during V2 |
| [V2 — Platform Rebuild Plan](./v2-platform-rebuild.md) | Tech lead, backend, frontend | Building the next-generation application |
| [Git, PR & Release Workflow](./git-pr-and-release-workflow.md) | All engineers | Every branch, commit, and pull request |
| [Engineering Standards](./engineering-standards.md) | All engineers | Code, logs, docs, testing conventions |

**Related:** [Technical Audit Report](../technical-audit-report.md) — findings that drive this program.

---

## Executive summary

```
┌─────────────────────────────────────────────────────────────────┐
│  V1 (main branch)          │  V2 (v2/* branches → v2/main)    │
│  ─────────────────         │  ────────────────────────────    │
│  Security triage           │  Laravel 11 API + Next.js 16 web   │
│  Critical bug fixes only   │  Port domain services + schema     │
│  No new features           │  Policies, queues, shadcn UI       │
│  ~2–4 weeks active work    │  ~4–6 months to cutover            │
└─────────────────────────────────────────────────────────────────┘
                              │
                    Shared database (initially)
                    Shared integration credentials (.env)
                    Shared behavioral tests (ported to V2)
```

**Rule:** V1 and V2 are **not** duplicate effort. V1 closes production holes. V2 carries forward domain logic, data model, and integration knowledge.

---

## Timeline (high level)

| Phase | Track | Duration | Outcome |
|-------|-------|----------|---------|
| 0 | V1 | Week 1–2 | Production secured, secrets rotated |
| 1 | V1 + V2 | Week 2–4 | V1 stable; V2 repo bootstrapped + CI |
| 2 | V2 | Month 2–3 | Core domain ported with tests |
| 3 | V2 | Month 3–5 | UI modules + integrations |
| 4 | Both | Month 5–6 | Parity checklist, cutover, V1 decommission |

---

## Branch map

| Branch | Purpose |
|--------|---------|
| `main` | V1 production line — maintenance only after Phase 0 |
| `v2/main` | V2 integration branch (created when V2 repo or monorepo `apps/v2` is ready) |
| `v1/phase-0-security` | V1 security sprint |
| `v1/hotfix/*` | Emergency production fixes |
| `v2/feat/*` | V2 feature modules |
| `v2/chore/*` | V2 tooling, CI, deps |

See [Git, PR & Release Workflow](./git-pr-and-release-workflow.md) for full conventions.

---

## How to use these docs

1. **Tech lead** — Review V1 Phase 0 checklist before any V2 work ships to shared infrastructure.
2. **Implementer** — Open the relevant phase in V1 or V2 doc; create PR using the template in `git-pr-and-release-workflow.md`.
3. **Reviewer** — Use PR checklist + engineering standards for every review.
4. **Update docs** — When a phase completes, mark checkboxes in the plan and add a line to the changelog section at the bottom of each doc.

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-08 | Initial improvement program documentation created |
| 2026-06-08 | **V1 Phase 0 started** — security fixes implemented (see commits on `main`) |
| 2026-06-08 | **V1 code-complete** — stacked PRs #1–#7; ops merge + rotation pending |
| 2026-06-08 | **V2 UI locked:** Next.js + Laravel API ([ADR-002](../adr/002-nextjs-api-first-ui.md)); mobile after cutover |
| 2026-06-09 | **Nexa rebrand** — product naming, config (`nexa.php`), demo seeder, and READMEs aligned to Nexa |
