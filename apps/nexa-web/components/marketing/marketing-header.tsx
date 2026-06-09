"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { ArrowRight, Menu } from "lucide-react";
import { useEffect, useState } from "react";

import { BrandLogo } from "@/components/brand/brand-logo";
import { PreferencesToolbar } from "@/components/layout/preferences-toolbar";
import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { NavLink } from "@/components/ui/nav-link";
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet";
import { brand } from "@/lib/brand";
import { marketingPrimaryNav } from "@/lib/marketing-nav";
import { cn } from "@/lib/utils";

function resolveNavHref(pathname: string, href: string) {
  if (href.startsWith("/#") && pathname !== "/") {
    return `/${href}`;
  }

  return href;
}

function isNavActive(pathname: string, href: string) {
  if (href.startsWith("/#")) {
    return pathname === "/";
  }

  return pathname === href || pathname.startsWith(`${href}/`);
}

export function MarketingHeader() {
  const { t } = useLocale();
  const pathname = usePathname();
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    function onScroll() {
      setScrolled(window.scrollY > 8);
    }

    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <header
      className={cn(
        "sticky top-0 z-50 border-b transition-shadow duration-200",
        scrolled
          ? "border-border/80 bg-background/90 shadow-sm backdrop-blur-md"
          : "border-transparent bg-background/70 backdrop-blur-md",
      )}
    >
      <div className="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-6">
        <Link
          href="/"
          className="flex items-center gap-2.5 font-semibold tracking-tight"
        >
          <div className="flex size-9 items-center justify-center rounded-lg bg-primary p-2 shadow-sm">
            <BrandLogo variant="on-primary" />
          </div>
          {brand.name}
        </Link>

        <nav className="hidden items-center gap-1 lg:flex">
          {marketingPrimaryNav.map((link) => {
            const active = isNavActive(pathname, link.href);
            return (
              <Link
                key={link.href}
                href={resolveNavHref(pathname, link.href)}
                className={cn(
                  "rounded-lg px-3 py-2 text-sm font-medium transition-colors",
                  active
                    ? "bg-accent text-foreground"
                    : "text-muted-foreground hover:bg-muted/60 hover:text-foreground",
                )}
              >
                {t(link.labelKey)}
              </Link>
            );
          })}
        </nav>

        <div className="flex items-center gap-2">
          <div className="hidden sm:flex">
            <PreferencesToolbar />
          </div>
          <NavLink
            href="/login"
            variant="ghost"
            size="sm"
            className="hidden sm:inline-flex"
          >
            {t("marketing.nav.signIn")}
          </NavLink>
          <NavLink
            href="/login"
            size="sm"
            className="hidden bg-brand-accent text-primary hover:bg-brand-accent/90 md:inline-flex"
          >
            {t("marketing.nav.getStarted")}
            <ArrowRight />
          </NavLink>

          <Sheet open={open} onOpenChange={setOpen}>
            <SheetTrigger
              render={
                <Button
                  variant="outline"
                  size="icon-sm"
                  className="lg:hidden"
                  aria-label={t("marketing.nav.openMenu")}
                />
              }
            >
              <Menu />
            </SheetTrigger>
            <SheetContent side="right" className="w-80">
              <SheetHeader>
                <SheetTitle className="flex items-center gap-2">
                  <div className="flex size-8 items-center justify-center rounded-md bg-primary p-1.5">
                    <BrandLogo variant="on-primary" />
                  </div>
                  {brand.name}
                </SheetTitle>
              </SheetHeader>
              <nav className="mt-6 flex flex-col gap-1">
                {marketingPrimaryNav.map((link) => {
                  const active = isNavActive(pathname, link.href);
                  return (
                    <SheetClose
                      key={link.href}
                      render={
                        <Link
                          href={resolveNavHref(pathname, link.href)}
                          className={cn(
                            "rounded-lg px-3 py-2.5 text-sm font-medium",
                            active ? "bg-accent" : "hover:bg-muted",
                          )}
                        />
                      }
                    >
                      {t(link.labelKey)}
                    </SheetClose>
                  );
                })}
              </nav>
              <div className="mt-6 flex flex-col gap-2 border-t pt-6">
                <PreferencesToolbar />
                <SheetClose
                  render={
                    <NavLink href="/login" variant="outline" className="w-full" />
                  }
                >
                  {t("marketing.nav.signIn")}
                </SheetClose>
                <SheetClose
                  render={
                    <NavLink
                      href="/login"
                      className="w-full bg-brand-accent text-primary hover:bg-brand-accent/90"
                    />
                  }
                >
                  {t("marketing.nav.getStarted")}
                </SheetClose>
              </div>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </header>
  );
}
