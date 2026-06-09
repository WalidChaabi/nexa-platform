"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { History, Megaphone, Plus } from "lucide-react";

import { CampaignCreateDialog } from "@/components/campaigns/campaign-create-dialog";
import { createCampaignDetailColumns } from "@/components/campaigns/campaign-detail-columns";
import { CampaignFormSheet } from "@/components/campaigns/campaign-form-sheet";
import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { createCampaignHistoriqueColumns } from "@/components/campaigns/campaign-historique-columns";
import { Skeleton } from "@/components/ui/skeleton";
import {
  deleteCampaignDetail,
  fetchCampaignDetails,
  fetchCampaignHistoriques,
  fetchCampaignTypes,
  updateCampaignDetail,
  type CampaignDetailSummary,
  type CampaignHistoriqueSummary,
  type CampaignType,
} from "@/lib/api/campaigns";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function OwnerCampaignsPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [types, setTypes] = useState<CampaignType[]>([]);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [details, setDetails] = useState<CampaignDetailSummary[]>([]);
  const [historiques, setHistoriques] = useState<CampaignHistoriqueSummary[]>(
    [],
  );
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [createOpen, setCreateOpen] = useState(false);
  const [editDetailId, setEditDetailId] = useState<number | null>(null);
  const [editOpen, setEditOpen] = useState(false);

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [storeData, typeData, detailData, historyData, productData] =
          await Promise.all([
            fetchStores(),
            fetchCampaignTypes(),
            fetchCampaignDetails(),
            fetchCampaignHistoriques(),
            fetchProducts(),
          ]);

        if (active) {
          setStores(storeData);
          setTypes(typeData);
          setDetails(detailData);
          setHistoriques(historyData);
          setProducts(productData);
        }
      } catch {
        if (active) {
          setErrorKey("owner.campaigns.loadError");
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
  }, [reloadKey]);

  const handleToggleActive = useCallback(async (detail: CampaignDetailSummary) => {
    setErrorKey(null);

    try {
      await updateCampaignDetail(detail.id, {
        active: detail.active === 1 ? 0 : 1,
      });
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.campaigns.updateError");
    }
  }, []);

  const handleDelete = useCallback(async (detail: CampaignDetailSummary) => {
    setErrorKey(null);

    try {
      await deleteCampaignDetail(detail.id);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.campaigns.deleteError");
    }
  }, []);

  const historyColumns = useMemo(
    () => createCampaignHistoriqueColumns({ t }),
    [t],
  );

  const columns = useMemo(
    () =>
      createCampaignDetailColumns({
        t,
        onEdit: (detail) => {
          setEditDetailId(detail.id);
          setEditOpen(true);
        },
        onToggleActive: (detail) => void handleToggleActive(detail),
        onDelete: (detail) => void handleDelete(detail),
      }),
    [t, handleToggleActive, handleDelete],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Megaphone}
        title={t("owner.campaigns.title")}
        description={t("owner.campaigns.description")}
      >
        <Button onClick={() => setCreateOpen(true)}>
          <Plus />
          {t("owner.campaigns.create")}
        </Button>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <CampaignCreateDialog
        open={createOpen}
        onOpenChange={setCreateOpen}
        stores={stores}
        types={types}
        products={products}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.campaigns.listTitle")}</CardTitle>
          <CardDescription>{t("owner.campaigns.listDescription")}</CardDescription>
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
              filterPlaceholder={t("owner.campaigns.filterPlaceholder")}
              emptyMessage={t("owner.campaigns.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <History className="size-4 text-primary" />
            {t("owner.campaigns.historyTitle")}
          </CardTitle>
          <CardDescription>{t("owner.campaigns.historyDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-24 w-full" />
          ) : (
            <DataTable
              columns={historyColumns}
              data={historiques}
              filterColumn="search"
              filterPlaceholder={t("owner.campaigns.historyFilterPlaceholder")}
              emptyMessage={t("owner.campaigns.historyEmpty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>

      <CampaignFormSheet
        open={editOpen}
        onOpenChange={setEditOpen}
        detailId={editDetailId}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />
    </div>
  );
}
