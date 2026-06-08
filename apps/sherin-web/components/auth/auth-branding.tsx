"use client";

import { Sparkles } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";

export function AuthBranding() {
  const { t } = useLocale();

  return (
    <div className="relative hidden flex-col justify-between overflow-hidden bg-primary p-10 text-primary-foreground lg:flex">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,oklch(1_0_0/0.18),transparent_55%)]" />
      <div className="relative flex items-center gap-3">
        <div className="flex size-10 items-center justify-center rounded-xl bg-primary-foreground/15">
          <Sparkles className="size-5" />
        </div>
        <div>
          <p className="text-lg font-semibold">{t("common.appName")}</p>
          <p className="text-sm text-primary-foreground/80">{t("auth.tagline")}</p>
        </div>
      </div>
      <div className="relative space-y-4">
        <h2 className="text-3xl font-semibold tracking-tight">
          {t("auth.headline")}
        </h2>
        <p className="max-w-md text-sm leading-relaxed text-primary-foreground/85">
          {t("auth.description")}
        </p>
      </div>
      <p className="relative text-xs text-primary-foreground/70">
        {t("auth.footer")}
      </p>
    </div>
  );
}
