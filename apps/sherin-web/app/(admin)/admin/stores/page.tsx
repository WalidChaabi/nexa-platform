"use client";

import { useEffect, useMemo, useState } from "react";
import { List, Store } from "lucide-react";

import { createStoreColumns } from "@/app/(admin)/admin/stores/columns";
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
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function AdminStoresPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const columns = useMemo(() => createStoreColumns(t), [t]);

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
          setErrorKey("admin.stores.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadStores();
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Store}
        title={t("admin.stores.title")}
        description={t("admin.stores.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("admin.stores.listTitle")}
          </CardTitle>
          <CardDescription>{t("admin.stores.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : stores.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("admin.stores.empty")}
            </p>
          ) : (
            <DataTable
              columns={columns}
              data={stores}
              filterColumn="name"
              filterPlaceholder={t("admin.stores.filterPlaceholder")}
              emptyMessage={t("admin.stores.empty")}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
