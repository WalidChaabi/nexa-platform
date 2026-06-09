"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { ArrowLeft, List, Printer, Receipt } from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
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

export default function EmployerInvoiceDetailPage() {
  const params = useParams<{ id: string }>();
  useDashboardAuth();
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
        setErrorKey("employer.detail.notFoundError");
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
        title={t("employer.detail.title", { id: params.id })}
        description={t("employer.detail.description")}
      >
        <NavLink
          href={`/employer/invoices/${params.id}/print`}
          variant="outline"
        >
          <Printer />
          {t("common.print")}
        </NavLink>
        <NavLink href="/employer/invoices" variant="outline">
          <ArrowLeft />
          {t("common.backToList")}
        </NavLink>
      </PageHeader>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("employer.detail.cardTitle")}
          </CardTitle>
          <CardDescription>{t("employer.detail.cardDescription")}</CardDescription>
        </CardHeader>
        <CardContent className="space-y-4 text-sm">
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-6 w-48" />
              <Skeleton className="h-24 w-full" />
            </div>
          ) : errorKey ? (
            <Alert variant="destructive">
              <AlertTitle>{t("employer.detail.notFound")}</AlertTitle>
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : invoice ? (
            <>
              <div className="flex flex-wrap items-center gap-3 rounded-xl border bg-muted/30 p-4">
                <Badge>{invoice.status_label}</Badge>
                <span className="text-muted-foreground">
                  {t("employer.detail.paid")}: {formatCurrency(invoice.paid_amount)}
                </span>
                <span className="font-semibold">
                  {t("employer.detail.total")}: {formatCurrency(invoice.total)}
                </span>
              </div>

              {invoice.items && invoice.items.length > 0 ? (
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>{t("employer.detail.columns.product")}</TableHead>
                      <TableHead>{t("employer.detail.columns.qty")}</TableHead>
                      <TableHead>{t("employer.detail.columns.price")}</TableHead>
                      <TableHead className="text-right">
                        {t("employer.detail.columns.lineTotal")}
                      </TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {invoice.items.map((item) => (
                      <TableRow key={item.id}>
                        <TableCell>
                          {item.label ??
                            t("employer.detail.productFallback", {
                              id: item.product_id,
                            })}
                        </TableCell>
                        <TableCell>{item.qte}</TableCell>
                        <TableCell>{formatCurrency(item.price)}</TableCell>
                        <TableCell className="text-right">
                          {formatCurrency(item.line_total)}
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              ) : (
                <p className="text-muted-foreground">{t("employer.detail.noItems")}</p>
              )}
            </>
          ) : null}
        </CardContent>
      </Card>
    </div>
  );
}
