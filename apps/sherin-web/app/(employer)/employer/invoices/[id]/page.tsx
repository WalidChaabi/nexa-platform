"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { useParams } from "next/navigation";

import { LogoutButton } from "@/components/auth/logout-button";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { useRequireAuth } from "@/hooks/use-require-auth";
import { fetchInvoice, type InvoiceSummary } from "@/lib/api/invoices";

function formatAmount(value: number): string {
  return new Intl.NumberFormat("fr-MA", {
    style: "currency",
    currency: "MAD",
  }).format(value);
}

export default function EmployerInvoiceDetailPage() {
  const params = useParams<{ id: string }>();
  const { user, loading } = useRequireAuth("employer");
  const [invoice, setInvoice] = useState<InvoiceSummary | null>(null);
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
        const data = await fetchInvoice(Number(params.id));
        setInvoice(data);
      } catch {
        setError("Invoice not found or access denied.");
      } finally {
        setFetching(false);
      }
    }

    void load();
  }, [loading, user, params.id]);

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        Loading invoice...
      </div>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <div className="mx-auto flex min-h-screen max-w-3xl flex-col gap-6 px-6 py-12">
      <div className="flex items-center justify-between">
        <div>
          <Badge variant="outline">Employer</Badge>
          <h1 className="mt-2 text-3xl font-semibold">
            Invoice #{params.id}
          </h1>
        </div>
        <div className="flex items-center gap-2">
          <Button variant="outline" asChild>
            <Link href="/employer/invoices">Back to list</Link>
          </Button>
          <LogoutButton />
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Details</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4 text-sm">
          {fetching ? (
            <p className="text-muted-foreground">Loading...</p>
          ) : error ? (
            <p className="text-destructive">{error}</p>
          ) : invoice ? (
            <>
              <div className="flex flex-wrap gap-3">
                <Badge>{invoice.status_label}</Badge>
                <span className="text-muted-foreground">
                  Paid: {formatAmount(invoice.paid_amount)}
                </span>
                <span className="font-medium">
                  Total: {formatAmount(invoice.total)}
                </span>
              </div>

              {invoice.items && invoice.items.length > 0 ? (
                <div className="overflow-x-auto">
                  <table className="w-full text-left">
                    <thead className="border-b text-muted-foreground">
                      <tr>
                        <th className="py-2 pr-4 font-medium">Product</th>
                        <th className="py-2 pr-4 font-medium">Qty</th>
                        <th className="py-2 pr-4 font-medium">Price</th>
                        <th className="py-2 font-medium">Line total</th>
                      </tr>
                    </thead>
                    <tbody>
                      {invoice.items.map((item) => (
                        <tr key={item.id} className="border-b last:border-0">
                          <td className="py-2 pr-4">
                            {item.label ?? `Product #${item.product_id}`}
                          </td>
                          <td className="py-2 pr-4">{item.qte}</td>
                          <td className="py-2 pr-4">
                            {formatAmount(item.price)}
                          </td>
                          <td className="py-2">
                            {formatAmount(item.line_total)}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <p className="text-muted-foreground">No line items.</p>
              )}
            </>
          ) : null}
        </CardContent>
      </Card>
    </div>
  );
}
