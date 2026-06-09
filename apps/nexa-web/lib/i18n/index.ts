import type { Locale } from "./types";
import { ar } from "./messages/ar";
import { en } from "./messages/en";
import { fr } from "./messages/fr";
import type { Messages } from "./messages/en";

const catalogs: Record<Locale, Messages> = {
  en,
  fr: fr as Messages,
  ar: ar as Messages,
};

export type TranslationParams = Record<string, string | number>;

function resolvePath(messages: Messages, key: string): string | undefined {
  const parts = key.split(".");
  let current: unknown = messages;

  for (const part of parts) {
    if (current == null || typeof current !== "object") {
      return undefined;
    }

    current = (current as Record<string, unknown>)[part];
  }

  return typeof current === "string" ? current : undefined;
}

export function translate(
  locale: Locale,
  key: string,
  params?: TranslationParams,
): string {
  const template =
    resolvePath(catalogs[locale], key) ?? resolvePath(catalogs.en, key) ?? key;

  if (!params) {
    return template;
  }

  return Object.entries(params).reduce(
    (result, [name, value]) =>
      result.replaceAll(`{${name}}`, String(value)),
    template,
  );
}

export function getDateLocale(locale: Locale): string {
  if (locale === "ar") {
    return "ar-MA";
  }

  if (locale === "fr") {
    return "fr-MA";
  }

  return "en-GB";
}

export function formatCurrency(
  locale: Locale,
  value: number,
  currency = "MAD",
): string {
  return new Intl.NumberFormat(getDateLocale(locale), {
    style: "currency",
    currency,
  }).format(value);
}

export function formatDateTime(locale: Locale, value: string): string {
  return new Intl.DateTimeFormat(getDateLocale(locale), {
    dateStyle: "medium",
    timeStyle: "short",
  }).format(new Date(value));
}

export function isLocale(value: string | null | undefined): value is Locale {
  return value === "en" || value === "fr" || value === "ar";
}
