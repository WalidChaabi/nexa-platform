"use client";

import type { LucideIcon } from "lucide-react";

import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { cn } from "@/lib/utils";

export type KpiTone = "default" | "primary" | "accent" | "warning";

export type KpiCardModel = {
  key: string;
  label: string;
  value: string;
  hint?: string;
  icon: LucideIcon;
  tone?: KpiTone;
  featured?: boolean;
};

const toneStyles: Record<
  KpiTone,
  { icon: string; ring: string; featured: string }
> = {
  default: {
    icon: "bg-muted text-muted-foreground",
    ring: "border-border/80",
    featured: "from-card to-muted/40",
  },
  primary: {
    icon: "bg-primary/10 text-primary",
    ring: "border-primary/15",
    featured: "from-card via-card to-primary/5",
  },
  accent: {
    icon: "bg-brand-accent/15 text-brand-accent",
    ring: "border-brand-accent/20",
    featured: "from-card via-card to-brand-accent/10",
  },
  warning: {
    icon: "bg-brand-warning/15 text-brand-warning",
    ring: "border-brand-warning/30",
    featured: "from-card via-card to-brand-warning/10",
  },
};

export function KpiCard({ card }: { card: KpiCardModel }) {
  const tone = card.tone ?? (card.featured ? "primary" : "default");
  const styles = toneStyles[tone];
  const Icon = card.icon;

  return (
    <Card
      className={cn(
        "relative overflow-hidden shadow-sm transition-[box-shadow,border-color] hover:shadow-md",
        styles.ring,
        card.featured &&
          cn("bg-gradient-to-br sm:min-h-[9.5rem]", styles.featured),
      )}
    >
      <CardHeader
        className={cn(
          "flex flex-row items-start justify-between gap-3 space-y-0",
          card.featured ? "pb-3" : "pb-2",
        )}
      >
        <div className="min-w-0 space-y-1.5">
          <CardDescription className="text-xs font-medium uppercase tracking-wide">
            {card.label}
          </CardDescription>
          <CardTitle
            className={cn(
              "truncate font-semibold tabular-nums tracking-tight",
              card.featured ? "text-3xl" : "text-2xl",
            )}
          >
            {card.value}
          </CardTitle>
        </div>
        <div
          className={cn(
            "flex size-10 shrink-0 items-center justify-center rounded-xl",
            styles.icon,
          )}
        >
          <Icon className="size-5" />
        </div>
      </CardHeader>
      {card.hint ? (
        <CardContent className="pt-0 text-xs text-muted-foreground">
          {card.hint}
        </CardContent>
      ) : null}
    </Card>
  );
}

export function KpiCardSkeleton({ featured = false }: { featured?: boolean }) {
  return (
    <Card className={cn(featured && "sm:min-h-[9.5rem]")}>
      <CardHeader className="flex flex-row items-start justify-between gap-3 space-y-0 pb-2">
        <div className="flex-1 space-y-2">
          <div className="h-3 w-24 rounded bg-muted" />
          <div className={cn("rounded bg-muted", featured ? "h-9 w-32" : "h-8 w-28")} />
        </div>
        <div className="size-10 rounded-xl bg-muted" />
      </CardHeader>
      <CardContent className="pt-0">
        <div className="h-3 w-36 rounded bg-muted" />
      </CardContent>
    </Card>
  );
}
