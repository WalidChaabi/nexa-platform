# V1 Known Limitations

Document maintained during V1 maintenance freeze. Items here are **accepted** until V2 cutover — not forgotten bugs.

| ID | Area | Limitation | V2 target |
|----|------|------------|-----------|
| L-01 | Framework | Laravel 5.7 / PHP 7.4 EOL | V2-PR-01 |
| L-02 | Auth | No Policy layer; middleware + controller traits only | V2-PR-06 |
| L-03 | Queues | `QUEUE_CONNECTION=sync` | V2-PR-09 |
| L-04 | Schema | No FK constraints / indexes on hot columns | V2-PR-04 |
| L-05 | UI | Multiple legacy layouts; weak mobile UX | V2-PR-20 |
| L-06 | Tests | Static/security tests on V1; limited integration coverage | V2 module tests |
| L-07 | API | `routes/api.php` commented; no mobile API | V2 Phase 3 optional |
| L-08 | XSS | Product history allows limited HTML tags via `SafeHtml` | V2 sanitize or markdown |
| L-09 | Admin payments | `resetCampaigns` / `payAll` use hidden confirmation + audit log, not re-auth | V2 payment module |

## V1 code-complete checklist (2026-06-08)

- [x] Phase 0 security remediations implemented on `v1/*` branches
- [x] Phase 1 critical bug fixes implemented
- [x] Mass assignment hardening
- [x] Security test baseline (static)
- [ ] All PRs merged to `main` (user decision)
- [ ] Production secrets rotated (`docs/runbooks/secret-rotation.md`)
- [ ] POS tokens migrated (`docs/runbooks/pos-token-migration.md`)
- [ ] Server cron for `schedule:run` enabled
- [x] V2 bootstrap started (`legacy/v1`, `apps/nexa-api`, `apps/nexa-web`)

Update this file when closing a limitation in V2.
