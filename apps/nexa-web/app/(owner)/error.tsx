"use client";

import { DashboardRouteError } from "@/components/layout/dashboard-route-error";

export default function OwnerError({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  return <DashboardRouteError error={error} reset={reset} />;
}
