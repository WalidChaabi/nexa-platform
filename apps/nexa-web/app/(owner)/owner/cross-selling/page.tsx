"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { History, Plus, RefreshCw } from "lucide-react";

import { CrossSellingCreateDialog } from "@/components/cross-selling/cross-selling-create-dialog";
import { createCrossSellingColumns } from "@/components/cross-selling/cross-selling-columns";
import { CrossSellingFormSheet } from "@/components/cross-selling/cross-selling-form-sheet";
import { DataTable } from "@/components/data-table/data-table";
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
  deleteCrossProduct,
  fetchCrossProducts,
  fetchSmsCrossSelling,
  type CrossProductRule,
  type SmsCrossSellingRecord,
} from "@/lib/api/cross-selling";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";

export default function OwnerCrossSellingPage() {
  const { t } = useLocale();
  const [rules, setRules] = useState<CrossProductRule[]>([]);
  const [smsHistory, setSmsHistory] = useState<SmsCrossSellingRecord[]>([]);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [createOpen, setCreateOpen] = useState(false);
  const [editRule, setEditRule] = useState<CrossProductRule | null>(null);
  const [editOpen, setEditOpen] = useState(false);

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

    return () => {
      active = false;
    };
  }, [reloadKey]);

  const usedProductIds = new Set(rules.map((rule) => rule.product_id));
  const availableProducts = products.filter(
    (product) => !usedProductIds.has(product.id),
  );

  const handleDelete = useCallback(async (rule: CrossProductRule) => {
    setErrorKey(null);

    try {
      await deleteCrossProduct(rule.id);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.crossSelling.deleteError");
    }
  }, []);

  const columns = useMemo(
    () =>
      createCrossSellingColumns({
        t,
        onEdit: (rule) => {
          setEditRule(rule);
          setEditOpen(true);
        },
        onDelete: (rule) => void handleDelete(rule),
      }),
    [t, handleDelete],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={RefreshCw}
        title={t("owner.crossSelling.title")}
        description={t("owner.crossSelling.description")}
      >
        <Button onClick={() => setCreateOpen(true)}>
          <Plus />
          {t("owner.crossSelling.create")}
        </Button>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <CrossSellingCreateDialog
        open={createOpen}
        onOpenChange={setCreateOpen}
        availableProducts={availableProducts}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.crossSelling.listTitle")}</CardTitle>
          <CardDescription>{t("owner.crossSelling.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={rules}
              filterColumn="search"
              filterPlaceholder={t("owner.crossSelling.filterPlaceholder")}
              emptyMessage={t("owner.crossSelling.empty")}
              initialColumnVisibility={{ search: false }}
            />
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

      <CrossSellingFormSheet
        open={editOpen}
        onOpenChange={setEditOpen}
        rule={editRule}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />
    </div>
  );
}
