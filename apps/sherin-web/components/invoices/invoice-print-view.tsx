"use client";

import { ArrowLeft, Printer } from "lucide-react";

import { BrandLogo } from "@/components/brand/brand-logo";
import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { NavLink } from "@/components/ui/nav-link";
import { brand } from "@/lib/brand";
import type { InvoiceSummaryWithStore } from "@/lib/api/invoices";

type InvoicePrintViewProps = {
  invoice: InvoiceSummaryWithStore;
  backHref: string;
};

export function InvoicePrintView({ invoice, backHref }: InvoicePrintViewProps) {
  const { t, formatCurrency, formatDateTime } = useLocale();

  const customerName = invoice.customer
    ? [invoice.customer.firstname, invoice.customer.lastname]
        .filter(Boolean)
        .join(" ") || invoice.customer.phone
    : t("invoicePrint.customerFallback", { id: invoice.customer_id });

  return (
    <div className="mx-auto max-w-2xl px-6 py-10 print:px-0 print:py-0">
      <div className="mb-6 flex gap-2 print:hidden">
        <Button type="button" onClick={() => window.print()}>
          <Printer />
          {t("common.print")}
        </Button>
        <NavLink href={backHref} variant="outline">
          <ArrowLeft />
          {t("invoicePrint.backToDetail")}
        </NavLink>
      </div>

      <article className="space-y-6 rounded-lg border p-8 print:border-0 print:p-0">
        <header className="flex flex-col items-center gap-3 border-b pb-4 text-center">
          <div className="size-16 shrink-0">
            <BrandLogo />
          </div>
          <div className="space-y-1">
            <h1 className="text-2xl font-semibold">
              {invoice.store?.name ?? t("invoicePrint.title")}
            </h1>
            {invoice.store?.address ? (
              <p className="text-sm text-muted-foreground">
                {invoice.store.address}
              </p>
            ) : null}
            {invoice.store?.phone ? (
              <p className="text-sm text-muted-foreground">
                {invoice.store.phone}
              </p>
            ) : null}
          </div>
        </header>

        <section className="grid gap-2 text-sm md:grid-cols-2">
          <div>
            <p className="font-medium">
              {t("invoicePrint.detailTitle", { id: invoice.id })}
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
              <th className="py-2">{t("invoicePrint.columns.product")}</th>
              <th className="py-2">{t("invoicePrint.columns.qty")}</th>
              <th className="py-2">{t("invoicePrint.columns.price")}</th>
              <th className="py-2">{t("invoicePrint.columns.lineTotal")}</th>
            </tr>
          </thead>
          <tbody>
            {invoice.items?.map((item) => (
              <tr key={item.id} className="border-b">
                <td className="py-2">
                  {item.label ??
                    t("invoicePrint.productFallback", {
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
          {invoice.total_ht != null ? (
            <p>
              {t("invoicePrint.totalHt")}:{" "}
              {formatCurrency(invoice.total_ht)}
            </p>
          ) : null}
          <p>
            {t("invoicePrint.total")}:{" "}
            <strong>{formatCurrency(invoice.total)}</strong>
          </p>
          <p>
            {t("invoicePrint.paid")}: {formatCurrency(invoice.paid_amount)}
          </p>
          {invoice.store?.invoice_message ? (
            <p className="pt-4 font-medium text-muted-foreground">
              {invoice.store.invoice_message}
            </p>
          ) : null}
          <div className="pt-6 text-center text-xs text-muted-foreground print:pt-4">
            <p className="font-medium">{t("invoicePrint.storeInfo")}</p>
            {invoice.store?.name ? (
              <p>{invoice.store.name}</p>
            ) : null}
            {invoice.store?.address ? <p>{invoice.store.address}</p> : null}
            {invoice.store?.phone ? <p>{invoice.store.phone}</p> : null}
            <p className="pt-2">
              {t("invoicePrint.poweredBy", { brand: brand.name })}
            </p>
          </div>
        </footer>
      </article>
    </div>
  );
}
