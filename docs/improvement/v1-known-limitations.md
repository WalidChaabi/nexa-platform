# V1 Known Limitations

Document maintained during V1 maintenance freeze. Items here are **accepted** until V2 cutover — not forgotten bugs.

| ID | Area | Limitation | V2 target |
|----|------|------------|-----------|
| L-01 | Framework | Laravel 5.7 / PHP 7.4 EOL | V2-PR-01 |
| L-02 | Auth | No Policy layer; middleware-only roles | V2-PR-06 |
| L-03 | Queues | `QUEUE_CONNECTION=sync` | V2-PR-09 |
| L-04 | Schema | No FK constraints / indexes on hot columns | V2-PR-04 |
| L-05 | UI | Multiple legacy layouts; weak mobile UX | V2-PR-20 |
| L-06 | Tests | Minimal coverage on V1 after Phase 0 | V2 module tests |
| L-07 | API | `routes/api.php` commented; no mobile API | V2 Phase 3 optional |

Update this file when closing a limitation in V2.
