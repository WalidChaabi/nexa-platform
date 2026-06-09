"use client";

import Link from "next/link";
import type { LucideIcon } from "lucide-react";
import {
  AlertTriangle,
  ArrowRight,
  Mail,
  Scale,
  Sparkles,
} from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
import { NavLink } from "@/components/ui/nav-link";
import { cn } from "@/lib/utils";

export function MarketingGridBg({ className }: { className?: string }) {
  return (
    <div
      aria-hidden
      className={cn(
        "pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,#0f172a08_1px,transparent_1px),linear-gradient(to_bottom,#0f172a08_1px,transparent_1px)] bg-size-[4rem_4rem] mask-[radial-gradient(ellipse_at_center,black,transparent_80%)]",
        className,
      )}
    />
  );
}

export function MarketingSectionHeader({
  badge,
  title,
  description,
  align = "center",
  className,
}: {
  badge?: string;
  title: string;
  description?: string;
  align?: "center" | "start";
  className?: string;
}) {
  return (
    <div
      className={cn(
        "max-w-2xl",
        align === "center" && "mx-auto text-center",
        className,
      )}
    >
      {badge ? (
        <Badge variant="secondary" className="mb-4">
          {badge}
        </Badge>
      ) : null}
      <h2 className="text-3xl font-semibold tracking-tight text-balance sm:text-4xl">
        {title}
      </h2>
      {description ? (
        <p className="mt-4 text-lg text-muted-foreground">{description}</p>
      ) : null}
    </div>
  );
}

export function MarketingSalesBanner() {
  const { t } = useLocale();

  return (
    <div className="mx-auto max-w-6xl px-6 pb-8">
      <div className="relative overflow-hidden rounded-2xl border border-brand-accent/25 bg-linear-to-br from-accent/60 via-background to-background p-6 md:p-8">
        <MarketingGridBg className="opacity-60" />
        <div className="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
          <div className="max-w-xl space-y-2">
            <div className="flex items-center gap-2 text-brand-accent">
              <Sparkles className="size-4" />
              <span className="text-sm font-medium">
                {t("marketing.pricing.salesBanner.eyebrow")}
              </span>
            </div>
            <p className="text-xl font-semibold tracking-tight">
              {t("marketing.pricing.salesBanner.title")}
            </p>
            <p className="text-sm leading-relaxed text-muted-foreground">
              {t("marketing.pricing.salesBanner.description")}
            </p>
          </div>
          <div className="flex shrink-0 flex-col gap-2 sm:flex-row">
            <NavLink
              href="mailto:sales@nexa.app"
              className="bg-brand-accent text-primary hover:bg-brand-accent/90"
            >
              <Mail />
              {t("marketing.pricing.salesBanner.cta")}
            </NavLink>
            <NavLink href="/docs" variant="outline">
              {t("marketing.pricing.salesBanner.docs")}
            </NavLink>
          </div>
        </div>
      </div>
    </div>
  );
}

export function MarketingCounselBanner() {
  const { t } = useLocale();

  return (
    <div className="mx-auto max-w-6xl px-6 pt-6">
      <Alert className="border-amber-500/30 bg-amber-500/5">
        <Scale className="text-amber-600 dark:text-amber-400" />
        <AlertTitle>{t("marketing.legal.counselBanner.title")}</AlertTitle>
        <AlertDescription>
          {t("marketing.legal.counselBanner.description")}
        </AlertDescription>
      </Alert>
    </div>
  );
}

export function MarketingPlaceholderBanner() {
  const { t } = useLocale();

  if (process.env.NODE_ENV === "development") {
    return null;
  }

  return (
    <div className="mx-auto max-w-6xl px-6 pb-6">
      <Alert className="border-border/80 bg-muted/40">
        <AlertTriangle className="text-muted-foreground" />
        <AlertTitle>{t("marketing.placeholder.title")}</AlertTitle>
        <AlertDescription>{t("marketing.placeholder.description")}</AlertDescription>
      </Alert>
    </div>
  );
}

