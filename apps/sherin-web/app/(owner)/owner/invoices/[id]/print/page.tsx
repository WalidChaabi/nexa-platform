"use client";

import { useParams } from "next/navigation";
import { useEffect, useState } from "react";
import { ArrowLeft, Printer } from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { NavLink } from "@/components/ui/nav-link";
import {
  fetchInvoiceForPrint,
  type InvoiceSummaryWithStore,
} from "@/lib/api/invoices";

export default function OwnerInvoicePrintPage() {
  const params = useParams<{ id: string }>();
  const { loading } = useDashboardAuth();
  const { t, formatCurrency, formatDateTime } = useLocale();
  const [invoice, setInvoice] = useState<InvoiceSummaryWithStore | null>(null);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (loading) {
      return;
    }

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchInvoiceForPrint(Number(params.id));
        setInvoice(data);
      } catch {
        setErrorKey("owner.invoices.printError");
      } finally {
        setFetching(false);
      }
    }

    void load();
  }, [loading, params.id]);

  if (loading || fetching) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        {t("common.loading")}
      </div>
    );
  }

  if (errorKey || !invoice) {
    return (
      <div className="flex min-h-screen flex-col items-center justify-center gap-4">
        <p className="text-destructive">{t(errorKey ?? "owner.invoices.notFoundError")}</p>
        <NavLink href={`/owner/invoices/${params.id}`} variant="outline">
          <ArrowLeft />
          {t("common.backToList")}
        </NavLink>
      </div>
    );
  }

  const customerName = invoice.customer
    ? [invoice.customer.firstname, invoice.customer.lastname]
        .filter(Boolean)
        .join(" ") || invoice.customer.phone
    : t("owner.invoices.customerFallback", { id: invoice.customer_id });

  return (
    <div className="mx-auto max-w-2xl px-6 py-10 print:px-0 print:py-0">
      <div className="mb-6 flex gap-2 print:hidden">
        <Button type="button" onClick={() => window.print()}>
          <Printer />
          {t("common.print")}
        </Button>
        <NavLink href={`/owner/invoices/${params.id}`} variant="outline">
          <ArrowLeft />
          {t("owner.invoices.backToDetail")}
        </NavLink>
      </div>

      <article className="space-y-6 rounded-lg border p-8 print:border-0 print:p-0">
        <header className="space-y-1 border-b pb-4">
          <h1 className="text-2xl font-semibold">
            {invoice.store?.name ?? t("owner.invoices.printTitle")}
          </h1>
          {invoice.store?.address ? (
            <p className="text-sm text-muted-foreground">{invoice.store.address}</p>
          ) : null}
          {invoice.store?.phone ? (
            <p className="text-sm text-muted-foreground">{invoice.store.phone}</p>
          ) : null}
        </header>

        <section className="grid gap-2 text-sm md:grid-cols-2">
          <div>
            <p className="font-medium">
              {t("owner.invoices.detailTitle", { id: invoice.id })}
            </p>
            <p className="text-muted-foreground">
              {formatDateTime(invoice.invoice_date)}
            </p>
          </div>
          <div>
            <p className="font-medium">{customerName}</p>
            {invoice.customer?.phone ? (
              <p className="text-muted-foreground">{invoice.customer.phone}</p>
            ) : null}
          </div>
        </section>

        <table className="w-full text-sm">
          <thead>
            <tr className="border-b text-left text-muted-foreground">
              <th className="py-2">{t("owner.invoices.columns.product")}</th>
              <th className="py-2">{t("owner.invoices.columns.qty")}</th>
              <th className="py-2">{t("owner.invoices.columns.price")}</th>
              <th className="py-2">{t("owner.invoices.columns.lineTotal")}</th>
            </tr>
          </thead>
          <tbody>
            {invoice.items?.map((item) => (
              <tr key={item.id} className="border-b">
                <td className="py-2">
                  {item.label ??
                    t("owner.invoices.productFallback", {
                      id: item.product_id,
                    })}
                </td>
                <td className="py-2">{item.qte}</td>
                <td className="py-2">{formatCurrency(item.price)}</td>
                <td className="py-2">{formatCurrency(item.line_total)}</td>
              </tr>
            ))}
          </tbody>
        </table>

        <footer className="space-y-1 border-t pt-4 text-sm">
          <p>
            {t("owner.invoices.total")}:{" "}
            <strong>{formatCurrency(invoice.total)}</strong>
          </p>
          <p>
            {t("owner.invoices.paid")}: {formatCurrency(invoice.paid_amount)}
          </p>
          {invoice.store?.invoice_message ? (
            <p className="pt-4 text-muted-foreground">
              {invoice.store.invoice_message}
            </p>
          ) : null}
        </footer>
      </article>
    </div>
  );
}
