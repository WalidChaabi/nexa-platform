import { ar, enUS, fr } from "date-fns/locale";

import type { Locale } from "@/lib/i18n/types";

const locales = {
  en: enUS,
  fr,
  ar,
} as const;

export function getDateFnsLocale(locale: Locale) {
  return locales[locale] ?? enUS;
}
