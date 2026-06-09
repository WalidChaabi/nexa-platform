"use client";

import Link from "next/link";
import {
  ArrowRight,
  BarChart3,
  Building2,
  CreditCard,
  Megaphone,
  Menu,
  Receipt,
  Shield,
  Store,
  TrendingUp,
  UserCheck,
  Users,
} from "lucide-react";
import { useState } from "react";

import { BrandLogo } from "@/components/brand/brand-logo";
import { PreferencesToolbar } from "@/components/layout/preferences-toolbar";
import { useLocale } from "@/components/providers/locale-provider";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
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
import { cn } from "@/lib/utils";

const featureIcons = {
  pos: Receipt,
  crm: Users,
  campaigns: Megaphone,
  payments: CreditCard,
  stores: Store,
  analytics: BarChart3,
} as const;

const roleIcons = {
  admin: Shield,
  owner: Building2,
  employer: UserCheck,
} as const;

function MarketingHeader() {
  const { t } = useLocale();
  const [open, setOpen] = useState(false);

  const navLinks = [
    { href: "#features", label: t("marketing.nav.features") },
    { href: "#roles", label: t("marketing.nav.roles") },
  ];

  return (
    <header className="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur-md">
      <div className="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-6">
        <Link
          href="/"
          className="flex items-center gap-2.5 font-semibold tracking-tight"
        >
          <div className="flex size-9 items-center justify-center rounded-lg bg-primary p-2">
            <BrandLogo variant="on-primary" />
          </div>
          {brand.name}
        </Link>

        <nav className="hidden items-center gap-8 md:flex">
          {navLinks.map((link) => (
            <a
              key={link.href}
              href={link.href}
              className="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
            >
              {link.label}
            </a>
          ))}
        </nav>

        <div className="flex items-center gap-2">
          <div className="hidden sm:flex">
            <PreferencesToolbar />
          </div>
          <NavLink
            href="/login"
            variant="outline"
            size="sm"
            className="hidden sm:inline-flex"
          >
            {t("marketing.nav.signIn")}
          </NavLink>
          <NavLink href="/login" size="sm" className="hidden md:inline-flex">
            {t("marketing.nav.getStarted")}
            <ArrowRight />
          </NavLink>

          <Sheet open={open} onOpenChange={setOpen}>
            <SheetTrigger
              render={
                <Button
                  variant="outline"
                  size="icon-sm"
                  className="md:hidden"
                  aria-label={t("marketing.nav.openMenu")}
                />
              }
            >
              <Menu />
            </SheetTrigger>
            <SheetContent side="right" className="w-72">
              <SheetHeader>
                <SheetTitle className="flex items-center gap-2">
                  <div className="flex size-8 items-center justify-center rounded-md bg-primary p-1.5">
                    <BrandLogo variant="on-primary" />
                  </div>
                  {brand.name}
                </SheetTitle>
              </SheetHeader>
              <nav className="mt-6 flex flex-col gap-1">
                {navLinks.map((link) => (
                  <SheetClose
                    key={link.href}
                    render={
                      <a
                        href={link.href}
                        className="rounded-lg px-3 py-2 text-sm font-medium hover:bg-muted"
                      />
                    }
                  >
                    {link.label}
                  </SheetClose>
                ))}
              </nav>
              <div className="mt-6 flex flex-col gap-2 border-t pt-6">
                <PreferencesToolbar />
                <SheetClose
                  render={
                    <NavLink
                      href="/login"
                      variant="outline"
                      className="w-full"
                    >
                      {t("marketing.nav.signIn")}
                    </NavLink>
                  }
                />
                <SheetClose
                  render={
                    <NavLink href="/login" className="w-full">
                      {t("marketing.nav.getStarted")}
                    </NavLink>
                  }
                />
              </div>
            </SheetContent>
          </Sheet>
        </div>
      </div>
    </header>
  );
}

