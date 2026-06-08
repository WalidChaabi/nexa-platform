"use client";

import { useEffect, useState } from "react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
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

export default function AdminStoresPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

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
          <CardTitle>{t("admin.stores.listTitle")}</CardTitle>
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
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.stores.columns.name")}</TableHead>
                  <TableHead>{t("admin.stores.columns.owner")}</TableHead>
                  <TableHead>{t("admin.stores.columns.phone")}</TableHead>
                  <TableHead>{t("admin.stores.columns.status")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {stores.map((store) => (
                  <TableRow key={store.id}>
                    <TableCell className="font-medium">{store.name}</TableCell>
                    <TableCell>
                      {store.owner?.full_name ?? `#${store.user_id}`}
                    </TableCell>
                    <TableCell>{store.phone || "—"}</TableCell>
                    <TableCell>
                      <Badge variant={store.status === 1 ? "default" : "secondary"}>
                        {store.status === 1
                          ? t("admin.stores.statusActive")
                          : t("admin.stores.statusInactive")}
                      </Badge>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
