"use client";

import { useEffect, useState } from "react";
import {
  List,
  Package,
  Pencil,
  Plus,
  Search,
  Tags,
} from "lucide-react";

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
  createCategory,
  fetchCategories,
  updateCategory,
  type CategorySummary,
} from "@/lib/api/categories";
import {
  createProduct,
  fetchProducts,
  updateProduct,
  type ProductInput,
  type ProductSummary,
  type ProductUpdateInput,
} from "@/lib/api/products";

const NO_CATEGORY = "__none__";

const emptyCategory = { name: "", description: "" };

const emptyProduct: Omit<ProductInput, "store_id"> = {
  label: "",
  price: 0,
  quantite: 0,
  categorie_id: null,
};

export default function EmployerProductsPage() {
  const { user } = useDashboardAuth();
  const { t, formatCurrency } = useLocale();
  const storeId = user.store_id;

  const [categories, setCategories] = useState<CategorySummary[]>([]);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [search, setSearch] = useState("");
  const [categoryFilter, setCategoryFilter] = useState<string>(NO_CATEGORY);

  const [categoryForm, setCategoryForm] = useState(emptyCategory);
  const [creatingCategory, setCreatingCategory] = useState(false);
  const [editingCategoryId, setEditingCategoryId] = useState<number | null>(null);
  const [editCategoryForm, setEditCategoryForm] = useState(emptyCategory);
  const [savingCategory, setSavingCategory] = useState(false);

  const [productForm, setProductForm] = useState(emptyProduct);
  const [creatingProduct, setCreatingProduct] = useState(false);
  const [editingProductId, setEditingProductId] = useState<number | null>(null);
  const [editProductForm, setEditProductForm] = useState<ProductUpdateInput>({});
  const [savingProduct, setSavingProduct] = useState(false);

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    const active = true;

    async function loadCatalog() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [categoryData, productData] = await Promise.all([
          fetchCategories(activeStoreId),
          fetchProducts({
            store_id: activeStoreId,
            q: search.trim() || undefined,
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
  }, [storeId, reloadKey, search, categoryFilter]);

  async function handleCreateCategory(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (storeId == null) {
      return;
    }

    setCreatingCategory(true);
    setErrorKey(null);

    try {
      await createCategory({
        store_id: storeId,
        name: categoryForm.name,
        description: categoryForm.description,
      });
      setCategoryForm(emptyCategory);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.products.categoryCreateError");
    } finally {
      setCreatingCategory(false);
    }
  }

  async function handleUpdateCategory(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (editingCategoryId == null) {
      return;
    }

    setSavingCategory(true);
    setErrorKey(null);

    try {
      await updateCategory(editingCategoryId, editCategoryForm);
      setEditingCategoryId(null);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.products.categoryUpdateError");
    } finally {
      setSavingCategory(false);
    }
  }

  function startEditCategory(category: CategorySummary) {
    setEditingCategoryId(category.id);
    setEditCategoryForm({
      name: category.name,
      description: category.description,
    });
  }

  async function handleCreateProduct(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (storeId == null) {
      return;
    }

    setCreatingProduct(true);
    setErrorKey(null);

    try {
      await createProduct({
        store_id: storeId,
        ...productForm,
        price: Number(productForm.price),
        quantite: Number(productForm.quantite ?? 0),
      });
      setProductForm(emptyProduct);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.products.createError");
    } finally {
      setCreatingProduct(false);
    }
  }

  async function handleUpdateProduct(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (editingProductId == null) {
      return;
    }

    setSavingProduct(true);
    setErrorKey(null);

    try {
      await updateProduct(editingProductId, {
        ...editProductForm,
        price:
          editProductForm.price !== undefined
            ? Number(editProductForm.price)
            : undefined,
        quantite:
          editProductForm.quantite !== undefined
            ? Number(editProductForm.quantite)
            : undefined,
      });
      setEditingProductId(null);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.products.updateError");
    } finally {
      setSavingProduct(false);
    }
  }

  function startEditProduct(product: ProductSummary) {
    setEditingProductId(product.id);
    setEditProductForm({
      label: product.label,
      price: product.price,
      promotion_price: product.promotion_price,
      quantite: product.quantite,
      categorie_id: product.categorie_id,
    });
  }

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
            <Tags className="size-4 text-primary" />
            {t("employer.products.categoriesTitle")}
          </CardTitle>
          <CardDescription>
            {t("employer.products.categoriesDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          <form
            className="grid gap-4 sm:grid-cols-2"
            onSubmit={handleCreateCategory}
          >
            <div className="space-y-2">
              <Label htmlFor="cat-name">
                {t("employer.products.fields.categoryName")}
              </Label>
              <Input
                id="cat-name"
                required
                value={categoryForm.name}
                onChange={(event) =>
                  setCategoryForm((value) => ({
                    ...value,
                    name: event.target.value,
                  }))
                }
              />
            </div>
            <div className="space-y-2 sm:col-span-2">
              <Button type="submit" disabled={creatingCategory}>
                <Plus />
                {creatingCategory
                  ? t("employer.products.categoryCreating")
                  : t("employer.products.categoryCreate")}
              </Button>
            </div>
          </form>

          {editingCategoryId != null ? (
            <form
              className="grid gap-4 sm:grid-cols-2 rounded-lg border p-4"
              onSubmit={handleUpdateCategory}
            >
              <div className="space-y-2 sm:col-span-2">
                <Label>{t("employer.products.fields.categoryName")}</Label>
                <Input
                  required
                  value={editCategoryForm.name ?? ""}
                  onChange={(event) =>
                    setEditCategoryForm((value) => ({
                      ...value,
                      name: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="flex gap-2 sm:col-span-2">
                <Button type="submit" disabled={savingCategory}>
                  {savingCategory
                    ? t("employer.products.saving")
                    : t("employer.products.save")}
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  onClick={() => setEditingCategoryId(null)}
                >
                  {t("common.cancel")}
                </Button>
              </div>
            </form>
          ) : null}

          {categories.length > 0 ? (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("employer.products.columns.category")}</TableHead>
                  <TableHead className="text-end">
                    {t("employer.products.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {categories.map((category) => (
                  <TableRow key={category.id}>
                    <TableCell>{category.name}</TableCell>
                    <TableCell className="text-end">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => startEditCategory(category)}
                      >
                        <Pencil />
                        {t("employer.products.edit")}
                      </Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          ) : (
            <p className="text-sm text-muted-foreground">
              {t("employer.products.categoriesEmpty")}
            </p>
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Search className="size-4 text-primary" />
            {t("employer.products.searchTitle")}
          </CardTitle>
        </CardHeader>
        <CardContent className="grid gap-4 sm:grid-cols-2">
          <Input
            placeholder={t("employer.products.searchPlaceholder")}
            value={search}
            onChange={(event) => setSearch(event.target.value)}
          />
          <Select
            value={categoryFilter}
            onValueChange={(value) => setCategoryFilter(value ?? NO_CATEGORY)}
          >
            <SelectTrigger className="w-full">
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
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Plus className="size-4 text-primary" />
            {t("employer.products.createTitle")}
          </CardTitle>
          <CardDescription>{t("employer.products.createDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 sm:grid-cols-2" onSubmit={handleCreateProduct}>
            <div className="space-y-2 sm:col-span-2">
              <Label htmlFor="product-label">
                {t("employer.products.fields.label")}
              </Label>
              <Input
                id="product-label"
                required
                value={productForm.label}
                onChange={(event) =>
                  setProductForm((value) => ({
                    ...value,
                    label: event.target.value,
                  }))
                }
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="product-price">
                {t("employer.products.fields.price")}
              </Label>
              <Input
                id="product-price"
                type="number"
                min={0}
                step="0.01"
                required
                value={productForm.price}
                onChange={(event) =>
                  setProductForm((value) => ({
                    ...value,
                    price: Number(event.target.value),
                  }))
                }
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="product-qty">
                {t("employer.products.fields.quantity")}
              </Label>
              <Input
                id="product-qty"
                type="number"
                min={0}
                value={productForm.quantite ?? 0}
                onChange={(event) =>
                  setProductForm((value) => ({
                    ...value,
                    quantite: Number(event.target.value),
                  }))
                }
              />
            </div>
            <div className="space-y-2 sm:col-span-2">
              <Label>{t("employer.products.fields.category")}</Label>
              <Select
                value={
                  productForm.categorie_id
                    ? String(productForm.categorie_id)
                    : NO_CATEGORY
                }
                onValueChange={(value) =>
                  setProductForm((prev) => ({
                    ...prev,
                    categorie_id:
                      !value || value === NO_CATEGORY ? null : Number(value),
                  }))
                }
              >
                <SelectTrigger className="w-full">
                  <SelectValue placeholder={t("employer.products.noCategory")} />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value={NO_CATEGORY}>
                    {t("employer.products.noCategory")}
                  </SelectItem>
                  {categories.map((category) => (
                    <SelectItem key={category.id} value={String(category.id)}>
                      {category.name}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="sm:col-span-2">
              <Button type="submit" disabled={creatingProduct}>
                <Plus />
                {creatingProduct
                  ? t("employer.products.creating")
                  : t("employer.products.create")}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      {editingProductId != null ? (
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Pencil className="size-4 text-primary" />
              {t("employer.products.editTitle")}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form className="grid gap-4 sm:grid-cols-2" onSubmit={handleUpdateProduct}>
              <div className="space-y-2 sm:col-span-2">
                <Label>{t("employer.products.fields.label")}</Label>
                <Input
                  required
                  value={editProductForm.label ?? ""}
                  onChange={(event) =>
                    setEditProductForm((value) => ({
                      ...value,
                      label: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-2">
                <Label>{t("employer.products.fields.price")}</Label>
                <Input
                  type="number"
                  min={0}
                  step="0.01"
                  required
                  value={editProductForm.price ?? 0}
                  onChange={(event) =>
                    setEditProductForm((value) => ({
                      ...value,
                      price: Number(event.target.value),
                    }))
                  }
                />
              </div>
              <div className="space-y-2">
                <Label>{t("employer.products.fields.quantity")}</Label>
                <Input
                  type="number"
                  min={0}
                  value={editProductForm.quantite ?? 0}
                  onChange={(event) =>
                    setEditProductForm((value) => ({
                      ...value,
                      quantite: Number(event.target.value),
                    }))
                  }
                />
              </div>
              <div className="space-y-2 sm:col-span-2">
                <Label>{t("employer.products.fields.category")}</Label>
                <Select
                  value={
                    editProductForm.categorie_id
                      ? String(editProductForm.categorie_id)
                      : NO_CATEGORY
                  }
                  onValueChange={(value) =>
                    setEditProductForm((prev) => ({
                      ...prev,
                      categorie_id:
                        !value || value === NO_CATEGORY ? null : Number(value),
                    }))
                  }
                >
                  <SelectTrigger className="w-full">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value={NO_CATEGORY}>
                      {t("employer.products.noCategory")}
                    </SelectItem>
                    {categories.map((category) => (
                      <SelectItem key={category.id} value={String(category.id)}>
                        {category.name}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div className="flex gap-2 sm:col-span-2">
                <Button type="submit" disabled={savingProduct}>
                  {savingProduct
                    ? t("employer.products.saving")
                    : t("employer.products.save")}
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  onClick={() => setEditingProductId(null)}
                >
                  {t("common.cancel")}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("employer.products.listTitle")}
          </CardTitle>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : products.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("employer.products.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("employer.products.columns.label")}</TableHead>
                  <TableHead>{t("employer.products.columns.category")}</TableHead>
                  <TableHead>{t("employer.products.columns.price")}</TableHead>
                  <TableHead>{t("employer.products.columns.quantity")}</TableHead>
                  <TableHead className="text-end">
                    {t("employer.products.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {products.map((product) => (
                  <TableRow key={product.id}>
                    <TableCell className="font-medium">{product.label}</TableCell>
                    <TableCell>
                      {product.category?.name ?? t("employer.products.noCategory")}
                    </TableCell>
                    <TableCell>{formatCurrency(product.price)}</TableCell>
                    <TableCell>{product.quantite}</TableCell>
                    <TableCell className="text-end">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => startEditProduct(product)}
                      >
                        <Pencil />
                        {t("employer.products.edit")}
                      </Button>
                    </TableCell>
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
