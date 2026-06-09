"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Plus, Store } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { StoreFormDialog } from "@/components/stores/store-form-dialog";
import { createStoresColumns } from "@/components/stores/stores-columns";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";
import { Skeleton } from "@/components/ui/skeleton";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function OwnerStoresPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [editStore, setEditStore] = useState<StoreSummary | null>(null);
  const [editOpen, setEditOpen] = useState(false);

  useEffect(() => {
    let active = true;

    async function loadStores() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchStores();
        if (active) {
          setStores(data);
        }
      } catch {
        if (active) {
          setErrorKey("owner.stores.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadStores();

    return () => {
      active = false;
    };
  }, [reloadKey]);

  const handleEdit = useCallback((store: StoreSummary) => {
    setEditStore(store);
    setEditOpen(true);
  }, []);

  const columns = useMemo(
    () =>
      createStoresColumns({
        t,
        onEdit: handleEdit,
      }),
    [t, handleEdit],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Store}
        title={t("owner.stores.title")}
        description={t("owner.stores.description")}
      >
        <NavLink href="/owner/stores/new">
          <Plus />
          {t("owner.stores.create")}
        </NavLink>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <StoreFormDialog
        open={editOpen}
        store={editStore}
        onOpenChange={(open) => {
          setEditOpen(open);
          if (!open) {
            setEditStore(null);
          }
        }}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.stores.listTitle")}</CardTitle>
          <CardDescription>{t("owner.stores.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : stores.length === 0 ? (
            <div className="flex flex-col items-center gap-3 py-8 text-center">
              <p className="text-sm text-muted-foreground">
                {t("owner.stores.empty")}
              </p>
              <NavLink href="/owner/stores/new">
                <Plus />
                {t("owner.stores.createFirst")}
              </NavLink>
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={stores}
              filterColumn="search"
              filterPlaceholder={t("owner.stores.filterPlaceholder")}
              emptyMessage={t("owner.stores.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