function HeroPreview() {
  const { t } = useLocale();

  const kpis = [
    { label: t("marketing.hero.preview.revenue"), value: "€24.8k", trend: "+12%" },
    { label: t("marketing.hero.preview.invoices"), value: "186", trend: "+8%" },
    { label: t("marketing.hero.preview.customers"), value: "1,042", trend: "+5%" },
  ];

  return (
    <div className="relative mx-auto w-full max-w-lg lg:max-w-none">
      <div className="absolute -inset-4 rounded-3xl bg-brand-accent/20 blur-3xl" />
      <Card className="relative overflow-hidden border-white/10 bg-card/95 shadow-2xl ring-1 ring-foreground/10 backdrop-blur">
        <div className="border-b bg-muted/40 px-4 py-3">
          <div className="flex items-center gap-2">
            <div className="size-2.5 rounded-full bg-brand-accent" />
            <div className="size-2.5 rounded-full bg-brand-warning/80" />
            <div className="size-2.5 rounded-full bg-muted-foreground/30" />
            <span className="ms-2 text-xs text-muted-foreground">
              {t("marketing.hero.preview.workspace")}
            </span>
          </div>
        </div>
        <CardContent className="space-y-4 p-5">
          <div className="grid gap-3 sm:grid-cols-3">
            {kpis.map((kpi) => (
              <div
                key={kpi.label}
                className="rounded-xl border bg-background/80 p-3"
              >
                <p className="text-xs text-muted-foreground">{kpi.label}</p>
                <p className="mt-1 text-lg font-semibold tabular-nums">
                  {kpi.value}
                </p>
                <p className="mt-0.5 flex items-center gap-1 text-xs font-medium text-brand-accent">
                  <TrendingUp className="size-3" />
                  {kpi.trend}
                </p>
              </div>
            ))}
          </div>
          <div className="rounded-xl border bg-background/80 p-4">
            <div className="mb-3 flex items-center justify-between">
              <p className="text-sm font-medium">
                {t("marketing.hero.preview.chartTitle")}
              </p>
              <Badge variant="secondary">
                {t("marketing.hero.preview.chartBadge")}
              </Badge>
            </div>
            <div className="flex h-24 items-end gap-2">
              {[40, 65, 45, 80, 55, 90, 70].map((height, index) => (
                <div
                  key={index}
                  className="flex-1 rounded-t-md bg-brand-accent/80"
                  style={{ height: `${height}%` }}
                />
              ))}
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}

function MarketingHero() {
  const { t } = useLocale();

  return (
    <section className="relative overflow-hidden bg-primary text-primary-foreground">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#00C38933,transparent_55%)]" />
      <div className="absolute inset-0 bg-[linear-gradient(160deg,transparent_40%,#00C38922_100%)]" />
      <div className="relative mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-2 lg:items-center lg:py-28">
        <div className="space-y-8">
          <Badge className="border-white/15 bg-white/10 text-primary-foreground hover:bg-white/10">
            {t("marketing.hero.badge")}
          </Badge>
          <div className="space-y-4">
            <h1 className="text-4xl font-semibold tracking-tight text-balance sm:text-5xl lg:text-6xl">
              {t("marketing.hero.headline")}
            </h1>
            <p className="max-w-xl text-lg leading-relaxed text-primary-foreground/85">
              {t("marketing.hero.description")}
            </p>
          </div>
          <div className="flex flex-col gap-3 sm:flex-row">
            <NavLink
              href="/login"
              size="lg"
              className="h-10 bg-brand-accent px-5 text-primary hover:bg-brand-accent/90"
            >
              {t("marketing.hero.ctaPrimary")}
              <ArrowRight />
            </NavLink>
            <NavLink
              href="#features"
              variant="outline"
              size="lg"
              className="h-10 border-white/20 bg-white/5 text-primary-foreground hover:bg-white/10 hover:text-primary-foreground"
            >
              {t("marketing.hero.ctaSecondary")}
            </NavLink>
          </div>
          <p className="text-sm text-primary-foreground/70">
            {t("marketing.hero.note")}
          </p>
        </div>
        <HeroPreview />
      </div>
    </section>
  );
}

function TrustStrip() {
  const { t } = useLocale();

  const stats = [
    { value: t("marketing.trust.multiStore"), label: t("marketing.trust.multiStoreLabel") },
    { value: t("marketing.trust.realTime"), label: t("marketing.trust.realTimeLabel") },
    { value: t("marketing.trust.roles"), label: t("marketing.trust.rolesLabel") },
  ];

  return (
    <section className="border-b bg-muted/30">
      <div className="mx-auto grid max-w-6xl gap-8 px-6 py-12 sm:grid-cols-3">
        {stats.map((stat) => (
          <div key={stat.label} className="text-center sm:text-start">
            <p className="text-2xl font-semibold tracking-tight">{stat.value}</p>
            <p className="mt-1 text-sm text-muted-foreground">{stat.label}</p>
          </div>
        ))}
      </div>
    </section>
  );
}

function MarketingFeatures() {
  const { t } = useLocale();

  const features = [
    { key: "pos" as const, titleKey: "marketing.features.pos.title", descKey: "marketing.features.pos.description" },
    { key: "crm" as const, titleKey: "marketing.features.crm.title", descKey: "marketing.features.crm.description" },
    { key: "campaigns" as const, titleKey: "marketing.features.campaigns.title", descKey: "marketing.features.campaigns.description" },
    { key: "payments" as const, titleKey: "marketing.features.payments.title", descKey: "marketing.features.payments.description" },
    { key: "stores" as const, titleKey: "marketing.features.stores.title", descKey: "marketing.features.stores.description" },
    { key: "analytics" as const, titleKey: "marketing.features.analytics.title", descKey: "marketing.features.analytics.description" },
  ];

  return (
    <section id="features" className="scroll-mt-20 py-20 md:py-28">
      <div className="mx-auto max-w-6xl px-6">
        <div className="mx-auto max-w-2xl text-center">
          <Badge variant="secondary" className="mb-4">
            {t("marketing.features.badge")}
          </Badge>
          <h2 className="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
            {t("marketing.features.title")}
          </h2>
          <p className="mt-4 text-muted-foreground">
            {t("marketing.features.subtitle")}
          </p>
        </div>
        <div className="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {features.map((feature) => {
            const Icon = featureIcons[feature.key];
            return (
              <Card
                key={feature.key}
                className="transition-shadow hover:shadow-md"
              >
                <CardHeader>
                  <div className="mb-2 flex size-10 items-center justify-center rounded-lg bg-accent">
                    <Icon className="size-5 text-brand-accent" />
                  </div>
                  <CardTitle className="text-lg">{t(feature.titleKey)}</CardTitle>
                  <CardDescription className="leading-relaxed">
                    {t(feature.descKey)}
                  </CardDescription>
                </CardHeader>
              </Card>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function MarketingRoles() {
  const { t } = useLocale();

  const roles = [
    { key: "admin" as const, titleKey: "marketing.roles.admin.title", descKey: "marketing.roles.admin.description" },
    { key: "owner" as const, titleKey: "marketing.roles.owner.title", descKey: "marketing.roles.owner.description" },
    { key: "employer" as const, titleKey: "marketing.roles.employer.title", descKey: "marketing.roles.employer.description" },
  ];

  return (
    <section
      id="roles"
      className="scroll-mt-20 border-y bg-muted/30 py-20 md:py-28"
    >
      <div className="mx-auto max-w-6xl px-6">
        <div className="mx-auto max-w-2xl text-center">
          <Badge variant="secondary" className="mb-4">
            {t("marketing.roles.badge")}
          </Badge>
          <h2 className="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
            {t("marketing.roles.title")}
          </h2>
          <p className="mt-4 text-muted-foreground">
            {t("marketing.roles.subtitle")}
          </p>
        </div>
        <div className="mt-14 grid gap-6 lg:grid-cols-3">
          {roles.map((role) => {
            const Icon = roleIcons[role.key];
            return (
              <Card key={role.key} className="border-border/80 bg-card">
                <CardHeader>
                  <div className="mb-2 flex size-10 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                    <Icon className="size-5" />
                  </div>
                  <CardTitle className="text-lg">{t(role.titleKey)}</CardTitle>
                  <CardDescription className="leading-relaxed">
                    {t(role.descKey)}
                  </CardDescription>
                </CardHeader>
              </Card>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function MarketingCta() {
  const { t } = useLocale();

  return (
    <section className="py-20 md:py-28">
      <div className="mx-auto max-w-6xl px-6">
        <Card className="overflow-hidden border-0 bg-primary text-primary-foreground ring-0">
          <CardContent className="relative px-8 py-12 md:px-12 md:py-16">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,#00C38933,transparent_60%)]" />
            <div className="relative mx-auto max-w-2xl text-center">
              <h2 className="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
                {t("marketing.cta.title")}
              </h2>
              <p className="mt-4 text-primary-foreground/85">
                {t("marketing.cta.description")}
              </p>
              <NavLink
                href="/login"
                size="lg"
                className={cn(
                  "mt-8 h-10 bg-brand-accent px-6 text-primary hover:bg-brand-accent/90",
                )}
              >
                {t("marketing.cta.button")}
                <ArrowRight />
              </NavLink>
            </div>
          </CardContent>
        </Card>
      </div>
    </section>
  );
}

function MarketingFooter() {
  const { t } = useLocale();
  const year = new Date().getFullYear();

  return (
    <footer className="border-t py-10">
      <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 sm:flex-row">
        <div className="flex items-center gap-2.5">
          <div className="flex size-8 items-center justify-center rounded-md bg-primary p-1.5">
            <BrandLogo variant="on-primary" />
          </div>
          <div>
            <p className="font-medium">{brand.name}</p>
            <p className="text-xs text-muted-foreground">
              {t("marketing.footer.tagline")}
            </p>
          </div>
        </div>
        <p className="text-sm text-muted-foreground">
          {t("marketing.footer.copyright", { year })}
        </p>
      </div>
    </footer>
  );
}

export function MarketingSite() {
  return (
    <div className="flex min-h-svh flex-col">
      <MarketingHeader />
      <main className="flex-1">
        <MarketingHero />
        <TrustStrip />
        <MarketingFeatures />
        <MarketingRoles />
        <MarketingCta />
      </main>
      <MarketingFooter />
    </div>
  );
}
