"use client";

import {
  ArrowRight,
  BarChart3,
  Building2,
  CreditCard,
  Megaphone,
  Receipt,
  Shield,
  Sparkles,
  Store,
  TrendingUp,
  UserCheck,
  Users,
  Zap,
} from "lucide-react";

import { MarketingShell } from "@/components/marketing/marketing-shell";
import { TestimonialsSection } from "@/components/marketing/marketing-pages";
import {
  MarketingFeatureCard,
  MarketingGridBg,
  MarketingSectionHeader,
  marketingAccentButtonClass,
  marketingHeroOutlineClass,
} from "@/components/marketing/marketing-ui";
import { useLocale } from "@/components/providers/locale-provider";
import { Badge } from "@/components/ui/badge";
import { Card, CardContent } from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";
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

function HeroPreview() {
  const { t } = useLocale();

  const kpis = [
    { label: t("marketing.hero.preview.revenue"), value: "€24.8k", trend: "+12%" },
    { label: t("marketing.hero.preview.invoices"), value: "186", trend: "+8%" },
    { label: t("marketing.hero.preview.customers"), value: "1,042", trend: "+5%" },
  ];

  return (
    <div className="relative mx-auto w-full max-w-lg lg:max-w-none">
      <div className="absolute -inset-6 rounded-[2rem] bg-brand-accent/15 blur-3xl" />
      <Card className="relative overflow-hidden border-white/10 bg-card/95 shadow-2xl ring-1 ring-white/10 backdrop-blur-sm">
        <div className="flex items-center justify-between border-b border-border/60 bg-muted/50 px-4 py-3">
          <div className="flex items-center gap-2">
            <div className="size-2.5 rounded-full bg-brand-accent" />
            <div className="size-2.5 rounded-full bg-brand-warning/80" />
            <div className="size-2.5 rounded-full bg-muted-foreground/30" />
          </div>
          <span className="text-xs font-medium text-muted-foreground">
            {t("marketing.hero.preview.workspace")}
          </span>
          <Badge variant="secondary" className="text-[10px]">
            {t("marketing.hero.preview.live")}
          </Badge>
        </div>
        <CardContent className="space-y-4 p-5">
          <div className="grid gap-3 sm:grid-cols-3">
            {kpis.map((kpi) => (
              <div
                key={kpi.label}
                className="rounded-xl border border-border/60 bg-background/90 p-3"
              >
                <p className="text-[11px] text-muted-foreground">{kpi.label}</p>
                <p className="mt-1 text-lg font-semibold tabular-nums">{kpi.value}</p>
                <p className="mt-0.5 flex items-center gap-1 text-xs font-medium text-brand-accent">
                  <TrendingUp className="size-3" />
                  {kpi.trend}
                </p>
              </div>
            ))}
          </div>
          <div className="rounded-xl border border-border/60 bg-background/90 p-4">
            <div className="mb-3 flex items-center justify-between">
              <p className="text-sm font-medium">
                {t("marketing.hero.preview.chartTitle")}
              </p>
              <Badge variant="outline" className="text-[10px]">
                {t("marketing.hero.preview.chartBadge")}
              </Badge>
            </div>
            <div className="flex h-28 items-end gap-1.5">
              {[40, 65, 45, 80, 55, 90, 70, 85, 60].map((height, index) => (
                <div
                  key={index}
                  className="flex-1 rounded-t-sm bg-linear-to-t from-brand-accent/50 to-brand-accent"
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
  const pillars = [1, 2, 3] as const;

  return (
    <section className="relative overflow-hidden bg-primary text-primary-foreground">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#00C38933,transparent_55%)]" />
      <div className="absolute inset-0 bg-[linear-gradient(160deg,transparent_40%,#00C38922_100%)]" />
      <MarketingGridBg className="opacity-15 mask-none" />
      <div className="relative mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-2 lg:items-center lg:py-28">
        <div className="space-y-8">
          <Badge className="gap-1.5 border-white/15 bg-white/10 text-primary-foreground hover:bg-white/10">
            <Sparkles className="size-3" />
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
          <div className="flex flex-wrap gap-2">
            {pillars.map((pillar) => (
              <span
                key={pillar}
                className="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-medium text-primary-foreground/90"
              >
                {t(`marketing.hero.pillars.${pillar}`)}
              </span>
            ))}
          </div>
          <div className="flex flex-col gap-3 sm:flex-row">
            <NavLink
              href="/login"
              size="lg"
              className={cn("h-11 px-6", marketingAccentButtonClass)}
            >
              {t("marketing.hero.ctaPrimary")}
              <ArrowRight />
            </NavLink>
            <NavLink
              href="/pricing"
              variant="outline"
              size="lg"
              className={cn("h-11 px-6", marketingHeroOutlineClass)}
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
    { value: t("marketing.trust.multiStore"), label: t("marketing.trust.multiStoreLabel"), icon: Store },
    { value: t("marketing.trust.realTime"), label: t("marketing.trust.realTimeLabel"), icon: Zap },
    { value: t("marketing.trust.roles"), label: t("marketing.trust.rolesLabel"), icon: Users },
  ];

  return (
    <section className="border-b bg-muted/30">
      <div className="mx-auto grid max-w-6xl gap-6 px-6 py-12 sm:grid-cols-3">
        {stats.map((stat) => {
          const Icon = stat.icon;
          return (
            <div
              key={stat.label}
              className="flex gap-4 rounded-2xl border bg-card/60 p-5 sm:flex-col sm:items-start sm:text-start"
            >
              <div className="flex size-10 shrink-0 items-center justify-center rounded-xl bg-accent">
                <Icon className="size-5 text-brand-accent" />
              </div>
              <div>
                <p className="text-xl font-semibold tracking-tight">{stat.value}</p>
                <p className="mt-1 text-sm leading-relaxed text-muted-foreground">
                  {stat.label}
                </p>
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
}

function HowItWorks() {
  const { t } = useLocale();
  const steps = [1, 2, 3] as const;

  return (
    <section className="border-b bg-background py-20 md:py-24">
      <div className="mx-auto max-w-6xl px-6">
        <MarketingSectionHeader
          badge={t("marketing.howItWorks.badge")}
          title={t("marketing.howItWorks.title")}
          description={t("marketing.howItWorks.subtitle")}
        />
        <div className="relative mt-14 grid gap-8 md:grid-cols-3">
          <div className="absolute top-8 right-[16%] left-[16%] hidden h-px bg-border md:block" />
          {steps.map((step) => (
            <div key={step} className="relative text-center">
              <div className="mx-auto flex size-12 items-center justify-center rounded-full border-2 border-brand-accent bg-background text-sm font-bold text-brand-accent">
                {step}
              </div>
              <h3 className="mt-5 text-lg font-semibold">
                {t(`marketing.howItWorks.steps.${step}.title`)}
              </h3>
              <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
                {t(`marketing.howItWorks.steps.${step}.description`)}
              </p>
            </div>
          ))}
        </div>
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
    <section id="features" className="relative scroll-mt-20 py-20 md:py-28">
      <MarketingGridBg />
      <div className="relative mx-auto max-w-6xl px-6">
        <MarketingSectionHeader
          badge={t("marketing.features.badge")}
          title={t("marketing.features.title")}
          description={t("marketing.features.subtitle")}
        />
        <div className="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {features.map((feature, index) => {
            const Icon = featureIcons[feature.key];
            return (
              <MarketingFeatureCard
                key={feature.key}
                icon={Icon}
                title={t(feature.titleKey)}
                description={t(feature.descKey)}
                index={index + 1}
              />
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
    <section id="roles" className="scroll-mt-20 bg-muted/30 py-20 md:py-28">
      <div className="mx-auto max-w-6xl px-6">
        <MarketingSectionHeader
          badge={t("marketing.roles.badge")}
          title={t("marketing.roles.title")}
          description={t("marketing.roles.subtitle")}
        />
        <div className="mt-14 grid gap-6 lg:grid-cols-3">
          {roles.map((role, index) => {
            const Icon = roleIcons[role.key];
            return (
              <div
                key={role.key}
                className="relative overflow-hidden rounded-2xl border bg-card p-6 transition-shadow hover:shadow-md"
              >
                <div className="absolute top-4 end-4 text-4xl font-bold text-muted/40">
                  {index + 1}
                </div>
                <div className="flex size-11 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                  <Icon className="size-5" />
                </div>
                <h3 className="mt-5 text-lg font-semibold">{t(role.titleKey)}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
                  {t(role.descKey)}
                </p>
              </div>
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
        <div className="relative overflow-hidden rounded-3xl border-0 bg-primary text-primary-foreground">
          <div className="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,#00C38933,transparent_60%)]" />
          <div className="relative px-8 py-14 text-center md:px-12 md:py-16">
            <h2 className="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
              {t("marketing.cta.title")}
            </h2>
            <p className="mx-auto mt-4 max-w-2xl text-primary-foreground/85">
              {t("marketing.cta.description")}
            </p>
            <div className="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
              <NavLink
                href="/login"
                size="lg"
                className={cn("h-11 px-6", marketingAccentButtonClass)}
              >
                {t("marketing.cta.button")}
                <ArrowRight />
              </NavLink>
              <NavLink
                href="/pricing"
                variant="outline"
                size="lg"
                className={cn("h-11 px-6", marketingHeroOutlineClass)}
              >
                {t("marketing.cta.pricing")}
              </NavLink>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

export function MarketingSite() {
  return (
    <MarketingShell>
      <MarketingHero />
      <TrustStrip />
      <HowItWorks />
      <MarketingFeatures />
      <MarketingRoles />
      <TestimonialsSection />
      <MarketingCta />
    </MarketingShell>
  );
}
