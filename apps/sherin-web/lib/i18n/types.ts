export type Locale = "en" | "fr" | "ar";

export const locales: Locale[] = ["en", "fr", "ar"];

export const localeLabels: Record<Locale, string> = {
  en: "EN",
  fr: "FR",
  ar: "AR",
};

export const localeDirections: Record<Locale, "ltr" | "rtl"> = {
  en: "ltr",
  fr: "ltr",
  ar: "rtl",
};

export const LOCALE_COOKIE = "sherin-locale";
