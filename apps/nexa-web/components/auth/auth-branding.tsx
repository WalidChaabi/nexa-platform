"use client";

import { useLocale } from "@/components/providers/locale-provider";

export function AuthBranding() {
  const { t } = useLocale();

  return (
    <div className="relative hidden overflow-hidden bg-primary text-primary-foreground lg:block">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#00C38933,transparent_55%)]" />
      <div className="absolute inset-0 bg-[linear-gradient(160deg,transparent_40%,#00C38922_100%)]" />
      <div className="absolute inset-0 flex flex-col justify-between p-10">
        <p className="relative text-sm font-medium text-primary-foreground/80">
          {t("auth.tagline")}
        </p>
        <div className="relative max-w-md space-y-4">
          <h2 className="text-3xl font-semibold tracking-tight">
            {t("auth.headline")}
          </h2>
          <p className="text-sm leading-relaxed text-primary-foreground/85">
            {t("auth.description")}
          </p>
        </div>
        <p className="relative text-xs text-primary-foreground/70">
          {t("auth.footer")}
        </p>
      </div>
    </div>
  );
}