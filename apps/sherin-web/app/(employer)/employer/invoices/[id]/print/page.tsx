"use client";

import { useParams } from "next/navigation";
import { useEffect, useState } from "react";

import { Button } from "@/components/ui/button";
import { NavLink } from "@/components/ui/nav-link";
import { useRequireAuth } from "@/hooks/use-require-auth";
import {
  fetchInvoiceForPrint,
  type InvoiceSummaryWithStore,
} from "@/lib/api/invoices";

function formatAmount(value: number): string {
  return new Intl.NumberFormat("fr-MA", {
    style: "currency",
    currency: "MAD",
  }).format(value);
}

function formatDate(value: string): string {
  return new Intl.DateTimeFormat("fr-MA", {
    dateStyle: "medium",
    timeStyle: "short",
  }).format(new Date(value));
}

export default function InvoicePrintPage() {
  const params = useParams<{ id: string }>();
  const { user, loading } = useRequireAuth("employer");
  const [invoice, setInvoice] = useState<InvoiceSummaryWithStore | null>(null);
  const [fetching, setFetching] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (loading || !user) {
      return;
    }

    async function load() {
      setFetching(true);
      setError(null);

      try {
        const data = await fetchInvoiceForPrint(Number(params.id));
        setInvoice(data);
      } catch {
        setError("Unable to load invoice for printing.");
      } finally {
        setFetching(false);
      }
    }

    void load();
  }, [loading, user, params.id]);

  if (loading || fetching) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        Loading print view...
      </div>
    );
  }

  if (!user || error || !invoice) {
    return (
      <div className="flex min-h-screen flex-col items-center justify-center gap-4">
        <p className="text-destructive">{error ?? "Invoice not found."}</p>
        <NavLink href={`/employer/invoices/${params.id}`} variant="outline">
          Back
        </NavLink>
      </div>
    );
  }

  const customerName = invoice.customer
    ? [invoice.customer.firstname, invoice.customer.lastname]
        .filter(Boolean)
        .join(" ") || invoice.customer.phone
    : `Client #${invoice.customer_id}`;

  return (
    <div className="mx-auto max-w-2xl px-6 py-10 print:px-0 print:py-0">
      <div className="mb-6 flex gap-2 print:hidden">
        <Button onClick={() => window.print()}>Print</Button>
        <NavLink href={`/employer/invoices/${params.id}`} variant="outline">
          Back to invoice
        </NavLink>
      </div>

      <article className="space-y-6 rounded-lg border p-8 print:border-0 print:p-0">
        <header className="space-y-1 border-b pb-4">
          <h1 className="text-2xl font-semibold">
            {invoice.store?.name ?? "Invoice"}
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
            <p className="font-medium">Invoice #{invoice.id}</p>
            <p className="text-muted-foreground">
              {formatDate(invoice.invoice_date)}
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
              <th className="py-2">Product</th>
              <th className="py-2">Qty</th>
              <th className="py-2">Price</th>
              <th className="py-2">Total</th>
            </tr>
          </thead>
          <tbody>
            {invoice.items?.map((item) => (
              <tr key={item.id} className="border-b">
                <td className="py-2">{item.label ?? `Product #${item.product_id}`}</td>
                <td className="py-2">{item.qte}</td>
                <td className="py-2">{formatAmount(item.price)}</td>
                <td className="py-2">{formatAmount(item.line_total)}</td>
              </tr>
            ))}
          </tbody>
        </table>

        <footer className="space-y-1 border-t pt-4 text-sm">
          <p>
            Total: <strong>{formatAmount(invoice.total)}</strong>
          </p>
          <p>Paid: {formatAmount(invoice.paid_amount)}</p>
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
