"use client";

import { LocaleToggle } from "@/components/layout/locale-toggle";
import { ThemeToggle } from "@/components/layout/theme-toggle";

export function PreferencesToolbar() {
  return (
    <div className="flex items-center gap-2">
      <LocaleToggle />
      <ThemeToggle />
    </div>
  );
}
