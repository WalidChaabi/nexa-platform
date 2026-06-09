"use client";

import Link from "next/link";
import { ChevronRight } from "lucide-react";

import { MarketingGridBg } from "@/components/marketing/marketing-ui";
import { Badge } from "@/components/ui/badge";
import { cn } from "@/lib/utils";

type MarketingPageHeroProps = {
  badge?: string;
  title: string;
  description: string;
  variant?: "light" | "dark";
  breadcrumbs?: { label: string; href?: string }[];
};

export function MarketingPageHero({
  badge,
  title,
  description,
  variant = "light",
  breadcrumbs,
}: MarketingPageHeroProps) {
  const isDark = variant === "dark";

  return (
    <section
      className={cn(
        "relative overflow-hidden border-b",
        isDark
          ? "bg-primary text-primary-foreground"
          : "bg-linear-to-b from-muted/60 to-background",
      )}
    >
      {isDark ? (
        <>
          <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,#00C38933,transparent_55%)]" />
          <MarketingGridBg className="opacity-20 mask-none" />
        </>
      ) : (
        <MarketingGridBg />
      )}
      <div className="relative mx-auto max-w-6xl px-6 py-14 md:py-20">
        {breadcrumbs && breadcrumbs.length > 0 ? (
          <nav
            aria-label="Breadcrumb"
            className="mb-6 flex flex-wrap items-center gap-1 text-sm"
          >
            {breadcrumbs.map((crumb, index) => (
              <span key={crumb.label} className="flex items-center gap-1">
                {index > 0 ? (
                  <ChevronRight
                    className={cn(
                      "size-3.5",
                      isDark ? "text-primary-foreground/50" : "text-muted-foreground",
                    )}
                  />
                ) : null}
                {crumb.href ? (
                  <Link
                    href={crumb.href}
                    className={cn(
                      "transition-colors hover:underline",
                      isDark
                        ? "text-primary-foreground/70 hover:text-primary-foreground"
                        : "text-muted-foreground hover:text-foreground",
                    )}
                  >
                    {crumb.label}
                  </Link>
                ) : (
                  <span
                    className={
                      isDark ? "text-primary-foreground" : "text-foreground"
                    }
                  >
                    {crumb.label}
                  </span>
                )}
              </span>
            ))}
          </nav>
        ) : null}
        <div className="mx-auto max-w-3xl text-center">
          {badge ? (
            <Badge
              variant={isDark ? "secondary" : "secondary"}
              className={cn(
                "mb-4",
                isDark && "border-white/15 bg-white/10 text-primary-foreground",
              )}
            >
              {badge}
            </Badge>
          ) : null}
          <h1
            className={cn(
              "text-3xl font-semibold tracking-tight text-balance sm:text-4xl md:text-5xl",
              isDark && "text-primary-foreground",
            )}
          >
            {title}
          </h1>
          <p
            className={cn(
              "mt-4 text-lg leading-relaxed",
              isDark ? "text-primary-foreground/85" : "text-muted-foreground",
            )}
          >
            {description}
          </p>
        </div>
      </div>
    </section>
  );
}
