"use client";

import { useEffect, useState } from "react";
import { List, Pencil, Plus, Store } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { StoreFormSheet } from "@/components/stores/store-form-sheet";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
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
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function OwnerStoresPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [sheetOpen, setSheetOpen] = useState(false);
  const [editingStore, setEditingStore] = useState<StoreSummary | null>(null);

  useEffect(() => {
    const active = true;

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
  }, [reloadKey]);

  function openEditSheet(store: StoreSummary) {
    setEditingStore(store);
    setSheetOpen(true);
  }

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

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.stores.listTitle")}
          </CardTitle>
          <CardDescription>{t("owner.stores.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
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
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.stores.columns.name")}</TableHead>
                  <TableHead>{t("owner.stores.columns.phone")}</TableHead>
                  <TableHead>{t("owner.stores.columns.status")}</TableHead>
                  <TableHead className="text-end">
                    {t("owner.stores.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {stores.map((store) => (
                  <TableRow key={store.id}>
                    <TableCell className="font-medium">{store.name}</TableCell>
                    <TableCell>{store.phone || "—"}</TableCell>
                    <TableCell>
                      <Badge variant={store.status === 1 ? "default" : "secondary"}>
                        {store.status === 1
                          ? t("owner.stores.statusActive")
                          : t("owner.stores.statusInactive")}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-end">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => openEditSheet(store)}
                      >
                        <Pencil />
                        {t("owner.stores.edit")}
                      </Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>

      <StoreFormSheet
        open={sheetOpen}
        onOpenChange={setSheetOpen}
        store={editingStore}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />
    </div>
  );
}
