"use client";

import { useEffect, useMemo, useState } from "react";
import {
  CheckCircle2,
  Circle,
  Megaphone,
  Package,
  Rocket,
  Store,
  UserPlus,
  X,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";
import { Skeleton } from "@/components/ui/skeleton";
import { fetchCampaignHistoriques } from "@/lib/api/campaigns";
import { fetchEmployers } from "@/lib/api/employers";
import { fetchProducts } from "@/lib/api/products";
import { fetchStores } from "@/lib/api/stores";

const DISMISS_STORAGE_KEY = "nexa-owner-onboarding-dismissed";

type OnboardingStep = {
  id: string;
  titleKey: string;
  descriptionKey: string;
  href: string;
  icon: LucideIcon;
  done: boolean;
};

type OnboardingProgress = {
  storeCount: number;
  productCount: number;
  employerCount: number;
  campaignCount: number;
};

function readDismissed(): boolean {
  if (typeof window === "undefined") {
    return false;
  }

  return window.localStorage.getItem(DISMISS_STORAGE_KEY) === "1";
}

function writeDismissed(): void {
  window.localStorage.setItem(DISMISS_STORAGE_KEY, "1");
}

export function OwnerOnboardingChecklist() {
  const { t } = useLocale();
  const [loading, setLoading] = useState(true);
  const [dismissed, setDismissed] = useState(() => readDismissed());
  const [progress, setProgress] = useState<OnboardingProgress>({
    storeCount: 0,
    productCount: 0,
    employerCount: 0,
    campaignCount: 0,
  });

  useEffect(() => {
    let active = true;

    async function load() {
      setLoading(true);

      const [storesResult, productsResult, employersResult, campaignsResult] =
        await Promise.allSettled([
          fetchStores(),
          fetchProducts(),
          fetchEmployers(),
          fetchCampaignHistoriques(),
        ]);

      if (!active) {
        return;
      }

      setProgress({
        storeCount:
          storesResult.status === "fulfilled" ? storesResult.value.length : 0,
        productCount:
          productsResult.status === "fulfilled"
            ? productsResult.value.length
            : 0,
        employerCount:
          employersResult.status === "fulfilled"
            ? employersResult.value.length
            : 0,
        campaignCount:
          campaignsResult.status === "fulfilled"
            ? campaignsResult.value.length
            : 0,
      });
      setLoading(false);
    }

    void load();

    return () => {
      active = false;
    };
  }, []);

  const steps = useMemo<OnboardingStep[]>(
    () => [
      {
        id: "store",
        titleKey: "owner.onboarding.steps.store.title",
        descriptionKey: "owner.onboarding.steps.store.description",
        href: "/owner/stores",
        icon: Store,
        done: progress.storeCount > 0,
      },
      {
        id: "products",
        titleKey: "owner.onboarding.steps.products.title",
        descriptionKey: "owner.onboarding.steps.products.description",
        href: "/owner/products",
        icon: Package,
        done: progress.productCount > 0,
      },
      {
        id: "team",
        titleKey: "owner.onboarding.steps.team.title",
        descriptionKey: "owner.onboarding.steps.team.description",
        href: "/owner/team",
        icon: UserPlus,
        done: progress.employerCount > 0,
      },
      {
        id: "campaign",
        titleKey: "owner.onboarding.steps.campaign.title",
        descriptionKey: "owner.onboarding.steps.campaign.description",
        href: "/owner/campaigns",
        icon: Megaphone,
        done: progress.campaignCount > 0,
      },
    ],
    [progress],
  );

  const completedCount = steps.filter((step) => step.done).length;
  const allComplete = completedCount === steps.length;

  if (dismissed || allComplete) {
    return null;
  }

  function handleDismiss() {
    writeDismissed();
    setDismissed(true);
  }

  return (
    <Card className="border-primary/20 bg-primary/5 shadow-sm">
      <CardHeader className="gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div className="space-y-1">
          <div className="flex items-center gap-2">
            <Rocket className="size-4 text-primary" />
            <CardTitle className="text-base font-medium">
              {t("owner.onboarding.title")}
            </CardTitle>
          </div>
          <CardDescription>{t("owner.onboarding.description")}</CardDescription>
        </div>
        <Button
          type="button"
          variant="ghost"
          size="icon-sm"
          className="shrink-0 text-muted-foreground"
          onClick={handleDismiss}
          aria-label={t("owner.onboarding.dismiss")}
        >
          <X />
        </Button>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="space-y-2">
          <div className="flex items-center justify-between text-sm">
            <span className="text-muted-foreground">
              {t("owner.onboarding.progress", {
                completed: completedCount,
                total: steps.length,
              })}
            </span>
            <span className="font-medium text-primary">
              {Math.round((completedCount / steps.length) * 100)}%
            </span>
          </div>
          <div className="h-2 overflow-hidden rounded-full bg-primary/10">
            <div
              className="h-full rounded-full bg-primary transition-all duration-300"
              style={{
                width: `${(completedCount / steps.length) * 100}%`,
              }}
            />
          </div>
        </div>

        <ul className="grid gap-3 sm:grid-cols-2">
          {steps.map((step) => {
            const Icon = step.icon;
            const StatusIcon = step.done ? CheckCircle2 : Circle;

            return (
              <li
                key={step.id}
                className="flex items-start gap-3 rounded-lg border border-border/70 bg-background/80 p-3"
              >
                <StatusIcon
                  className={
                    step.done
                      ? "mt-0.5 size-5 shrink-0 text-primary"
                      : "mt-0.5 size-5 shrink-0 text-muted-foreground"
                  }
                />
                <div className="min-w-0 flex-1 space-y-2">
                  <div className="space-y-1">
                    <div className="flex items-center gap-2">
                      <Icon className="size-4 text-muted-foreground" />
                      <p className="font-medium leading-none">
                        {loading ? (
                          <Skeleton className="h-4 w-32" />
                        ) : (
                          t(step.titleKey)
                        )}
                      </p>
                    </div>
                    <p className="text-sm text-muted-foreground">
                      {loading ? (
                        <Skeleton className="h-3 w-full max-w-xs" />
                      ) : (
                        t(step.descriptionKey)
                      )}
                    </p>
                  </div>
                  {!step.done && !loading ? (
                    <NavLink href={step.href} variant="outline" size="sm">
                      {t("owner.onboarding.action")}
                    </NavLink>
                  ) : null}
                </div>
              </li>
            );
          })}
        </ul>
      </CardContent>
    </Card>
  );
}
