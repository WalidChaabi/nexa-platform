"use client";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import { localeLabels, locales, type Locale } from "@/lib/i18n/types";

export function LocaleToggle() {
  const { locale, setLocale, t } = useLocale();

  return (
    <div
      className="inline-flex items-center rounded-lg border p-0.5"
      role="group"
      aria-label={t("common.language")}
    >
      {locales.map((item) => (
        <Button
          key={item}
          type="button"
          variant={locale === item ? "secondary" : "ghost"}
          size="sm"
          className={cn(
            "h-7 min-w-9 px-2",
            locale === item && "shadow-sm",
          )}
          aria-pressed={locale === item}
          onClick={() => setLocale(item as Locale)}
        >
          {localeLabels[item]}
        </Button>
      ))}
    </div>
  );
}
