"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Package, Plus, Tags } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { CategoryFormDialog } from "@/components/products/category-form-dialog";
import { createCategoriesColumns } from "@/components/products/categories-columns";
import { ProductFormDialog } from "@/components/products/product-form-dialog";
import { createProductsColumns } from "@/components/products/products-columns";
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
  fetchCategories,
  type CategorySummary,
} from "@/lib/api/categories";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";

const NO_CATEGORY = "__none__";

type CategoryDialogState =
  | { open: false }
  | { open: true; mode: "create" }
  | { open: true; mode: "edit"; category: CategorySummary };

type ProductDialogState =
  | { open: false }
  | { open: true; mode: "create" }
  | { open: true; mode: "edit"; product: ProductSummary };

export default function EmployerProductsPage() {
  const { user } = useDashboardAuth();
  const { t, formatCurrency } = useLocale();
  const storeId = user.store_id;

  const [categories, setCategories] = useState<CategorySummary[]>([]);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [categoryFilter, setCategoryFilter] = useState<string>(NO_CATEGORY);
  const [categoryDialog, setCategoryDialog] = useState<CategoryDialogState>({
    open: false,
  });
  const [productDialog, setProductDialog] = useState<ProductDialogState>({
    open: false,
  });

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    let active = true;

    async function loadCatalog() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [categoryData, productData] = await Promise.all([
          fetchCategories(activeStoreId),
          fetchProducts({
            store_id: activeStoreId,
            categorie_id:
              categoryFilter === NO_CATEGORY
                ? undefined
                : Number(categoryFilter),
          }),
        ]);

        if (active) {
          setCategories(categoryData);
          setProducts(productData);
        }
      } catch {
        if (active) {
          setErrorKey("employer.products.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadCatalog();

    return () => {
      active = false;
    };
  }, [storeId, reloadKey, categoryFilter]);

  const categoryColumns = useMemo(
    () =>
      createCategoriesColumns({
        t,
        onEdit: (category) =>
          setCategoryDialog({ open: true, mode: "edit", category }),
      }),
    [t],
  );

  const productColumns = useMemo(
    () =>
      createProductsColumns({
        t,
        formatCurrency,
        onEdit: (product) =>
          setProductDialog({ open: true, mode: "edit", product }),
      }),
    [t, formatCurrency],
  );

  const handleReload = useCallback(() => {
    setReloadKey((value) => value + 1);
  }, []);

  if (storeId == null) {
    return (
      <Alert variant="destructive">
        <AlertTitle>{t("common.error")}</AlertTitle>
        <AlertDescription>{t("employer.products.noStore")}</AlertDescription>
      </Alert>
    );
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Package}
        title={t("employer.products.title")}
        description={t("employer.products.description")}
      >
        <div className="flex flex-wrap gap-2">
          <Button
            variant="outline"
            onClick={() => setCategoryDialog({ open: true, mode: "create" })}
          >
            <Tags />
            {t("employer.products.categoryCreate")}
          </Button>
          <Button onClick={() => setProductDialog({ open: true, mode: "create" })}>
            <Plus />
            {t("employer.products.create")}
          </Button>
        </div>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <CategoryFormDialog
        open={categoryDialog.open}
        mode={categoryDialog.open ? categoryDialog.mode : "create"}
        storeId={storeId}
        category={
          categoryDialog.open && categoryDialog.mode === "edit"
            ? categoryDialog.category
            : null
        }
        onOpenChange={(open) => {
          if (!open) {
            setCategoryDialog({ open: false });
          }
        }}
        onSuccess={handleReload}
      />

      <ProductFormDialog
        open={productDialog.open}
        mode={productDialog.open ? productDialog.mode : "create"}
        storeId={storeId}
        product={
          productDialog.open && productDialog.mode === "edit"
            ? productDialog.product
            : null
        }
        categories={categories}
        onOpenChange={(open) => {
          if (!open) {
            setProductDialog({ open: false });
          }
        }}
        onSuccess={handleReload}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("employer.products.categoriesTitle")}</CardTitle>
          <CardDescription>
            {t("employer.products.categoriesDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : categories.length === 0 ? (
            <div className="flex flex-col items-center gap-3 py-6 text-center">
              <p className="text-sm text-muted-foreground">
                {t("employer.products.categoriesEmpty")}
              </p>
              <Button
                variant="outline"
                onClick={() => setCategoryDialog({ open: true, mode: "create" })}
              >
                <Tags />
                {t("employer.products.categoryCreate")}
              </Button>
            </div>
          ) : (
            <DataTable
              columns={categoryColumns}
              data={categories}
              filterColumn="search"
              filterPlaceholder={t("employer.products.categoriesFilterPlaceholder")}
              emptyMessage={t("employer.products.categoriesEmpty")}
              initialColumnVisibility={{ search: false }}
              showPagination={categories.length > 10}
            />
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader className="gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <CardTitle>{t("employer.products.listTitle")}</CardTitle>
          </div>
          <div className="w-full space-y-2 sm:w-56">
            <Label htmlFor="product-category-filter">
              {t("employer.products.filterCategory")}
            </Label>
            <Select
              value={categoryFilter}
              onValueChange={(value) => setCategoryFilter(value ?? NO_CATEGORY)}
            >
              <SelectTrigger id="product-category-filter" className="w-full">
                <SelectValue placeholder={t("employer.products.filterCategory")} />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value={NO_CATEGORY}>
                  {t("employer.products.allCategories")}
                </SelectItem>
                {categories.map((category) => (
                  <SelectItem key={category.id} value={String(category.id)}>
                    {category.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : (
            <DataTable
              columns={productColumns}
              data={products}
              filterColumn="search"
              filterPlaceholder={t("employer.products.filterPlaceholder")}
              emptyMessage={t("employer.products.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
