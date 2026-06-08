"use client";

import { useRouter } from "next/navigation";
import { useEffect, useMemo, useState } from "react";

import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { NavLink } from "@/components/ui/nav-link";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useRequireAuth } from "@/hooks/use-require-auth";
import { createInvoice } from "@/lib/api/invoices";
import { fetchPosCatalog, type PosProduct } from "@/lib/api/pos";

type LineDraft = {
  productId: string;
  qte: number;
};

function lineTotal(product: PosProduct | undefined, qte: number): number {
  if (!product) {
    return 0;
  }

  return product.price * qte;
}

export default function CreateInvoicePage() {
  const router = useRouter();
  const { user, loading } = useRequireAuth("employer");
  const [catalogLoading, setCatalogLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [customerId, setCustomerId] = useState("");
  const [paymentMethod, setPaymentMethod] = useState("0");
  const [paidAmount, setPaidAmount] = useState("");
  const [lines, setLines] = useState<LineDraft[]>([
    { productId: "", qte: 1 },
  ]);
  const [customers, setCustomers] = useState<
    Awaited<ReturnType<typeof fetchPosCatalog>>["customers"]
  >([]);
  const [products, setProducts] = useState<
    Awaited<ReturnType<typeof fetchPosCatalog>>["products"]
  >([]);

  const storeId = user?.store_id;

  useEffect(() => {
    if (loading || storeId == null) {
      return;
    }

    const activeStoreId = storeId;

    async function loadCatalog() {
      setCatalogLoading(true);
      setError(null);

      try {
        const catalog = await fetchPosCatalog(activeStoreId);
        setCustomers(catalog.customers);
        setProducts(catalog.products);
      } catch {
        setError("Unable to load POS catalog.");
      } finally {
        setCatalogLoading(false);
      }
    }

    void loadCatalog();
  }, [loading, storeId]);

  const total = useMemo(() => {
    return lines.reduce((sum, line) => {
      const product = products.find((p) => String(p.id) === line.productId);
      return sum + lineTotal(product, line.qte);
    }, 0);
  }, [lines, products]);

  const totalHt = useMemo(() => Number((total / 1.2).toFixed(2)), [total]);

  function updateLine(index: number, patch: Partial<LineDraft>) {
    setLines((current) =>
      current.map((line, i) => (i === index ? { ...line, ...patch } : line)),
    );
  }

  function addLine() {
    setLines((current) => [...current, { productId: "", qte: 1 }]);
  }

  function removeLine(index: number) {
    setLines((current) => current.filter((_, i) => i !== index));
  }

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!user?.store_id || !customerId) {
      setError("Select a customer.");
      return;
    }

    const productLines = lines
      .filter((line) => line.productId)
      .map((line) => {
        const product = products.find((p) => String(p.id) === line.productId)!;

        return {
          product_id: product.id,
          qte: line.qte,
          price: product.price,
        };
      });

    if (productLines.length === 0) {
      setError("Add at least one product.");
      return;
    }

    const paid = paidAmount === "" ? total : Number(paidAmount);

    setSubmitting(true);
    setError(null);

    try {
      const invoice = await createInvoice({
        store_id: user.store_id,
        customer_id: Number(customerId),
        payment_method: Number(paymentMethod),
        paid_amount: paid,
        total,
        total_ht: totalHt,
        products: productLines,
      });

      router.push(`/employer/invoices/${invoice.id}`);
    } catch {
      setError("Unable to create invoice.");
      setSubmitting(false);
    }
  }

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        Loading...
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
          <Badge variant="outline">POS</Badge>
          <h1 className="mt-2 text-3xl font-semibold">New invoice</h1>
        </div>
        <NavLink href="/employer/invoices" variant="outline">
          Back to list
        </NavLink>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Create invoice</CardTitle>
        </CardHeader>
        <CardContent>
          {catalogLoading ? (
            <p className="text-sm text-muted-foreground">Loading catalog...</p>
          ) : (
            <form className="space-y-6" onSubmit={(e) => void handleSubmit(e)}>
              {error ? (
                <p className="text-sm text-destructive">{error}</p>
              ) : null}

              <div className="space-y-2">
                <Label htmlFor="customer">Customer</Label>
                <select
                  id="customer"
                  className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm"
                  value={customerId}
                  onChange={(e) => setCustomerId(e.target.value)}
                  required
                >
                  <option value="">Select customer</option>
                  {customers.map((customer) => (
                    <option key={customer.id} value={customer.id}>
                      {customer.display_name}
                    </option>
                  ))}
                </select>
              </div>

              <div className="space-y-3">
                <div className="flex items-center justify-between">
                  <Label>Products</Label>
                  <Button type="button" variant="outline" size="sm" onClick={addLine}>
                    Add line
                  </Button>
                </div>

                {lines.map((line, index) => {
                  const product = products.find(
                    (p) => String(p.id) === line.productId,
                  );

                  return (
                    <div
                      key={index}
                      className="grid gap-3 rounded-lg border p-3 md:grid-cols-[1fr_100px_120px_auto]"
                    >
                      <select
                        className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm"
                        value={line.productId}
                        onChange={(e) =>
                          updateLine(index, { productId: e.target.value })
                        }
                      >
                        <option value="">Select product</option>
                        {products.map((item) => (
                          <option key={item.id} value={item.id}>
                            {item.label} — {item.price} MAD
                          </option>
                        ))}
                      </select>
                      <Input
                        type="number"
                        min={1}
                        value={line.qte}
                        onChange={(e) =>
                          updateLine(index, { qte: Number(e.target.value) || 1 })
                        }
                      />
                      <div className="flex items-center text-sm font-medium">
                        {lineTotal(product, line.qte).toFixed(2)} MAD
                      </div>
                      <Button
                        type="button"
                        variant="ghost"
                        size="sm"
                        disabled={lines.length === 1}
                        onClick={() => removeLine(index)}
                      >
                        Remove
                      </Button>
                    </div>
                  );
                })}
              </div>

              <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                  <Label htmlFor="payment">Payment method</Label>
                  <select
                    id="payment"
                    className="flex h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm"
                    value={paymentMethod}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                  >
                    <option value="0">Cash</option>
                    <option value="1">Check</option>
                    <option value="2">Card</option>
                  </select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="paid">Paid amount (MAD)</Label>
                  <Input
                    id="paid"
                    type="number"
                    min={0}
                    step="0.01"
                    placeholder={total.toFixed(2)}
                    value={paidAmount}
                    onChange={(e) => setPaidAmount(e.target.value)}
                  />
                </div>
              </div>

              <div className="rounded-lg bg-muted p-4 text-sm">
                <p>
                  Total TTC: <strong>{total.toFixed(2)} MAD</strong>
                </p>
                <p className="text-muted-foreground">
                  Total HT: {totalHt.toFixed(2)} MAD
                </p>
              </div>

              <Button type="submit" disabled={submitting}>
                {submitting ? "Saving..." : "Create invoice"}
              </Button>
            </form>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
