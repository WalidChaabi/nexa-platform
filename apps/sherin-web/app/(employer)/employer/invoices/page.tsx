"use client";

import { useEffect, useState } from "react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
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
import {
  cancelInvoice,
  fetchInvoices,
  type InvoiceSummary,
} from "@/lib/api/invoices";

export default function EmployerInvoicesPage() {
  const { user } = useDashboardAuth();
  const { t, formatCurrency, formatDateTime } = useLocale();
  const [invoices, setInvoices] = useState<InvoiceSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [cancellingId, setCancellingId] = useState<number | null>(null);
  const [reloadKey, setReloadKey] = useState(0);

  const storeId = user.store_id;

  function customerName(invoice: InvoiceSummary): string {
    const customer = invoice.customer;
    if (!customer) {
      return t("employer.invoices.customerFallback", {
        id: invoice.customer_id,
      });
    }

    const name = [customer.firstname, customer.lastname]
      .filter(Boolean)
      .join(" ");
    return name || customer.phone;
  }

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    let active = true;

    async function loadInvoices() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchInvoices({ store_id: activeStoreId });
        if (active) {
          setInvoices(data);
        }
      } catch {
        if (active) {
          setErrorKey("employer.invoices.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadInvoices();
  }, [storeId, reloadKey]);

  async function handleCancel(invoiceId: number) {
    setCancellingId(invoiceId);
    setErrorKey(null);

    try {
      await cancelInvoice(invoiceId);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.invoices.cancelError");
    } finally {
      setCancellingId(null);
    }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        title={t("employer.invoices.title")}
        description={
          storeId
            ? t("employer.invoices.storeContext", {
                id: storeId,
                name: user.full_name,
              })
            : t("common.noStore")
        }
      >
        <NavLink href="/employer/invoices/create">{t("nav.newInvoice")}</NavLink>
      </PageHeader>

      <Card>
        <CardHeader>
          <CardTitle>{t("employer.invoices.recentTitle")}</CardTitle>
          <CardDescription>
            {t("employer.invoices.recentDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          {errorKey ? (
            <Alert variant="destructive">
              <AlertTitle>{t("common.error")}</AlertTitle>
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          {!storeId ? (
            <p className="text-sm text-muted-foreground">{t("common.noStore")}</p>
          ) : fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : invoices.length === 0 ? (
            <div className="flex flex-col items-center gap-3 py-8 text-center">
              <p className="text-sm text-muted-foreground">
                {t("employer.invoices.empty")}
              </p>
              <NavLink href="/employer/invoices/create">
                {t("employer.invoices.createFirst")}
              </NavLink>
            </div>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("employer.invoices.columns.id")}</TableHead>
                  <TableHead>{t("employer.invoices.columns.date")}</TableHead>
                  <TableHead>{t("employer.invoices.columns.customer")}</TableHead>
                  <TableHead>{t("employer.invoices.columns.total")}</TableHead>
                  <TableHead>{t("employer.invoices.columns.status")}</TableHead>
                  <TableHead className="text-right">
                    {t("employer.invoices.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {invoices.map((invoice) => (
                  <TableRow key={invoice.id}>
                    <TableCell className="font-medium">{invoice.id}</TableCell>
                    <TableCell>{formatDateTime(invoice.invoice_date)}</TableCell>
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
                    <TableCell className="text-right">
                      <div className="flex justify-end gap-2">
                        <NavLink
                          href={`/employer/invoices/${invoice.id}`}
                          variant="outline"
                          size="sm"
                        >
                          {t("common.view")}
                        </NavLink>
                        {invoice.status_label === "pending" ? (
                          <Button
                            variant="destructive"
                            size="sm"
                            disabled={cancellingId === invoice.id}
                            onClick={() => void handleCancel(invoice.id)}
                          >
                            {cancellingId === invoice.id
                              ? t("common.cancelling")
                              : t("common.cancel")}
                          </Button>
                        ) : null}
                      </div>
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
