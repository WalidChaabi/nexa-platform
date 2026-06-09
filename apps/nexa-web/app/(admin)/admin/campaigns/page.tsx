"use client";

import { useEffect, useMemo, useState } from "react";
import { History, Megaphone } from "lucide-react";

import { createAdminCampaignDetailColumns } from "@/components/campaigns/admin-campaign-detail-columns";
import { createCampaignHistoriqueColumns } from "@/components/campaigns/campaign-historique-columns";
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
  fetchCampaignDetails,
  fetchCampaignHistoriques,
  type CampaignDetailSummary,
  type CampaignHistoriqueSummary,
} from "@/lib/api/campaigns";

export default function AdminCampaignsPage() {
  const { t, formatDateTime } = useLocale();
  const [details, setDetails] = useState<CampaignDetailSummary[]>([]);
  const [historiques, setHistoriques] = useState<CampaignHistoriqueSummary[]>(
    [],
  );
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [detailData, historyData] = await Promise.all([
          fetchCampaignDetails(),
          fetchCampaignHistoriques(),
        ]);
        if (active) {
          setDetails(detailData);
          setHistoriques(historyData);
        }
      } catch {
        if (active) {
          setErrorKey("admin.campaigns.loadError");
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
    () => createAdminCampaignDetailColumns({ t }),
    [t],
  );

  const historyColumns = useMemo(
    () =>
      createCampaignHistoriqueColumns({
        t,
        formatDateTime,
        namespace: "admin.campaigns",
      }),
    [t, formatDateTime],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Megaphone}
        title={t("admin.campaigns.title")}
        description={t("admin.campaigns.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("admin.campaigns.listTitle")}</CardTitle>
          <CardDescription>{t("admin.campaigns.listDescription")}</CardDescription>
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
              data={details}
              filterColumn="search"
              filterPlaceholder={t("admin.campaigns.filterPlaceholder")}
              emptyMessage={t("admin.campaigns.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <History className="size-4 text-primary" />
            {t("admin.campaigns.historyTitle")}
          </CardTitle>
          <CardDescription>{t("admin.campaigns.historyDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-24 w-full" />
          ) : (
            <DataTable
              columns={historyColumns}
              data={historiques}
              filterColumn="search"
              filterPlaceholder={t("admin.campaigns.historyFilterPlaceholder")}
              emptyMessage={t("admin.campaigns.historyEmpty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
