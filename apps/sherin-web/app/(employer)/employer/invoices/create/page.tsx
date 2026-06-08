"use client";

import { useRouter } from "next/navigation";
import { useEffect, useMemo, useState } from "react";
import { ArrowLeft, Plus, PlusCircle, Trash2 } from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { NavLink } from "@/components/ui/nav-link";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Separator } from "@/components/ui/separator";
import { Skeleton } from "@/components/ui/skeleton";
import { createInvoice } from "@/lib/api/invoices";
import { fetchPosCatalog, type PosProduct } from "@/lib/api/pos";

const EMPTY_PRODUCT = "__none__";

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
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [catalogLoading, setCatalogLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);
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

  const storeId = user.store_id;

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;

    async function loadCatalog() {
      setCatalogLoading(true);
      setErrorKey(null);

      try {
        const catalog = await fetchPosCatalog(activeStoreId);
        setCustomers(catalog.customers);
        setProducts(catalog.products);
      } catch {
        setErrorKey("employer.create.catalogError");
      } finally {
        setCatalogLoading(false);
      }
    }

    void loadCatalog();
  }, [storeId]);

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

    if (!user.store_id || !customerId) {
      setErrorKey("employer.create.selectCustomer");
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
      setErrorKey("employer.create.addProduct");
      return;
    }

    const paid = paidAmount === "" ? total : Number(paidAmount);

    setSubmitting(true);
    setErrorKey(null);

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
      setErrorKey("employer.create.createError");
      setSubmitting(false);
    }
  }

  return (
    <div className="mx-auto w-full max-w-3xl space-y-6">
      <PageHeader
        icon={PlusCircle}
        title={t("employer.create.title")}
        description={t("employer.create.description")}
      >
        <NavLink href="/employer/invoices" variant="outline">
          <ArrowLeft />
          {t("common.backToList")}
        </NavLink>
      </PageHeader>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <PlusCircle className="size-4 text-primary" />
            {t("employer.create.cardTitle")}
          </CardTitle>
          <CardDescription>{t("employer.create.cardDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {catalogLoading ? (
            <div className="space-y-4">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-24 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : (
            <form className="space-y-6" onSubmit={(e) => void handleSubmit(e)}>
              {errorKey ? (
                <Alert variant="destructive">
                  <AlertTitle>{t("employer.create.saveError")}</AlertTitle>
                  <AlertDescription>{t(errorKey)}</AlertDescription>
                </Alert>
              ) : null}

              <div className="space-y-2">
                <Label>{t("employer.create.customer")}</Label>
                <Select
                  value={customerId}
                  onValueChange={(value) => setCustomerId(value ?? "")}
                >
                  <SelectTrigger className="w-full">
                    <SelectValue
                      placeholder={t("employer.create.selectCustomerPlaceholder")}
                    />
                  </SelectTrigger>
                  <SelectContent>
                    {customers.map((customer) => (
                      <SelectItem key={customer.id} value={String(customer.id)}>
                        {customer.display_name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-3">
                <div className="flex items-center justify-between">
                  <Label>{t("employer.create.products")}</Label>
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    onClick={addLine}
                  >
                    <Plus />
                    {t("employer.create.addLine")}
                  </Button>
                </div>

                {lines.map((line, index) => {
                  const product = products.find(
                    (p) => String(p.id) === line.productId,
                  );
                  const selectValue = line.productId || EMPTY_PRODUCT;

                  return (
                    <div
                      key={index}
                      className="grid gap-3 rounded-xl border bg-muted/20 p-4 md:grid-cols-[1fr_100px_120px_auto]"
                    >
                      <Select
                        value={selectValue}
                        onValueChange={(value) =>
                          updateLine(index, {
                            productId:
                              !value || value === EMPTY_PRODUCT ? "" : value,
                          })
                        }
                      >
                        <SelectTrigger className="w-full">
                          <SelectValue
                            placeholder={t("employer.create.selectProduct")}
                          />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value={EMPTY_PRODUCT}>
                            {t("employer.create.selectProduct")}
                          </SelectItem>
                          {products.map((item) => (
                            <SelectItem key={item.id} value={String(item.id)}>
                              {item.label} — {item.price} MAD
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                      <Input
                        type="number"
                        min={1}
                        value={line.qte}
                        onChange={(e) =>
                          updateLine(index, {
                            qte: Number(e.target.value) || 1,
                          })
                        }
                      />
                      <div className="flex items-center text-sm font-medium">
                        {lineTotal(product, line.qte).toFixed(2)} MAD
                      </div>
                      <Button
                        type="button"
                        variant="ghost"
                        size="icon-sm"
                        disabled={lines.length === 1}
                        onClick={() => removeLine(index)}
                        aria-label={t("employer.create.removeLine")}
                      >
                        <Trash2 />
                      </Button>
                    </div>
                  );
                })}
              </div>

              <Separator />

              <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                  <Label>{t("employer.create.paymentMethod")}</Label>
                  <Select
                    value={paymentMethod}
                    onValueChange={(value) => setPaymentMethod(value ?? "0")}
                  >
                    <SelectTrigger className="w-full">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="0">
                        {t("employer.create.payment.cash")}
                      </SelectItem>
                      <SelectItem value="1">
                        {t("employer.create.payment.check")}
                      </SelectItem>
                      <SelectItem value="2">
                        {t("employer.create.payment.card")}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="paid">{t("employer.create.paidAmount")}</Label>
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

              <div className="rounded-xl border bg-muted/40 p-4 text-sm">
                <p>
                  {t("employer.create.totalTtc")}:{" "}
                  <strong className="text-foreground">
                    {total.toFixed(2)} MAD
                  </strong>
                </p>
                <p className="text-muted-foreground">
                  {t("employer.create.totalHt")}: {totalHt.toFixed(2)} MAD
                </p>
              </div>

              <Button type="submit" disabled={submitting} className="w-full sm:w-auto">
                {submitting ? t("employer.create.saving") : t("employer.create.submit")}
              </Button>
            </form>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
