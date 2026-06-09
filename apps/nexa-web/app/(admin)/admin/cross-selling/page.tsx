"use client";

import { useEffect, useMemo, useState } from "react";
import { History, RefreshCw } from "lucide-react";

import { createAdminCrossSellingColumns } from "@/components/cross-selling/admin-cross-selling-columns";
import { createCrossSellingHistoryColumns } from "@/components/cross-selling/cross-selling-history-columns";
import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import {
  fetchCrossProducts,
  fetchSmsCrossSelling,
  type CrossProductRule,
  type SmsCrossSellingRecord,
} from "@/lib/api/cross-selling";

export default function AdminCrossSellingPage() {
  const { t, formatDateTime } = useLocale();
  const [rules, setRules] = useState<CrossProductRule[]>([]);
  const [smsHistory, setSmsHistory] = useState<SmsCrossSellingRecord[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [ruleData, historyData] = await Promise.all([
          fetchCrossProducts(),
          fetchSmsCrossSelling(),
        ]);
        if (active) {
          setRules(ruleData);
          setSmsHistory(historyData);
        }
      } catch {
        if (active) {
          setErrorKey("admin.crossSelling.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, []);

  const columns = useMemo(
    () => createAdminCrossSellingColumns({ t }),
    [t],
  );

  const historyColumns = useMemo(
    () =>
      createCrossSellingHistoryColumns({
        t,
        formatDateTime,
        namespace: "admin.crossSelling",
      }),
    [t, formatDateTime],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={RefreshCw}
        title={t("admin.crossSelling.title")}
        description={t("admin.crossSelling.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("admin.crossSelling.listTitle")}</CardTitle>
          <CardDescription>{t("admin.crossSelling.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={rules}
              filterColumn="search"
              filterPlaceholder={t("admin.crossSelling.filterPlaceholder")}
              emptyMessage={t("admin.crossSelling.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <History className="size-4 text-primary" />
            {t("admin.crossSelling.historyTitle")}
          </CardTitle>
          <CardDescription>
            {t("admin.crossSelling.historyDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-24 w-full" />
          ) : (
            <DataTable
              columns={historyColumns}
              data={smsHistory}
              filterColumn="search"
              filterPlaceholder={t("admin.crossSelling.historyFilterPlaceholder")}
              emptyMessage={t("admin.crossSelling.historyEmpty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
