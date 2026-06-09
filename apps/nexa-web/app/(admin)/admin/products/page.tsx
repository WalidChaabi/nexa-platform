"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { FileSpreadsheet, Package } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { createAdminProductsColumns } from "@/components/products/admin-products-columns";
import { ProductImportDialog } from "@/components/products/product-import-dialog";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function AdminProductsPage() {
  const { t, formatCurrency } = useLocale();
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [importOpen, setImportOpen] = useState(false);

  const reloadProducts = useCallback(() => {
    setReloadKey((value) => value + 1);
  }, []);

  useEffect(() => {
    let active = true;

    async function loadProducts() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [productData, storeData] = await Promise.all([
          fetchProducts(),
          fetchStores(),
        ]);

        if (active) {
          setProducts(productData);
          setStores(storeData);
        }
      } catch {
        if (active) {
          setErrorKey("admin.products.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadProducts();

    return () => {
      active = false;
    };
  }, [reloadKey]);

  const columns = useMemo(
    () =>
      createAdminProductsColumns({
        t,
        formatCurrency,
      }),
    [t, formatCurrency],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Package}
        title={t("admin.products.title")}
        description={t("admin.products.description")}
      >
        <Button
          type="button"
          variant="outline"
          onClick={() => setImportOpen(true)}
          disabled={stores.length === 0}
        >
          <FileSpreadsheet className="size-4" />
          {t("admin.products.import.trigger")}
        </Button>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("admin.products.listTitle")}</CardTitle>
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
              data={products}
              filterColumn="search"
              filterPlaceholder={t("admin.products.searchPlaceholder")}
              emptyMessage={t("admin.products.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>

      <ProductImportDialog
        open={importOpen}
        onOpenChange={setImportOpen}
        stores={stores}
        namespace="admin.products"
        onSuccess={reloadProducts}
      />
    </div>
  );
}
