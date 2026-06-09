"use client";

import { useEffect } from "react";
import { AlertCircle } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";

type DashboardRouteErrorProps = {
  error: Error & { digest?: string };
  reset: () => void;
};

export function DashboardRouteError({
  error,
  reset,
}: DashboardRouteErrorProps) {
  const { t } = useLocale();

  useEffect(() => {
    console.error(error);
  }, [error]);

  return (
    <div className="flex flex-1 items-center justify-center p-6">
      <Alert variant="destructive" className="max-w-lg">
        <AlertCircle />
        <AlertTitle>{t("common.error")}</AlertTitle>
        <AlertDescription className="space-y-4">
          <p>{t("common.pageLoadError")}</p>
          <Button type="button" variant="outline" size="sm" onClick={reset}>
            {t("common.tryAgain")}
          </Button>
        </AlertDescription>
      </Alert>
    </div>
  );
}
