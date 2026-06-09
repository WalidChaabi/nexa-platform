# Nexa Web (V2)

Next.js App Router frontend for Nexa.

**Stack:** Next.js 16, React 19, Tailwind CSS 4, shadcn/ui, lucide-react.

## Run locally

### With Docker (recommended)

From the repository root:

```bash
docker compose up -d
```

Web UI: http://localhost:3000

### Without Docker

```bash
cd apps/nexa-web
npm install
```

Create `.env.local`:

```env
NEXT_PUBLIC_API_URL=http://localhost:8000
```

```bash
npm run dev
```

## Scripts

| Command | Description |
|---------|-------------|
| `npm run dev` | Development server (http://localhost:3000) |
| `npm run build` | Production build |
| `npm run start` | Serve production build |
| `npm run lint` | ESLint |

## UI conventions

- Icons: **lucide-react** only
- Components: **shadcn/ui** (`@/components/ui/*`)
- Branding: `lib/brand.ts`, `components/brand/brand-logo.tsx`
- i18n: `lib/i18n/messages/{en,fr,ar}.ts`

See `.cursor/rules/nexa-web-ui.mdc` for project UI rules.

## Related

- API: [apps/nexa-api](../nexa-api/README.md)
- Full stack setup: [docs/v2-local-setup.md](../../docs/v2-local-setup.md)
