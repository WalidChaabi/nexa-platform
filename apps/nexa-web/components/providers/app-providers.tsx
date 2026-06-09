"use client";

import { ConfirmProvider } from "@/components/feedback/confirm-provider";
import { LocaleProvider } from "@/components/providers/locale-provider";
import { ThemeProvider } from "@/components/providers/theme-provider";
import { Toaster } from "@/components/ui/sonner";

export function AppProviders({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <ThemeProvider>
      <LocaleProvider>
        <ConfirmProvider>
          {children}
          <Toaster richColors closeButton />
        </ConfirmProvider>
      </LocaleProvider>
    </ThemeProvider>
  );
}
