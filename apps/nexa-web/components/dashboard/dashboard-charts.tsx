"use client";

import {
  Area,
  AreaChart,
  Bar,
  BarChart,
  CartesianGrid,
  Cell,
  Legend,
  Pie,
  PieChart,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis,
} from "recharts";

import { useLocale } from "@/components/providers/locale-provider";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import type { DashboardCharts } from "@/lib/api/dashboard";
import { cn } from "@/lib/utils";

const CHART_COLORS = [
  "var(--chart-1)",
  "var(--chart-2)",
  "var(--chart-3)",
  "var(--chart-4)",
  "var(--chart-5)",
];

type DashboardChartsPanelProps = {
  charts: DashboardCharts;
  showPlatformChart: boolean;
};

type TooltipRow = {
  name?: string;
  value?: number;
  color?: string;
};

function formatShortDate(date: string, locale: string): string {
  return new Date(`${date}T12:00:00`).toLocaleDateString(locale, {
    month: "short",
    day: "numeric",
  });
}

function ChartTooltipContent({
  active,
  payload,
  label,
  formatValue,
}: {
  active?: boolean;
  payload?: TooltipRow[];
  label?: string;
  formatValue?: (value: number, name?: string) => string;
}) {
  if (!active || !payload?.length) {
    return null;
  }

  return (
    <div className="rounded-lg border bg-card px-3 py-2 text-xs shadow-md">
      {label ? (
        <p className="mb-1.5 font-medium text-foreground">{label}</p>
      ) : null}
      <ul className="space-y-1">
        {payload.map((entry) => (
          <li
            key={entry.name}
            className="flex items-center justify-between gap-4 text-muted-foreground"
          >
            <span className="flex items-center gap-1.5">
              <span
                className="size-2 rounded-full"
                style={{ backgroundColor: entry.color }}
              />
              {entry.name}
            </span>
            <span className="font-medium tabular-nums text-foreground">
              {formatValue
                ? formatValue(Number(entry.value ?? 0), entry.name)
                : String(entry.value ?? 0)}
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}

function ChartShell({
  title,
  description,
  children,
  className,
}: {
  title: string;
  description: string;
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <Card className={cn("shadow-sm", className)}>
      <CardHeader className="pb-2">
        <CardTitle className="text-base">{title}</CardTitle>
        <CardDescription>{description}</CardDescription>
      </CardHeader>
      <CardContent className="h-[280px] pt-0">
        <ResponsiveContainer width="100%" height="100%">
          {children}
        </ResponsiveContainer>
      </CardContent>
    </Card>
  );
}

export function DashboardChartsPanel({
  charts,
  showPlatformChart,
}: DashboardChartsPanelProps) {
  const { t, formatCurrency, locale } = useLocale();
  const dateLocale =
    locale === "ar" ? "ar-MA" : locale === "fr" ? "fr-FR" : "en-US";

  const revenueLabel = t("dashboard.kpis.charts.revenue");
  const invoicesLabel = t("dashboard.kpis.charts.invoices");
  const newCustomersLabel = t("dashboard.kpis.charts.newCustomers");
  const appointmentsLabel = t("dashboard.kpis.charts.appointments");

  const salesData = charts.sales_daily.map((point) => ({
    ...point,
    label: formatShortDate(point.date, dateLocale),
  }));

  const customersData = charts.customers_daily.map((point) => ({
    ...point,
    label: formatShortDate(point.date, dateLocale),
  }));

  const appointmentsData = charts.appointments_daily.map((point) => ({
    ...point,
    label: formatShortDate(point.date, dateLocale),
  }));

  const platformData =
    charts.platform_breakdown?.map((point) => ({
      ...point,
      name:
        point.key === "owners_active"
          ? t("dashboard.kpis.charts.ownersActive")
          : t("dashboard.kpis.charts.ownersInactive"),
      fill:
        point.key === "owners_active" ? CHART_COLORS[0] : CHART_COLORS[3],
    })) ?? [];

  const platformTotal = platformData.reduce((sum, item) => sum + item.value, 0);

  return (
    <section className="space-y-3">
      <h2 className="text-sm font-medium text-muted-foreground">
        {t("dashboard.kpis.charts.title")}
      </h2>

      <div className="grid gap-4 lg:grid-cols-2">
        <ChartShell
          title={t("dashboard.kpis.charts.salesTitle")}
          description={t("dashboard.kpis.charts.salesDescription")}
        >
          <AreaChart data={salesData} margin={{ top: 8, right: 8, left: 0, bottom: 0 }}>
            <defs>
              <linearGradient id="salesGradient" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor={CHART_COLORS[0]} stopOpacity={0.35} />
                <stop offset="100%" stopColor={CHART_COLORS[0]} stopOpacity={0.02} />
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" className="stroke-border/60" />
            <XAxis
              dataKey="label"
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              className="text-xs fill-muted-foreground"
            />
            <YAxis
              yAxisId="revenue"
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              width={56}
              className="text-xs fill-muted-foreground"
              tickFormatter={(value: number) =>
                value >= 1000 ? `${Math.round(value / 1000)}k` : String(value)
              }
            />
            <YAxis
              yAxisId="count"
              orientation="right"
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              width={32}
              className="text-xs fill-muted-foreground"
              allowDecimals={false}
            />
            <Tooltip
              content={({ active, payload, label }) => (
                <ChartTooltipContent
                  active={active}
                  label={String(label ?? "")}
                  payload={payload?.map((entry) => ({
                    name: String(entry.name ?? ""),
                    value: Number(entry.value ?? 0),
                    color: entry.color,
                  }))}
                  formatValue={(value, name) =>
                    name === revenueLabel
                      ? formatCurrency(value)
                      : String(value)
                  }
                />
              )}
            />
            <Area
              yAxisId="revenue"
              type="monotone"
              dataKey="revenue_ttc"
              name={revenueLabel}
              stroke={CHART_COLORS[0]}
              fill="url(#salesGradient)"
              strokeWidth={2}
            />
            <Area
              yAxisId="count"
              type="monotone"
              dataKey="invoice_count"
              name={invoicesLabel}
              stroke={CHART_COLORS[1]}
              fill="transparent"
              strokeWidth={2}
              strokeDasharray="4 4"
            />
          </AreaChart>
        </ChartShell>

        <ChartShell
          title={t("dashboard.kpis.charts.customersTitle")}
          description={t("dashboard.kpis.charts.customersDescription")}
        >
          <BarChart data={customersData} margin={{ top: 8, right: 8, left: 0, bottom: 0 }}>
            <CartesianGrid
              strokeDasharray="3 3"
              className="stroke-border/60"
              vertical={false}
            />
            <XAxis
              dataKey="label"
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              className="text-xs fill-muted-foreground"
            />
            <YAxis
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              width={32}
              allowDecimals={false}
              className="text-xs fill-muted-foreground"
            />
            <Tooltip
              content={({ active, payload, label }) => (
                <ChartTooltipContent
                  active={active}
                  label={String(label ?? "")}
                  payload={payload?.map((entry) => ({
                    name: String(entry.name ?? ""),
                    value: Number(entry.value ?? 0),
                    color: entry.color,
                  }))}
                />
              )}
            />
            <Bar
              dataKey="count"
              name={newCustomersLabel}
              fill={CHART_COLORS[0]}
              radius={[6, 6, 0, 0]}
              maxBarSize={40}
            />
          </BarChart>
        </ChartShell>

        <ChartShell
          title={t("dashboard.kpis.charts.appointmentsTitle")}
          description={t("dashboard.kpis.charts.appointmentsDescription")}
        >
          <BarChart
            data={appointmentsData}
            margin={{ top: 8, right: 8, left: 0, bottom: 0 }}
          >
            <CartesianGrid
              strokeDasharray="3 3"
              className="stroke-border/60"
              vertical={false}
            />
            <XAxis
              dataKey="label"
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              className="text-xs fill-muted-foreground"
            />
            <YAxis
              tickLine={false}
              axisLine={false}
              tickMargin={8}
              width={32}
              allowDecimals={false}
              className="text-xs fill-muted-foreground"
            />
            <Tooltip
              content={({ active, payload, label }) => (
                <ChartTooltipContent
                  active={active}
                  label={String(label ?? "")}
                  payload={payload?.map((entry) => ({
                    name: String(entry.name ?? ""),
                    value: Number(entry.value ?? 0),
                    color: entry.color,
                  }))}
                />
              )}
            />
            <Bar
              dataKey="count"
              name={appointmentsLabel}
              fill={CHART_COLORS[4]}
              radius={[6, 6, 0, 0]}
              maxBarSize={40}
            />
          </BarChart>
        </ChartShell>

        {showPlatformChart ? (
          <Card className="shadow-sm">
            <CardHeader className="pb-2">
              <CardTitle className="text-base">
                {t("dashboard.kpis.charts.platformTitle")}
              </CardTitle>
              <CardDescription>
                {t("dashboard.kpis.charts.platformDescription")}
              </CardDescription>
            </CardHeader>
            <CardContent className="h-[280px] pt-0">
              {platformTotal === 0 ? (
                <div className="flex h-full items-center justify-center text-sm text-muted-foreground">
                  {t("dashboard.kpis.charts.noData")}
                </div>
              ) : (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={platformData}
                      dataKey="value"
                      nameKey="name"
                      cx="50%"
                      cy="46%"
                      innerRadius={64}
                      outerRadius={96}
                      paddingAngle={3}
                    >
                      {platformData.map((entry) => (
                        <Cell key={entry.key} fill={entry.fill} />
                      ))}
                    </Pie>
                    <Tooltip
                      content={({ active, payload }) => (
                        <ChartTooltipContent
                          active={active}
                          payload={payload?.map((entry) => ({
                            name: String(entry.name ?? ""),
                            value: Number(entry.value ?? 0),
                            color: entry.payload?.fill as string | undefined,
                          }))}
                        />
                      )}
                    />
                    <Legend
                      verticalAlign="bottom"
                      height={36}
                      formatter={(value) => (
                        <span className="text-xs text-muted-foreground">
                          {value}
                        </span>
                      )}
                    />
                  </PieChart>
                </ResponsiveContainer>
              )}
            </CardContent>
          </Card>
        ) : null}
      </div>
    </section>
  );
}

export function DashboardChartsSkeleton({
  showPlatformChart,
}: {
  showPlatformChart: boolean;
}) {
  const chartCount = showPlatformChart ? 4 : 3;

  return (
    <section className="space-y-3">
      <div className="h-4 w-24 rounded bg-muted" />
      <div className="grid gap-4 lg:grid-cols-2">
        {Array.from({ length: chartCount }).map((_, index) => (
          <Card key={index} className="shadow-sm">
            <CardHeader className="space-y-2 pb-2">
              <div className="h-5 w-40 rounded bg-muted" />
              <div className="h-3 w-56 rounded bg-muted" />
            </CardHeader>
            <CardContent className="h-[280px] pt-0">
              <div className="h-full w-full rounded-lg bg-muted/60" />
            </CardContent>
          </Card>
        ))}
      </div>
    </section>
  );
}