export function MarketingInlineCta({
  title,
  description,
  href,
  buttonLabel,
  variant = "default",
}: {
  title: string;
  description: string;
  href: string;
  buttonLabel: string;
  variant?: "default" | "dark";
}) {
  return (
    <div
      className={cn(
        "relative overflow-hidden rounded-2xl border p-8 md:p-10",
        variant === "dark"
          ? "border-white/10 bg-primary text-primary-foreground"
          : "border-brand-accent/20 bg-accent/30",
      )}
    >
      {variant === "dark" ? (
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,#00C38933,transparent_60%)]" />
      ) : null}
      <div className="relative flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
        <div className="max-w-xl">
          <p className="text-xl font-semibold tracking-tight">{title}</p>
          <p
            className={cn(
              "mt-2 text-sm leading-relaxed",
              variant === "dark"
                ? "text-primary-foreground/85"
                : "text-muted-foreground",
            )}
          >
            {description}
          </p>
        </div>
        <NavLink
          href={href}
          className={cn(
            variant === "dark" &&
              "bg-brand-accent text-primary hover:bg-brand-accent/90",
          )}
          variant={variant === "dark" ? "default" : "default"}
        >
          {buttonLabel}
          <ArrowRight />
        </NavLink>
      </div>
    </div>
  );
}

export function MarketingFeatureCard({
  icon: Icon,
  title,
  description,
  index,
}: {
  icon: LucideIcon;
  title: string;
  description: string;
  index?: number;
}) {
  return (
    <div className="group relative rounded-2xl border bg-card p-6 transition-all hover:-translate-y-0.5 hover:border-brand-accent/30 hover:shadow-lg hover:shadow-brand-accent/5">
      <div className="mb-4 flex items-start justify-between gap-3">
        <div className="flex size-11 items-center justify-center rounded-xl bg-accent transition-colors group-hover:bg-brand-accent/15">
          <Icon className="size-5 text-brand-accent" />
        </div>
        {index != null ? (
          <span className="text-xs font-medium tabular-nums text-muted-foreground">
            {String(index).padStart(2, "0")}
          </span>
        ) : null}
      </div>
      <h3 className="text-lg font-semibold tracking-tight">{title}</h3>
      <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
        {description}
      </p>
    </div>
  );
}

export function MarketingDocsSidebar({
  sections,
  activeId,
}: {
  sections: { id: string; label: string }[];
  activeId?: string;
}) {
  const { t } = useLocale();

  return (
    <nav className="sticky top-24 hidden lg:block">
      <p className="mb-3 text-xs font-semibold tracking-wider text-muted-foreground uppercase">
        {t("marketing.docs.onThisPage")}
      </p>
      <ul className="space-y-1 border-s-2 border-border ps-4">
        {sections.map((section) => (
          <li key={section.id}>
            <a
              href={`#${section.id}`}
              className={cn(
                "block py-1.5 text-sm transition-colors hover:text-foreground",
                activeId === section.id
                  ? "font-medium text-brand-accent"
                  : "text-muted-foreground",
              )}
            >
              {section.label}
            </a>
          </li>
        ))}
      </ul>
    </nav>
  );
}

export function MarketingLegalToc({
  page,
  sections,
}: {
  page: "privacy" | "terms";
  sections: { id: string; title: string }[];
}) {
  const { t } = useLocale();
  const otherPage = page === "privacy" ? "terms" : "privacy";

  return (
    <aside className="space-y-6">
      <nav className="rounded-2xl border bg-card p-5">
        <p className="mb-3 text-xs font-semibold tracking-wider text-muted-foreground uppercase">
          {t("marketing.legal.toc")}
        </p>
        <ul className="space-y-2">
          {sections.map((section) => (
            <li key={section.id}>
              <a
                href={`#${section.id}`}
                className="text-sm text-muted-foreground transition-colors hover:text-foreground"
              >
                {section.title}
              </a>
            </li>
          ))}
        </ul>
      </nav>
      <div className="rounded-2xl border bg-muted/30 p-5 text-sm">
        <p className="font-medium">{t("marketing.legal.related")}</p>
        <Link
          href={`/${otherPage}`}
          className="mt-2 inline-flex items-center gap-1 text-brand-accent hover:underline"
        >
          {t(`marketing.nav.${otherPage}`)}
          <ArrowRight className="size-3.5" />
        </Link>
      </div>
    </aside>
  );
}

export const marketingAccentButtonClass =
  "bg-brand-accent text-primary hover:bg-brand-accent/90";

export const marketingHeroOutlineClass =
  "border-white/20 bg-white/5 text-primary-foreground hover:bg-white/10 hover:text-primary-foreground";
