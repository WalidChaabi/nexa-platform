# POS token migration (V1)

## Background

POS owner password checks used a plaintext `users.token` column. V1 stores a bcrypt hash in `users.pos_token_hash` and verifies with `Hash::check()`.

## Deploy steps

1. Run migrations:
   ```bash
   php artisan migrate
   ```
2. Hash existing plaintext tokens (production):
   ```bash
   php artisan sherin:migrate-pos-tokens --dry-run
   php artisan sherin:migrate-pos-tokens
   ```
3. When setting a new POS token for an owner, use `PosTokenService::setToken($user, $plainToken)` — never persist plaintext.

## Rollback

Legacy verification still works if `pos_token_hash` is empty and `users.token` is populated. After migration, plaintext `token` is cleared.
