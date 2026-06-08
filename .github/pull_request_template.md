## Summary
<!-- 1–3 bullets: what changed and why -->

## Type
- [ ] security
- [ ] fix
- [ ] feat
- [ ] refactor
- [ ] chore
- [ ] docs
- [ ] test

## Track
- [ ] V1 (maintenance — `main`)
- [ ] V2 (rebuild — `v2/main`)

## Changes
<!-- Key files or areas -->

## Security impact
- [ ] No security impact
- [ ] Closes exposure (describe)
- [ ] Requires secret rotation (list keys and runbook link)
- [ ] Requires deployment coordination

## Test plan
- [ ] `php artisan test` / `./vendor/bin/pest`
- [ ] `./vendor/bin/pint --test` (V2)
- [ ] Manual steps below
- [ ] N/A (docs-only)

### Manual verification
1. 

## Checklist
- [ ] No secrets, tokens, or credentials in diff
- [ ] No `dd()`, `dump()`, or debug routes added
- [ ] Authorization and validation considered for write endpoints
- [ ] `.env.example` updated if new environment variables
- [ ] Docs updated if behavior or setup changed
- [ ] Migration rollback considered (if applicable)

## Linked issues
Fixes #

## Deployment notes
<!-- Env changes, rotation, maintenance window — or "None" -->
