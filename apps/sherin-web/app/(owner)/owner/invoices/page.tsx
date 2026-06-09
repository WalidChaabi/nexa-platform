"use client";

import { useEffect, useState } from "react";
import { Eye, List, Receipt, Store as StoreIcon } from "lucide-react";

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
import { NavLink } from "@/components/ui/nav-link";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
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
    const active = true;

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

  function customerName(invoice: InvoiceSummary): string {
    const customer = invoice.customer;
    if (!customer) {
      return t("owner.invoices.customerFallback", { id: invoice.customer_id });
    }

    const name = [customer.firstname, customer.lastname]
      .filter(Boolean)
      .join(" ");
    return name || customer.phone;
  }

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
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : invoices.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.invoices.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.invoices.columns.id")}</TableHead>
                  <TableHead>{t("owner.invoices.columns.date")}</TableHead>
                  <TableHead>{t("owner.invoices.columns.store")}</TableHead>
                  <TableHead>{t("owner.invoices.columns.customer")}</TableHead>
                  <TableHead>{t("owner.invoices.columns.total")}</TableHead>
                  <TableHead>{t("owner.invoices.columns.status")}</TableHead>
                  <TableHead className="text-end">
                    {t("owner.invoices.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {invoices.map((invoice) => (
                  <TableRow key={invoice.id}>
                    <TableCell className="font-medium">{invoice.id}</TableCell>
                    <TableCell>{formatDateTime(invoice.invoice_date)}</TableCell>
                    <TableCell>
                      {invoice.store?.name ?? `#${invoice.store_id}`}
                    </TableCell>
                    <TableCell>{customerName(invoice)}</TableCell>
                    <TableCell>{formatCurrency(invoice.total)}</TableCell>
                    <TableCell>
                      <Badge
                        variant={
                          invoice.status_label === "pending"
                            ? "default"
                            : "secondary"
                        }
                      >
                        {invoice.status_label}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-end">
                      <NavLink
                        href={`/owner/invoices/${invoice.id}`}
                        variant="outline"
                        size="sm"
                      >
                        <Eye />
                        {t("common.view")}
                      </NavLink>
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
