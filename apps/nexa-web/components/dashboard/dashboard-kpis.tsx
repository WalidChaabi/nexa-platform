"use client";

import { useEffect, useMemo, useState } from "react";
import {
  CalendarDays,
  CreditCard,
  Package,
  Receipt,
  RefreshCw,
  Store,
  TrendingUp,
  UserCog,
  Users,
} from "lucide-react";

import {
  DashboardChartsPanel,
  DashboardChartsSkeleton,
} from "@/components/dashboard/dashboard-charts";
import { KpiCard, KpiCardSkeleton, type KpiCardModel } from "@/components/dashboard/kpi-card";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  fetchDashboardKpis,
  type DashboardKpis,
} from "@/lib/api/dashboard";
import { cn } from "@/lib/utils";

type StoreOption = { id: number; name: string };

type DashboardKpisProps = {
  role: "admin" | "owner" | "employer";
  stores?: StoreOption[];
  storeFilterEnabled?: boolean;
};

type KpiSection = {
  key: string;
  title: string;
  cards: KpiCardModel[];
  layout?: "featured" | "grid";
};

const ALL_STORES = "__all__";

function buildKpiSections(
  data: DashboardKpis,
  t: (key: string, params?: Record<string, string | number>) => string,
  formatCurrency: (value: number) => string,
): KpiSection[] {
  const sections: KpiSection[] = [
    {
      key: "sales",
      title: t("dashboard.kpis.sections.sales"),
      layout: "featured",
      cards: [
        {
          key: "sales-today",
          label: t("dashboard.kpis.salesToday"),
          value: formatCurrency(data.sales.today.revenue_ttc),
          hint: t("dashboard.kpis.invoiceCount", {
            count: data.sales.today.invoice_count,
          }),
          icon: Receipt,
          tone: "accent",
          featured: true,
        },
        {
          key: "sales-month",
          label: t("dashboard.kpis.salesMonth"),
          value: formatCurrency(data.sales.month.revenue_ttc),
          hint: t("dashboard.kpis.invoiceCount", {
            count: data.sales.month.invoice_count,
          }),
          icon: TrendingUp,
          tone: "primary",
          featured: true,
        },
      ],
    },
    {
      key: "activity",
      title: t("dashboard.kpis.sections.activity"),
      layout: "grid",
      cards: [
        {
          key: "customers",
          label: t("dashboard.kpis.customersTotal"),
          value: String(data.customers.total),
          hint: t("dashboard.kpis.customersNewMonth", {
            count: data.customers.new_this_month,
          }),
          icon: Users,
        },
        {
          key: "products",
          label: t("dashboard.kpis.products"),
          value: String(data.catalog.products),
          icon: Package,
        },
        {
          key: "appointments",
          label: t("dashboard.kpis.appointmentsWeek"),
          value: String(data.appointments.upcoming_week),
          icon: CalendarDays,
        },
      ],
    },
  ];

  const networkCards: KpiCardModel[] = [];

  if (
    (data.role === "owner" || data.role === "admin") &&
    data.scope.store_count > 1 &&
    data.scope.store_id === null
  ) {
    networkCards.push({
      key: "stores",
      label: t("dashboard.kpis.stores"),
      value: String(data.scope.store_count),
      icon: Store,
    });
  }

  if (data.billing) {
    networkCards.push({
      key: "billing",
      label: t("dashboard.kpis.paymentsPending"),
      value: String(data.billing.payments_pending),
      icon: CreditCard,
      tone: data.billing.payments_pending > 0 ? "warning" : "default",
    });
  }

  if (networkCards.length > 0) {
    sections.push({
      key: "network",
      title: t("dashboard.kpis.sections.network"),
      layout: "grid",
      cards: networkCards,
    });
  }

  if (data.platform) {
    sections.push({
      key: "platform",
      title: t("dashboard.kpis.sections.platform"),
      layout: "grid",
      cards: [
        {
          key: "owners-active",
          label: t("dashboard.kpis.ownersActive"),
          value: String(data.platform.owners_active),
          hint: t("dashboard.kpis.ownersInactive", {
            count: data.platform.owners_inactive,
          }),
          icon: UserCog,
        },
        {
          key: "platform-stores",
          label: t("dashboard.kpis.platformStores"),
          value: String(data.platform.stores),
          icon: Store,
        },
        {
          key: "platform-payments",
          label: t("dashboard.kpis.platformPaymentsPending"),
          value: String(data.platform.payments_pending),
          icon: CreditCard,
          tone: data.platform.payments_pending > 0 ? "warning" : "default",
        },
      ],
    });
  }

  return sections;
}

