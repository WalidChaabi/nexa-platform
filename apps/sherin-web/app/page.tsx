import { getApiBaseUrl } from "@/lib/api/client";

export default function Home() {
  return (
    <div className="flex min-h-screen flex-col items-center justify-center bg-zinc-50 px-6">
      <main className="w-full max-w-lg rounded-2xl border border-zinc-200 bg-white p-10 shadow-sm">
        <p className="text-sm font-medium uppercase tracking-wide text-violet-600">
          Sherin V2
        </p>
        <h1 className="mt-2 text-3xl font-semibold text-zinc-900">
          Platform monorepo
        </h1>
        <p className="mt-4 text-zinc-600">
          Next.js web UI for Sherin.ma. API base:{" "}
          <code className="rounded bg-zinc-100 px-1.5 py-0.5 text-sm">
            {getApiBaseUrl()}
          </code>
        </p>
        <p className="mt-6 text-sm text-zinc-500">
          Health check:{" "}
          <a
            className="text-violet-700 underline"
            href={`${getApiBaseUrl()}/api/v1/health`}
            target="_blank"
            rel="noreferrer"
          >
            /api/v1/health
          </a>
        </p>
      </main>
    </div>
  );
}
