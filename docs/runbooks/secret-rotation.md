# Runbook: Secret Rotation

Use this runbook when credentials were exposed in source code, logs, or a security incident.

---

## When to use

- BulkSMS token found in `CampaignService` or logs
- CMI store key committed to `config/app.php`
- Mailgun or other integration secret in repository
- Phishing kit (`gb/`) removal — rotate any keys used in that directory

---

## Pre-rotation

1. Identify all exposed credentials (see [technical audit](../technical-audit-report.md)).
2. Open incident issue with label `status/needs-rotation`.
3. Schedule maintenance window if payment or SMS will briefly fail.
4. Ensure V1-PR-02 (externalize secrets) is merged or deploy with env-only config.

---

## BulkSMS

1. Log in to BulkSMS provider dashboard.
2. **Revoke** compromised token.
3. **Generate** new API token.
4. Update production `.env`:
   ```env
   BULKSMS_TOKEN=new_token_here
   BULKSMS_ENDPOINT=https://bulksms.ma/developer/sms/send
   ```
5. `php artisan config:clear && php artisan config:cache`
6. Send test SMS from staging.
7. Verify `storage/logs` contains no full token strings.

---

## CMI Payment

1. Contact CMI / payment provider if keys were public.
2. Obtain new `CMI_STORE_KEY` and client credentials.
3. Update `.env`:
   ```env
   CMI_CLIENT_ID=
   CMI_STORE_KEY=
   ```
4. Test checkout on staging with small amount.
5. Verify callback hash validation still passes.

---

## Application key

If `.env` `APP_KEY` was exposed:

```bash
php artisan key:generate --show   # generate new, update .env carefully
```

**Warning:** Rotating `APP_KEY` invalidates encrypted sessions and data. Plan user re-login.

---

## Post-rotation

- [ ] Old credentials revoked at provider
- [ ] Production `.env` updated
- [ ] Config cached
- [ ] Smoke test: login, invoice, SMS test, payment test
- [ ] Grep logs for old token patterns
- [ ] Close incident issue
- [ ] Document date in team changelog

---

## Prevention

- No secrets in `config/*.php` fallbacks
- `gitleaks` or `trufflehog` in CI
- See [engineering standards](../improvement/engineering-standards.md#5-logging-standards)