function KpiSectionBlock({ section }: { section: KpiSection }) {
  return (
    <section className="space-y-3">
      <h2 className="text-sm font-medium text-muted-foreground">
        {section.title}
      </h2>
      <div
        className={cn(
          "grid gap-4",
          section.layout === "featured"
            ? "md:grid-cols-2"
            : "sm:grid-cols-2 xl:grid-cols-3",
        )}
      >
        {section.cards.map((card) => (
          <KpiCard key={card.key} card={card} />
        ))}
      </div>
    </section>
  );
}

function KpiSkeletonSections({ role }: { role: DashboardKpisProps["role"] }) {
  return (
    <div className="space-y-8">
      <section className="space-y-3">
        <div className="h-4 w-32 rounded bg-muted" />
        <div className="grid gap-4 md:grid-cols-2">
          <KpiCardSkeleton featured />
          <KpiCardSkeleton featured />
        </div>
      </section>
      <section className="space-y-3">
        <div className="h-4 w-28 rounded bg-muted" />
        <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {Array.from({ length: role === "admin" ? 3 : 3 }).map((_, index) => (
            <KpiCardSkeleton key={index} />
          ))}
        </div>
      </section>
      {role === "admin" ? (
        <section className="space-y-3">
          <div className="h-4 w-36 rounded bg-muted" />
          <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            {Array.from({ length: 3 }).map((_, index) => (
              <KpiCardSkeleton key={index} />
            ))}
          </div>
        </section>
      ) : null}
    </div>
  );
}

export function DashboardKpisPanel({
  role,
  stores = [],
  storeFilterEnabled = false,
}: DashboardKpisProps) {
  const { t, formatCurrency } = useLocale();
  const [data, setData] = useState<DashboardKpis | null>(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [error, setError] = useState(false);
  const [storeFilter, setStoreFilter] = useState<string>(ALL_STORES);

  const storeId =
    storeFilterEnabled && storeFilter !== ALL_STORES
      ? Number(storeFilter)
      : undefined;

  async function loadKpis(isRefresh = false) {
    if (isRefresh) {
      setRefreshing(true);
    } else {
      setLoading(true);
    }
    setError(false);

    try {
      const kpis = await fetchDashboardKpis(storeId);
      setData(kpis);
    } catch {
      setError(true);
      setData(null);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }

  useEffect(() => {
    let active = true;

    async function load() {
      setLoading(true);
      setError(false);

      try {
        const kpis = await fetchDashboardKpis(storeId);
        if (active) {
          setData(kpis);
        }
      } catch {
        if (active) {
          setError(true);
          setData(null);
        }
      } finally {
        if (active) {
          setLoading(false);
        }
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, [storeId]);

  const sections = useMemo(
    () => (data ? buildKpiSections(data, t, formatCurrency) : []),
    [data, t, formatCurrency],
  );

  const scopeLabel = data?.scope.store_name
    ? t("dashboard.kpis.storeScope", { name: data.scope.store_name })
    : storeFilter === ALL_STORES && stores.length > 1
      ? t("dashboard.kpis.allStores")
      : null;

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div className="space-y-1">
          <h2 className="text-base font-semibold tracking-tight">
            {t("dashboard.kpis.overview")}
          </h2>
          {scopeLabel ? (
            <p className="text-sm text-muted-foreground">{scopeLabel}</p>
          ) : null}
        </div>

        <div className="flex flex-wrap items-center gap-2">
          {storeFilterEnabled && stores.length > 1 ? (
            <Select
              value={storeFilter}
              onValueChange={(value) => setStoreFilter(value ?? ALL_STORES)}
            >
              <SelectTrigger className="w-full min-w-[12rem] sm:w-56">
                <SelectValue placeholder={t("dashboard.kpis.allStores")} />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value={ALL_STORES}>
                  {t("dashboard.kpis.allStores")}
                </SelectItem>
                {stores.map((store) => (
                  <SelectItem key={store.id} value={String(store.id)}>
                    {store.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          ) : null}

          <Button
            type="button"
            variant="outline"
            size="sm"
            disabled={loading || refreshing}
            onClick={() => void loadKpis(true)}
          >
            <RefreshCw className={cn(refreshing && "animate-spin")} />
            {t("dashboard.kpis.refresh")}
          </Button>
        </div>
      </div>

      {error ? (
        <Alert variant="destructive">
          <AlertTitle>{t("dashboard.kpis.loadErrorTitle")}</AlertTitle>
          <AlertDescription>{t("dashboard.kpis.loadError")}</AlertDescription>
        </Alert>
      ) : null}

      {loading ? (
        <>
          <KpiSkeletonSections role={role} />
          <DashboardChartsSkeleton showPlatformChart={role === "admin"} />
        </>
      ) : data ? (
        <div className="space-y-8">
          {sections.map((section) => (
            <KpiSectionBlock key={section.key} section={section} />
          ))}
          <DashboardChartsPanel
            charts={data.charts}
            showPlatformChart={role === "admin"}
          />
        </div>
      ) : null}
    </div>
  );
}
