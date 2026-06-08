"use client";

import { useEffect, useState } from "react";

import { LogoutButton } from "@/components/auth/logout-button";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";
import { useRequireAuth } from "@/hooks/use-require-auth";
import {
  cancelInvoice,
  fetchInvoices,
  type InvoiceSummary,
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

function customerName(invoice: InvoiceSummary): string {
  const customer = invoice.customer;
  if (!customer) {
    return `Client #${invoice.customer_id}`;
  }

  const name = [customer.firstname, customer.lastname].filter(Boolean).join(" ");
  return name || customer.phone;
}

export default function EmployerInvoicesPage() {
  const { user, loading } = useRequireAuth("employer");
  const [invoices, setInvoices] = useState<InvoiceSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [cancellingId, setCancellingId] = useState<number | null>(null);
  const [reloadKey, setReloadKey] = useState(0);

  const storeId = user?.store_id;

  useEffect(() => {
    if (loading || storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    let active = true;

    async function loadInvoices() {
      setFetching(true);
      setError(null);

      try {
        const data = await fetchInvoices({ store_id: activeStoreId });
        if (active) {
          setInvoices(data);
        }
      } catch {
        if (active) {
          setError("Unable to load invoices.");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadInvoices();

    return () => {
      active = false;
    };
  }, [loading, storeId, reloadKey]);

  async function handleCancel(invoiceId: number) {
    setCancellingId(invoiceId);
    setError(null);

    try {
      await cancelInvoice(invoiceId);
      setReloadKey((value) => value + 1);
    } catch {
      setError("Unable to cancel this invoice.");
    } finally {
      setCancellingId(null);
    }
  }

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        Loading invoices...
      </div>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <div className="mx-auto flex min-h-screen max-w-5xl flex-col gap-6 px-6 py-12">
      <div className="flex items-center justify-between">
        <div>
          <Badge variant="outline">Employer</Badge>
          <h1 className="mt-2 text-3xl font-semibold">Invoices</h1>
          <p className="text-muted-foreground">
            Store #{user.store_id ?? "—"} · {user.full_name}
          </p>
        </div>
        <div className="flex items-center gap-2">
          <NavLink href="/employer/invoices/create">New invoice</NavLink>
          <NavLink href="/employer" variant="outline">
            Dashboard
          </NavLink>
          <LogoutButton />
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Recent invoices</CardTitle>
        </CardHeader>
        <CardContent className="space-y-4">
          {error ? <p className="text-sm text-destructive">{error}</p> : null}

          {!storeId ? (
            <p className="text-sm text-muted-foreground">
              No store assigned to this employer account.
            </p>
          ) : fetching ? (
            <p className="text-sm text-muted-foreground">Loading...</p>
          ) : invoices.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              No invoices yet for this store.
            </p>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="border-b text-muted-foreground">
                  <tr>
                    <th className="py-2 pr-4 font-medium">#</th>
                    <th className="py-2 pr-4 font-medium">Date</th>
                    <th className="py-2 pr-4 font-medium">Customer</th>
                    <th className="py-2 pr-4 font-medium">Total</th>
                    <th className="py-2 pr-4 font-medium">Status</th>
                    <th className="py-2 font-medium">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {invoices.map((invoice) => (
                    <tr key={invoice.id} className="border-b last:border-0">
                      <td className="py-3 pr-4">{invoice.id}</td>
                      <td className="py-3 pr-4">
                        {formatDate(invoice.invoice_date)}
                      </td>
                      <td className="py-3 pr-4">{customerName(invoice)}</td>
                      <td className="py-3 pr-4">{formatAmount(invoice.total)}</td>
                      <td className="py-3 pr-4">
                        <Badge
                          variant={
                            invoice.status_label === "pending"
                              ? "default"
                              : "secondary"
                          }
                        >
                          {invoice.status_label}
                        </Badge>
                      </td>
                      <td className="py-3">
                        <div className="flex gap-2">
                          <NavLink
                            href={`/employer/invoices/${invoice.id}`}
                            variant="outline"
                            size="sm"
                          >
                            View
                          </NavLink>
                          {invoice.status_label === "pending" ? (
                            <Button
                              variant="destructive"
                              size="sm"
                              disabled={cancellingId === invoice.id}
                              onClick={() => void handleCancel(invoice.id)}
                            >
                              {cancellingId === invoice.id
                                ? "Cancelling..."
                                : "Cancel"}
                            </Button>
                          ) : null}
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
