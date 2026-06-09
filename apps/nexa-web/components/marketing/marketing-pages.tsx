"use client";

import Link from "next/link";
import {
  ArrowRight,
  BookOpen,
  Calendar,
  Check,
  CreditCard,
  Minus,
  Quote,
  Receipt,
  Shield,
  Star,
  Users,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";

import { MarketingPageHero } from "@/components/marketing/marketing-page-hero";
import { MarketingShell } from "@/components/marketing/marketing-shell";
import {
  MarketingCounselBanner,
  MarketingDocsSidebar,
  MarketingGridBg,
  MarketingInlineCta,
  MarketingLegalToc,
  MarketingPlaceholderBanner,
  MarketingSalesBanner,
  MarketingSectionHeader,
  marketingAccentButtonClass,
} from "@/components/marketing/marketing-ui";
import { useLocale } from "@/components/providers/locale-provider";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { brand } from "@/lib/brand";
import { cn } from "@/lib/utils";

const planKeys = ["starter", "growth", "enterprise"] as const;
const compareRows = [
  "pos",
  "crm",
  "campaigns",
  "multiStore",
  "reports",
  "staffRoles",
  "smsBilling",
  "setup",
] as const;

const docsSections = [
  { id: "gettingStarted", icon: BookOpen },
  { id: "roles", icon: Shield },
  { id: "pos", icon: Receipt },
  { id: "campaigns", icon: Users },
  { id: "billing", icon: CreditCard },
] as const;

function getInitials(name: string) {
  return name
    .split(" ")
    .map((part) => part[0])
    .join("")
    .slice(0, 2)
    .toUpperCase();
}

export function PricingPageContent() {
  const { t } = useLocale();

  return (
    <MarketingShell>
      <MarketingPageHero
        variant="dark"
        badge={t("marketing.pricing.badge")}
        title={t("marketing.pricing.title")}
        description={t("marketing.pricing.subtitle")}
        breadcrumbs={[
          { label: brand.name, href: "/" },
          { label: t("marketing.nav.pricing") },
        ]}
      />
      <MarketingSalesBanner />
      <MarketingPlaceholderBanner />
      <section className="pb-12 md:pb-16">
        <div className="mx-auto max-w-6xl px-6">
          <div className="grid gap-6 lg:grid-cols-3 lg:items-stretch">
            {planKeys.map((plan) => (
              <Card
                key={plan}
                className={cn(
                  "relative flex flex-col overflow-hidden transition-shadow hover:shadow-lg",
                  plan === "growth" &&
                    "border-brand-accent shadow-md shadow-brand-accent/10 ring-1 ring-brand-accent/30",
                )}
              >
                {plan === "growth" ? (
                  <div className="absolute inset-x-0 top-0 h-1 bg-brand-accent" />
                ) : null}
                <CardHeader className="pb-4">
                  <div className="flex items-start justify-between gap-2">
                    <CardTitle className="text-xl">
                      {t(`marketing.pricing.plans.${plan}.name`)}
                    </CardTitle>
                    {plan === "growth" ? (
                      <Badge className="bg-brand-accent text-primary">
                        {t("marketing.pricing.popular")}
                      </Badge>
                    ) : null}
                  </div>
                  <CardDescription className="leading-relaxed">
                    {t(`marketing.pricing.plans.${plan}.description`)}
                  </CardDescription>
                  <div className="border-t pt-4">
                    <p className="text-4xl font-semibold tracking-tight">
                      {t(`marketing.pricing.plans.${plan}.price`)}
                    </p>
                    <p className="mt-1 text-sm text-muted-foreground">
                      {t(`marketing.pricing.plans.${plan}.period`)}
                    </p>
                  </div>
                </CardHeader>
                <CardContent className="flex-1 space-y-3 border-t bg-muted/20 py-5">
                  {[1, 2, 3, 4, 5].map((index) => {
                    const feature = t(
                      `marketing.pricing.plans.${plan}.features.${index}`,
                    );
                    if (
                      feature ===
                      `marketing.pricing.plans.${plan}.features.${index}`
                    ) {
                      return null;
                    }
                    return (
                      <div key={index} className="flex items-start gap-2.5 text-sm">
                        <Check className="mt-0.5 size-4 shrink-0 text-brand-accent" />
                        <span>{feature}</span>
                      </div>
                    );
                  })}
                </CardContent>
                <CardFooter className="flex-col gap-2 border-t bg-card pt-5">
                  <NavLink
                    href="mailto:sales@nexa.app"
                    className={cn(
                      "h-10 w-full",
                      plan === "growth" && marketingAccentButtonClass,
                    )}
                    variant={plan === "growth" ? "default" : "outline"}
                  >
                    {t(`marketing.pricing.plans.${plan}.cta`)}
                    <ArrowRight />
                  </NavLink>
                </CardFooter>
              </Card>
            ))}
          </div>
        </div>
      </section>
      <section className="border-t bg-muted/30 py-16 md:py-20">
        <div className="mx-auto max-w-3xl px-6">
          <MarketingSectionHeader
            badge={t("marketing.pricing.faq.badge")}
            title={t("marketing.pricing.faq.title")}
          />
          <div className="mt-10 space-y-4">
            {[1, 2, 3, 4].map((item) => (
              <Card key={item}>
                <CardHeader className="pb-2">
                  <CardTitle className="text-base">
                    {t(`marketing.pricing.faq.items.${item}.q`)}
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <p className="text-sm leading-relaxed text-muted-foreground">
                    {t(`marketing.pricing.faq.items.${item}.a`)}
                  </p>
                </CardContent>
              </Card>
            ))}
          </div>
          <p className="mt-8 text-center text-sm text-muted-foreground">
            {t("marketing.pricing.note")}
          </p>
        </div>
      </section>
    </MarketingShell>
  );
}

export function ComparePageContent() {
  const { t } = useLocale();
  const columns = ["nexa", "spreadsheets", "legacyPos"] as const;

  function cellValue(
    column: (typeof columns)[number],
    row: (typeof compareRows)[number],
  ) {
    const value = t(`marketing.compare.matrix.${row}.${column}`);
    if (value === "yes") {
      return <Check className="mx-auto size-4 text-brand-accent" />;
    }
    if (value === "no") {
      return <Minus className="mx-auto size-4 text-muted-foreground/60" />;
    }
    return <span className="text-sm font-medium">{value}</span>;
  }

  return (
    <MarketingShell>
      <MarketingPageHero
        badge={t("marketing.compare.badge")}
        title={t("marketing.compare.title")}
        description={t("marketing.compare.subtitle")}
        breadcrumbs={[
          { label: brand.name, href: "/" },
          { label: t("marketing.nav.compare") },
        ]}
      />
      <section className="pb-16 md:pb-20">
        <div className="mx-auto max-w-6xl px-6">
          <div className="hidden overflow-hidden rounded-2xl border md:block">
            <Table>
              <TableHeader>
                <TableRow className="bg-muted/40 hover:bg-muted/40">
                  <TableHead className="min-w-48 ps-6">
                    {t("marketing.compare.table.capability")}
                  </TableHead>
                  {columns.map((column) => (
                    <TableHead
                      key={column}
                      className={cn(
                        "min-w-36 text-center",
                        column === "nexa" && "bg-brand-accent/10",
                      )}
                    >
                      <div className="flex flex-col items-center gap-1 py-1">
                        <span>{t(`marketing.compare.table.${column}`)}</span>
                        {column === "nexa" ? (
                          <Badge
                            variant="secondary"
                            className="text-[10px] text-brand-accent"
                          >
                            {t("marketing.compare.recommended")}
                          </Badge>
                        ) : null}
                      </div>
                    </TableHead>
                  ))}
                </TableRow>
              </TableHeader>
              <TableBody>
                {compareRows.map((row, index) => (
                  <TableRow
                    key={row}
                    className={index % 2 === 0 ? "bg-background" : "bg-muted/20"}
                  >
                    <TableCell className="ps-6 font-medium">
                      {t(`marketing.compare.rows.${row}`)}
                    </TableCell>
                    {columns.map((column) => (
                      <TableCell
                        key={column}
                        className={cn(
                          "text-center",
                          column === "nexa" && "bg-brand-accent/5",
                        )}
                      >
                        {cellValue(column, row)}
                      </TableCell>
                    ))}
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
          <div className="space-y-4 md:hidden">
            {compareRows.map((row) => (
              <Card key={row}>
                <CardHeader className="pb-2">
                  <CardTitle className="text-base">
                    {t(`marketing.compare.rows.${row}`)}
                  </CardTitle>
                </CardHeader>
                <CardContent className="grid grid-cols-3 gap-2 text-center text-xs">
                  {columns.map((column) => (
                    <div
                      key={column}
                      className={cn(
                        "rounded-lg border p-3",
                        column === "nexa" && "border-brand-accent/30 bg-accent/40",
                      )}
                    >
                      <p className="mb-2 font-medium text-muted-foreground">
                        {t(`marketing.compare.table.${column}`)}
                      </p>
                      {cellValue(column, row)}
                    </div>
                  ))}
                </CardContent>
              </Card>
            ))}
          </div>
          <p className="mx-auto mt-8 max-w-3xl text-center text-sm text-muted-foreground">
            {t("marketing.compare.note")}
          </p>
          <div className="mt-10">
            <MarketingInlineCta
              title={t("marketing.compare.cta.title")}
              description={t("marketing.compare.cta.description")}
              href="/pricing"
              buttonLabel={t("marketing.compare.cta.button")}
            />
          </div>
        </div>
      </section>
    </MarketingShell>
  );
}

export function ChangelogPageContent() {
  const { t } = useLocale();
  const releases = [1, 2, 3, 4] as const;

  return (
    <MarketingShell>
      <MarketingPageHero
        badge={t("marketing.changelog.badge")}
        title={t("marketing.changelog.title")}
        description={t("marketing.changelog.subtitle")}
        breadcrumbs={[
          { label: brand.name, href: "/" },
          { label: t("marketing.nav.changelog") },
        ]}
      />
      <section className="pb-16 md:pb-20">
        <div className="mx-auto max-w-3xl px-6">
          <div className="relative space-y-0">
            <div className="absolute start-[1.125rem] top-2 bottom-2 hidden w-px bg-border md:block" />
            {releases.map((release) => (
              <div key={release} className="relative pb-10 md:ps-12">
                <div className="absolute start-0 top-1.5 hidden size-[2.25rem] items-center justify-center rounded-full border-2 border-brand-accent bg-background md:flex">
                  <Calendar className="size-4 text-brand-accent" />
                </div>
                <Card>
                  <CardHeader>
                    <div className="flex flex-wrap items-center gap-2">
                      <CardTitle>
                        {t(`marketing.changelog.releases.${release}.version`)}
                      </CardTitle>
                      <Badge variant="secondary">
                        {t(`marketing.changelog.releases.${release}.date`)}
                      </Badge>
                    </div>
                    <CardDescription className="text-base">
                      {t(`marketing.changelog.releases.${release}.summary`)}
                    </CardDescription>
                  </CardHeader>
                  <CardContent>
                    <ul className="space-y-2.5">
                      {[1, 2, 3, 4, 5, 6].map((item) => {
                        const text = t(
                          `marketing.changelog.releases.${release}.items.${item}`,
                        );
                        if (
                          text ===
                          `marketing.changelog.releases.${release}.items.${item}`
                        ) {
                          return null;
                        }
                        return (
                          <li
                            key={item}
                            className="flex gap-2.5 text-sm text-muted-foreground"
                          >
                            <Check className="mt-0.5 size-4 shrink-0 text-brand-accent" />
                            <span>{text}</span>
                          </li>
                        );
                      })}
                    </ul>
                  </CardContent>
                </Card>
              </div>
            ))}
          </div>
        </div>
      </section>
    </MarketingShell>
  );
}

export function DocsPageContent() {
  const { t } = useLocale();

  const sidebarSections = docsSections.map((section) => ({
    id: section.id,
    label: t(`marketing.docs.sections.${section.id}.title`),
  }));

  return (
    <MarketingShell>
      <MarketingPageHero
        badge={t("marketing.docs.badge")}
        title={t("marketing.docs.title")}
        description={t("marketing.docs.subtitle")}
        breadcrumbs={[
          { label: brand.name, href: "/" },
          { label: t("marketing.nav.docs") },
        ]}
      />
      <MarketingPlaceholderBanner />
      <section className="pb-16 md:pb-20">
        <div className="mx-auto grid max-w-6xl gap-10 px-6 lg:grid-cols-[220px_1fr]">
          <MarketingDocsSidebar sections={sidebarSections} />
          <div className="space-y-6">
            {docsSections.map((section, index) => {
              const Icon = section.icon as LucideIcon;
              return (
                <Card
                  key={section.id}
                  id={section.id}
                  className="scroll-mt-24 overflow-hidden"
                >
                  <CardHeader className="border-b bg-muted/30">
                    <div className="flex items-start gap-4">
                      <div className="flex size-10 shrink-0 items-center justify-center rounded-xl bg-accent">
                        <Icon className="size-5 text-brand-accent" />
                      </div>
                      <div>
                        <CardTitle>
                          {t(`marketing.docs.sections.${section.id}.title`)}
                        </CardTitle>
                        <CardDescription className="mt-1">
                          {t(`marketing.docs.sections.${section.id}.description`)}
                        </CardDescription>
                      </div>
                      <span className="ms-auto text-xs text-muted-foreground">
                        {String(index + 1).padStart(2, "0")}
                      </span>
                    </div>
                  </CardHeader>
                  <CardContent className="pt-5">
                    <ul className="space-y-2.5">
                      {[1, 2, 3, 4].map((item) => {
                        const text = t(
                          `marketing.docs.sections.${section.id}.items.${item}`,
                        );
                        if (
                          text ===
                          `marketing.docs.sections.${section.id}.items.${item}`
                        ) {
                          return null;
                        }
                        return (
                          <li
                            key={item}
                            className="flex gap-2.5 text-sm text-muted-foreground"
                          >
                            <Check className="mt-0.5 size-4 shrink-0 text-brand-accent" />
                            <span>{text}</span>
                          </li>
                        );
                      })}
                    </ul>
                  </CardContent>
                </Card>
              );
            })}
            <MarketingInlineCta
              title={t("marketing.docs.cta.title")}
              description={t("marketing.docs.cta.description")}
              href="/login"
              buttonLabel={t("marketing.docs.cta.button")}
            />
            <p className="text-center text-sm text-muted-foreground">
              {t("marketing.docs.hubNote")}{" "}
              <Link href="/changelog" className="text-brand-accent hover:underline">
                {t("marketing.nav.changelog")}
              </Link>
            </p>
          </div>
        </div>
      </section>
    </MarketingShell>
  );
}

export function TestimonialsSection() {
  const { t } = useLocale();
  const quotes = [1, 2, 3] as const;

  return (
    <section className="relative py-20 md:py-28">
      <MarketingGridBg />
      <div className="relative mx-auto max-w-6xl px-6">
        <MarketingSectionHeader
          badge={t("marketing.testimonials.badge")}
          title={t("marketing.testimonials.title")}
          description={t("marketing.testimonials.subtitle")}
        />
        <div className="mt-14 grid gap-6 lg:grid-cols-3">
          {quotes.map((quote) => {
            const name = t(`marketing.testimonials.quotes.${quote}.name`);
            return (
              <Card
                key={quote}
                className="h-full border-border/80 transition-shadow hover:shadow-md"
              >
                <CardContent className="flex h-full flex-col p-6">
                  <div className="flex items-center justify-between">
                    <Quote className="size-8 text-brand-accent/70" />
                    <div className="flex gap-0.5">
                      {[1, 2, 3, 4, 5].map((star) => (
                        <Star
                          key={star}
                          className="size-3.5 fill-brand-accent text-brand-accent"
                        />
                      ))}
                    </div>
                  </div>
                  <p className="mt-4 flex-1 text-sm leading-relaxed">
                    {t(`marketing.testimonials.quotes.${quote}.text`)}
                  </p>
                  <div className="mt-6 flex items-center gap-3 border-t pt-4">
                    <Avatar size="lg">
                      <AvatarFallback className="bg-accent font-medium text-brand-accent">
                        {getInitials(name)}
                      </AvatarFallback>
                    </Avatar>
                    <div>
                      <p className="text-sm font-semibold">{name}</p>
                      <p className="text-xs text-muted-foreground">
                        {t(`marketing.testimonials.quotes.${quote}.role`)}
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>
      </div>
    </section>
  );
}

export function LegalPageContent({ page }: { page: "privacy" | "terms" }) {
  const { t } = useLocale();
  const sections = [1, 2, 3, 4, 5, 6] as const;

  const tocSections = sections
    .map((section) => {
      const title = t(`marketing.legal.${page}.sections.${section}.title`);
      if (title === `marketing.legal.${page}.sections.${section}.title`) {
        return null;
      }
      return { id: `section-${section}`, title };
    })
    .filter(Boolean) as { id: string; title: string }[];

  return (
    <MarketingShell>
      <MarketingPageHero
        title={t(`marketing.legal.${page}.title`)}
        description={t(`marketing.legal.${page}.subtitle`)}
        breadcrumbs={[
          { label: brand.name, href: "/" },
          { label: t(`marketing.nav.${page}`) },
        ]}
      />
      <MarketingCounselBanner />
      <section className="pb-16 md:pb-20">
        <div className="mx-auto grid max-w-6xl gap-10 px-6 lg:grid-cols-[240px_1fr]">
          <MarketingLegalToc page={page} sections={tocSections} />
          <article className="min-w-0 space-y-8">
            {sections.map((section) => {
              const heading = t(
                `marketing.legal.${page}.sections.${section}.title`,
              );
              if (
                heading === `marketing.legal.${page}.sections.${section}.title`
              ) {
                return null;
              }
              return (
                <section
                  key={section}
                  id={`section-${section}`}
                  className="scroll-mt-24 rounded-2xl border bg-card p-6 md:p-8"
                >
                  <h2 className="text-lg font-semibold tracking-tight">
                    {heading}
                  </h2>
                  <p className="mt-3 text-sm leading-7 text-muted-foreground">
                    {t(`marketing.legal.${page}.sections.${section}.body`)}
                  </p>
                </section>
              );
            })}
            <p className="rounded-xl border border-dashed bg-muted/30 p-4 text-xs leading-relaxed text-muted-foreground">
              {t("marketing.legal.updated")}
            </p>
          </article>
        </div>
      </section>
    </MarketingShell>
  );
}
