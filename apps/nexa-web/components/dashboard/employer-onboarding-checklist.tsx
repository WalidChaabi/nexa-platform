"use client";

import { useEffect, useMemo, useState } from "react";
import {
  CheckCircle2,
  Circle,
  Package,
  Receipt,
  Rocket,
  Users,
  X,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
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
import { fetchCustomers } from "@/lib/api/customers";
import { fetchInvoices } from "@/lib/api/invoices";
import { fetchProducts } from "@/lib/api/products";

const DISMISS_STORAGE_KEY = "nexa-employer-onboarding-dismissed";

type OnboardingStep = {
  id: string;
  titleKey: string;
  descriptionKey: string;
  href: string;
  icon: LucideIcon;
  done: boolean;
};

type OnboardingProgress = {
  productCount: number;
  customerCount: number;
  invoiceCount: number;
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

export function EmployerOnboardingChecklist() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [loading, setLoading] = useState(true);
  const [dismissed, setDismissed] = useState(() => readDismissed());
  const [progress, setProgress] = useState<OnboardingProgress>({
    productCount: 0,
    customerCount: 0,
    invoiceCount: 0,
  });

  const storeId = user.store_id;

  useEffect(() => {
    if (storeId == null) {
      setLoading(false);
      return;
    }

    let active = true;
    const activeStoreId = storeId;

    async function load() {
      setLoading(true);

      const [productsResult, customersResult, invoicesResult] =
        await Promise.allSettled([
          fetchProducts({ store_id: activeStoreId }),
          fetchCustomers({ store_id: activeStoreId }),
          fetchInvoices({ store_id: activeStoreId }),
        ]);

      if (!active) {
        return;
      }

      setProgress({
        productCount:
          productsResult.status === "fulfilled"
            ? productsResult.value.length
            : 0,
        customerCount:
          customersResult.status === "fulfilled"
            ? customersResult.value.length
            : 0,
        invoiceCount:
          invoicesResult.status === "fulfilled"
            ? invoicesResult.value.length
            : 0,
      });
      setLoading(false);
    }

    void load();

    return () => {
      active = false;
    };
  }, [storeId]);

  const steps = useMemo<OnboardingStep[]>(
    () => [
      {
        id: "products",
        titleKey: "employer.onboarding.steps.products.title",
        descriptionKey: "employer.onboarding.steps.products.description",
        href: "/employer/products",
        icon: Package,
        done: progress.productCount > 0,
      },
      {
        id: "customers",
        titleKey: "employer.onboarding.steps.customers.title",
        descriptionKey: "employer.onboarding.steps.customers.description",
        href: "/employer/customers",
        icon: Users,
        done: progress.customerCount > 0,
      },
      {
        id: "invoice",
        titleKey: "employer.onboarding.steps.invoice.title",
        descriptionKey: "employer.onboarding.steps.invoice.description",
        href: "/employer/invoices/create",
        icon: Receipt,
        done: progress.invoiceCount > 0,
      },
    ],
    [progress],
  );

  const completedCount = steps.filter((step) => step.done).length;
  const allComplete = completedCount === steps.length;

  if (storeId == null || dismissed || allComplete) {
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
              {t("employer.onboarding.title")}
            </CardTitle>
          </div>
          <CardDescription>{t("employer.onboarding.description")}</CardDescription>
        </div>
        <Button
          type="button"
          variant="ghost"
          size="icon-sm"
          className="shrink-0 text-muted-foreground"
          onClick={handleDismiss}
          aria-label={t("employer.onboarding.dismiss")}
        >
          <X />
        </Button>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="space-y-2">
          <div className="flex items-center justify-between text-sm">
            <span className="text-muted-foreground">
              {t("employer.onboarding.progress", {
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

        <ul className="grid gap-3 sm:grid-cols-3">
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
                      {t("employer.onboarding.action")}
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
