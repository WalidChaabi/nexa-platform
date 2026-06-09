"use client";

import { useEffect, useMemo, useState } from "react";
import { List, Receipt, Store as StoreIcon } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { createOwnerInvoiceColumns } from "@/components/invoices/owner-invoices-columns";
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import { fetchInvoices, type InvoiceSummary } from "@/lib/api/invoices";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

const ALL_STORES = "__all__";

export default function OwnerInvoicesPage() {
  const { t, formatCurrency, formatDateTime } = useLocale();
  const [invoices, setInvoices] = useState<InvoiceSummary[]>([]);
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [storeFilter, setStoreFilter] = useState<string>(ALL_STORES);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function loadStores() {
      try {
        const data = await fetchStores();
        if (active) {
          setStores(data);
        }
      } catch {
        if (active) {
          setErrorKey("owner.invoices.loadError");
        }
      }
    }

    void loadStores();

    return () => {
      active = false;
    };
  }, []);

  useEffect(() => {
    let active = true;

    async function loadInvoices() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchInvoices({
          store_id:
            storeFilter === ALL_STORES ? undefined : Number(storeFilter),
        });
        if (active) {
          setInvoices(data);
        }
      } catch {
        if (active) {
          setErrorKey("owner.invoices.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadInvoices();
  }, [storeFilter]);

  const columns = useMemo(
    () =>
      createOwnerInvoiceColumns({
        t,
        formatCurrency,
        formatDateTime,
      }),
    [t, formatCurrency, formatDateTime],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Receipt}
        title={t("owner.invoices.title")}
        description={t("owner.invoices.description")}
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
            <StoreIcon className="size-4 text-primary" />
            {t("owner.invoices.filterTitle")}
          </CardTitle>
          <CardDescription>{t("owner.invoices.filterDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <Select
            value={storeFilter}
            onValueChange={(value) => setStoreFilter(value ?? ALL_STORES)}
          >
            <SelectTrigger className="w-full max-w-sm">
              <SelectValue placeholder={t("owner.invoices.allStores")} />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={ALL_STORES}>
                {t("owner.invoices.allStores")}
              </SelectItem>
              {stores.map((store) => (
                <SelectItem key={store.id} value={String(store.id)}>
                  {store.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.invoices.listTitle")}
          </CardTitle>
          <CardDescription>{t("owner.invoices.listDescription")}</CardDescription>
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
              data={invoices}
              filterColumn="customer_name"
              filterPlaceholder={t("owner.invoices.filterPlaceholder")}
              emptyMessage={t("owner.invoices.empty")}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
