# Runbook: V2 Incident Response

Operational playbook for production issues on the Nexa V2 stack (`apps/nexa-api` + `apps/nexa-web`).

---

## Severity levels

| Level | Examples | Response target |
|-------|----------|-----------------|
| **SEV-1** | Login down, CMI callbacks failing, data loss | Immediate — page on-call |
| **SEV-2** | SMS queue stalled, scheduler not running, single store blocked | < 1 hour |
| **SEV-3** | UI glitch, non-critical report delay | Next business day |

---

## First 15 minutes (any SEV-1/2)

1. **Confirm scope** — all users vs one store vs one role (admin / owner / employer).
2. **Check health**
   - API: `GET /api/v1/health`
   - Web: homepage + `/login`
3. **Recent deploy?** Compare timestamp with last release tag / CI run.
4. **Logs**
   - API: `storage/logs/laravel.log`, integration channel (`config/logging.php`)
   - Queue: failed jobs table / Horizon / worker stdout
   - Web: hosting provider logs (Vercel, Node process, reverse proxy)
5. **Database** — connection errors, migration pending, disk full.

---

## Common incidents

### Users cannot log in (Sanctum / cookies)

- Verify `APP_URL`, `FRONTEND_URL`, `SANCTUM_STATEFUL_DOMAINS` match production hostnames (no trailing slashes).
- CORS: browser console shows blocked credentialed requests → fix `config/cors.php` / env.
- Session: `SESSION_DOMAIN` must align with cookie domain strategy (often `.nexa.app` or host-only).
- Rollback last deploy if env regression.

### CMI payment callbacks not settling

- Check `historique_paiements` for new rows stuck in pending.
- Search logs for `payment.callback` / hash mismatch (do **not** mark paid on hash failure).
- Confirm callback URL reachable from CMI (firewall, TLS cert).
- Verify `PaymentFulfillmentService` jobs dispatched (`jobs` / `failed_jobs` tables).
- See [deployment-v2.md](./deployment-v2.md) queue worker section.

### SMS / campaigns not sending

- `php artisan schedule:list` — are `nexa:*` commands due?
- Cron: `* * * * * php artisan schedule:run` running on API host?
- Queue worker alive? `QUEUE_CONNECTION=redis` + `queue:work` or Horizon.
- Birthday billing: unpaid rows in `messages_customers` until owner pays via checkout.

### Scheduler ran but no SMS

- Cross-selling: invoice date + rule `days` offset must match today (Casablanca TZ).
- RDV reminders: appointment `start` within next 30 minutes; hourly cron may miss edge cases if cron skewed — prefer `schedule:run` every minute.

### API 500 after deploy

- `php artisan migrate:status` — pending migrations?
- `php artisan config:clear` then `config:cache` after env change.
- Roll back deploy; restore DB only if migration was destructive.

---

## Rollback (fast)

1. Revert to previous release tag on API + web.
2. `composer install --no-dev`, `php artisan config:cache`, restart PHP-FPM / containers.
3. Redeploy previous web build artifact.
4. If DNS already flipped and rollback insufficient → execute [deployment-v2.md](./deployment-v2.md) DNS rollback to V1 (14-day window per cutover plan).

---

## Communication template

```
[Nexa V2] SEV-X — <short title>
Impact: <who cannot do what>
Status: Investigating | Mitigated | Resolved
Workaround: <if any>
ETA: <optional>
```

---

## Post-incident

- [ ] Root cause documented in ticket
- [ ] Add regression test or monitor if repeatable
- [ ] Update runbook if gap found

---

## Related

- [deployment-v2.md](./deployment-v2.md)
- [v2-platform-rebuild.md](../improvement/v2-platform-rebuild.md) — W7/W8 cutover checklist
