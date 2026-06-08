"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { ArrowLeft, List, Printer, Receipt, Store as StoreIcon } from "lucide-react";

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
import { Skeleton } from "@/components/ui/skeleton";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { fetchInvoice, type InvoiceSummary } from "@/lib/api/invoices";

export default function OwnerInvoiceDetailPage() {
  const params = useParams<{ id: string }>();
  const { t, formatCurrency } = useLocale();
  const [invoice, setInvoice] = useState<InvoiceSummary | null>(null);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchInvoice(Number(params.id));
        setInvoice(data);
      } catch {
        setErrorKey("owner.invoices.notFoundError");
      } finally {
        setFetching(false);
      }
    }

    void load();
  }, [params.id]);

  return (
    <div className="mx-auto w-full max-w-3xl space-y-6">
      <PageHeader
        icon={Receipt}
        title={t("owner.invoices.detailTitle", { id: params.id })}
        description={t("owner.invoices.detailDescription")}
      >
        <NavLink
          href={`/owner/invoices/${params.id}/print`}
          variant="outline"
        >
          <Printer />
          {t("common.print")}
        </NavLink>
        <NavLink href="/owner/invoices" variant="outline">
          <ArrowLeft />
          {t("common.backToList")}
        </NavLink>
      </PageHeader>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.invoices.detailCardTitle")}
          </CardTitle>
          <CardDescription>{t("owner.invoices.detailCardDescription")}</CardDescription>
        </CardHeader>
        <CardContent className="space-y-4 text-sm">
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-6 w-48" />
              <Skeleton className="h-24 w-full" />
            </div>
          ) : errorKey ? (
            <Alert variant="destructive">
              <AlertTitle>{t("owner.invoices.notFound")}</AlertTitle>
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : invoice ? (
            <>
              <div className="flex flex-wrap items-center gap-3 rounded-xl border bg-muted/30 p-4">
                <Badge>{invoice.status_label}</Badge>
                {invoice.store ? (
                  <span className="inline-flex items-center gap-1 text-muted-foreground">
                    <StoreIcon className="size-3.5" />
                    {invoice.store.name}
                  </span>
                ) : null}
                {invoice.employer ? (
                  <span className="text-muted-foreground">
                    {t("owner.invoices.employer", {
                      name: invoice.employer.full_name,
                    })}
                  </span>
                ) : null}
                <span className="text-muted-foreground">
                  {t("owner.invoices.paid")}: {formatCurrency(invoice.paid_amount)}
                </span>
                <span className="font-semibold">
                  {t("owner.invoices.total")}: {formatCurrency(invoice.total)}
                </span>
              </div>

              {invoice.items && invoice.items.length > 0 ? (
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>{t("owner.invoices.columns.product")}</TableHead>
                      <TableHead>{t("owner.invoices.columns.qty")}</TableHead>
                      <TableHead>{t("owner.invoices.columns.price")}</TableHead>
                      <TableHead className="text-end">
                        {t("owner.invoices.columns.lineTotal")}
                      </TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {invoice.items.map((item) => (
                      <TableRow key={item.id}>
                        <TableCell>
                          {item.label ??
                            t("owner.invoices.productFallback", {
                              id: item.product_id,
                            })}
                        </TableCell>
                        <TableCell>{item.qte}</TableCell>
                        <TableCell>{formatCurrency(item.price)}</TableCell>
                        <TableCell className="text-end">
                          {formatCurrency(item.line_total)}
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              ) : (
                <p className="text-muted-foreground">
                  {t("owner.invoices.noItems")}
                </p>
              )}
            </>
          ) : null}
        </CardContent>
      </Card>
    </div>
  );
}
