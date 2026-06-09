"use client";

import { useEffect, useState } from "react";
import { List, Package, Search } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Skeleton } from "@/components/ui/skeleton";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";

export default function AdminProductsPage() {
  const { t, formatCurrency } = useLocale();
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [search, setSearch] = useState("");

  useEffect(() => {
    const active = true;

    async function loadProducts() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchProducts({
          q: search.trim() || undefined,
        });
        if (active) {
          setProducts(data);
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
  }, [search]);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Package}
        title={t("admin.products.title")}
        description={t("admin.products.description")}
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
            <Search className="size-4 text-primary" />
            {t("admin.products.searchTitle")}
          </CardTitle>
          <CardDescription>{t("admin.products.searchDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <Input
            placeholder={t("admin.products.searchPlaceholder")}
            value={search}
            onChange={(event) => setSearch(event.target.value)}
          />
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("admin.products.listTitle")}
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
              {t("admin.products.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.products.columns.label")}</TableHead>
                  <TableHead>{t("admin.products.columns.store")}</TableHead>
                  <TableHead>{t("admin.products.columns.category")}</TableHead>
                  <TableHead>{t("admin.products.columns.price")}</TableHead>
                  <TableHead>{t("admin.products.columns.quantity")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {products.map((product) => (
                  <TableRow key={product.id}>
                    <TableCell className="font-medium">{product.label}</TableCell>
                    <TableCell>
                      {product.store?.name ?? `#${product.store_id}`}
                    </TableCell>
                    <TableCell>
                      {product.category?.name ?? "—"}
                    </TableCell>
                    <TableCell>{formatCurrency(product.price)}</TableCell>
                    <TableCell>{product.quantite}</TableCell>
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
