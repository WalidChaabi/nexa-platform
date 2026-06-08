"use client";

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
} from "react";

import {
  formatCurrency,
  formatDateTime,
  isLocale,
  translate,
  type TranslationParams,
} from "@/lib/i18n";
import {
  LOCALE_COOKIE,
  localeDirections,
  type Locale,
} from "@/lib/i18n/types";

type LocaleContextValue = {
  locale: Locale;
  setLocale: (locale: Locale) => void;
  t: (key: string, params?: TranslationParams) => string;
  formatCurrency: (value: number, currency?: string) => string;
  formatDateTime: (value: string) => string;
};

const LocaleContext = createContext<LocaleContextValue | null>(null);

function readStoredLocale(): Locale {
  if (typeof window === "undefined") {
    return "en";
  }

  const stored = window.localStorage.getItem(LOCALE_COOKIE);
  if (isLocale(stored)) {
    return stored;
  }

  const browser = window.navigator.language.toLowerCase();
  if (browser.startsWith("ar")) {
    return "ar";
  }

  if (browser.startsWith("fr")) {
    return "fr";
  }

  return "en";
}

function applyDocumentLocale(locale: Locale) {
  document.documentElement.lang = locale;
  document.documentElement.dir = localeDirections[locale];
}

function persistLocale(locale: Locale) {
  window.localStorage.setItem(LOCALE_COOKIE, locale);
  document.cookie = `${LOCALE_COOKIE}=${locale}; path=/; max-age=31536000; SameSite=Lax`;
  applyDocumentLocale(locale);
}

export function LocaleProvider({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const [locale, setLocaleState] = useState<Locale>("en");
  const [ready, setReady] = useState(false);

  useEffect(() => {
    const initial = readStoredLocale();
    setLocaleState(initial);
    applyDocumentLocale(initial);
    setReady(true);
  }, []);

  const setLocale = useCallback((next: Locale) => {
    setLocaleState(next);
    persistLocale(next);
  }, []);

  const value = useMemo<LocaleContextValue>(
    () => ({
      locale,
      setLocale,
      t: (key, params) => translate(locale, key, params),
      formatCurrency: (amount, currency) =>
        formatCurrency(locale, amount, currency),
      formatDateTime: (value) => formatDateTime(locale, value),
    }),
    [locale, setLocale],
  );

  if (!ready) {
    return (
      <LocaleContext.Provider
        value={{
          locale: "en",
          setLocale: () => undefined,
          t: (key, params) => translate("en", key, params),
          formatCurrency: (amount, currency) =>
            formatCurrency("en", amount, currency),
          formatDateTime: (value) => formatDateTime("en", value),
        }}
      >
        {children}
      </LocaleContext.Provider>
    );
  }

  return (
    <LocaleContext.Provider value={value}>{children}</LocaleContext.Provider>
  );
}

export function useLocale() {
  const context = useContext(LocaleContext);

  if (!context) {
    throw new Error("useLocale must be used within LocaleProvider.");
  }

  return context;
}
