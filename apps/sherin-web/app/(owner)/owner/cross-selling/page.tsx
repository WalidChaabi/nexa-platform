"use client";

import { useEffect, useState } from "react";
import { History, List, Plus, RefreshCw, Trash2 } from "lucide-react";

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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
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
  createCrossProduct,
  deleteCrossProduct,
  fetchCrossProducts,
  fetchSmsCrossSelling,
  type CrossProductRule,
  type SmsCrossSellingRecord,
} from "@/lib/api/cross-selling";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";
import { onSelectValue } from "@/lib/utils";

export default function OwnerCrossSellingPage() {
  const { t } = useLocale();
  const [rules, setRules] = useState<CrossProductRule[]>([]);
  const [smsHistory, setSmsHistory] = useState<SmsCrossSellingRecord[]>([]);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [creating, setCreating] = useState(false);
  const [productId, setProductId] = useState("");
  const [message, setMessage] = useState("");
  const [days, setDays] = useState("20");

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [ruleData, historyData, productData] = await Promise.all([
          fetchCrossProducts(),
          fetchSmsCrossSelling(),
          fetchProducts(),
        ]);

        if (active) {
          setRules(ruleData);
          setSmsHistory(historyData);
          setProducts(productData);
        }
      } catch {
        if (active) {
          setErrorKey("owner.crossSelling.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();
  }, [reloadKey]);

  const usedProductIds = new Set(rules.map((rule) => rule.product_id));
  const availableProducts = products.filter(
    (product) => !usedProductIds.has(product.id),
  );

  async function handleCreate(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!productId || !message.trim()) {
      return;
    }

    setCreating(true);
    setErrorKey(null);

    try {
      await createCrossProduct({
        product_id: Number(productId),
        message: message.trim(),
        days: Number(days),
      });
      setProductId("");
      setMessage("");
      setDays("20");
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.crossSelling.createError");
    } finally {
      setCreating(false);
    }
  }

  async function handleDelete(id: number) {
    setErrorKey(null);

    try {
      await deleteCrossProduct(id);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.crossSelling.deleteError");
    }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={RefreshCw}
        title={t("owner.crossSelling.title")}
        description={t("owner.crossSelling.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Plus className="size-4 text-primary" />
            {t("owner.crossSelling.createTitle")}
          </CardTitle>
          <CardDescription>{t("owner.crossSelling.createDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 md:grid-cols-2" onSubmit={handleCreate}>
            <div className="space-y-2">
              <Label htmlFor="cross-product">{t("owner.crossSelling.fields.product")}</Label>
              <Select
                value={productId}
                onValueChange={onSelectValue(setProductId)}
              >
                <SelectTrigger id="cross-product">
                  <SelectValue placeholder={t("owner.crossSelling.selectProduct")} />
                </SelectTrigger>
                <SelectContent>
                  {availableProducts.map((product) => (
                    <SelectItem key={product.id} value={String(product.id)}>
                      {product.label}
                      {product.store?.name ? ` — ${product.store.name}` : ""}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="cross-days">{t("owner.crossSelling.fields.days")}</Label>
              <Input
                id="cross-days"
                type="number"
                min={1}
                max={365}
                value={days}
                onChange={(event) => setDays(event.target.value)}
              />
            </div>

            <div className="space-y-2 md:col-span-2">
              <Label htmlFor="cross-message">{t("owner.crossSelling.fields.message")}</Label>
              <Input
                id="cross-message"
                value={message}
                onChange={(event) => setMessage(event.target.value)}
                placeholder={t("owner.crossSelling.messagePlaceholder")}
              />
            </div>

            <div className="md:col-span-2">
              <Button
                type="submit"
                disabled={creating || !productId || !message.trim()}
              >
                {creating ? t("owner.crossSelling.creating") : t("owner.crossSelling.create")}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.crossSelling.listTitle")}
          </CardTitle>
          <CardDescription>{t("owner.crossSelling.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : rules.length === 0 ? (
            <p className="text-sm text-muted-foreground">{t("owner.crossSelling.empty")}</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.crossSelling.columns.product")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.store")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.days")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.message")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.actions")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {rules.map((rule) => (
                  <TableRow key={rule.id}>
                    <TableCell>{rule.product?.label ?? rule.product_id}</TableCell>
                    <TableCell>{rule.product?.store?.name ?? "—"}</TableCell>
                    <TableCell>{rule.days}</TableCell>
                    <TableCell className="max-w-xs truncate">{rule.message}</TableCell>
                    <TableCell>
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => void handleDelete(rule.id)}
                      >
                        <Trash2 className="size-4" />
                      </Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <History className="size-4 text-primary" />
            {t("owner.crossSelling.historyTitle")}
          </CardTitle>
          <CardDescription>{t("owner.crossSelling.historyDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-24 w-full" />
          ) : smsHistory.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.crossSelling.historyEmpty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.crossSelling.columns.store")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.product")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.sentDate")}</TableHead>
                  <TableHead>{t("owner.crossSelling.columns.credit")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {smsHistory.map((row) => (
                  <TableRow key={row.id}>
                    <TableCell>{row.store?.name ?? row.store_id}</TableCell>
                    <TableCell>{row.product?.label ?? row.cross_product_id}</TableCell>
                    <TableCell>
                      {row.sent_date
                        ? new Date(row.sent_date).toLocaleDateString()
                        : "—"}
                    </TableCell>
                    <TableCell>{row.credit}</TableCell>
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
