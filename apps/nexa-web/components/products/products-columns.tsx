"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { ProductRowActions } from "@/components/products/product-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { ProductSummary } from "@/lib/api/products";

export type ProductsTableActions = {
  t: (key: string) => string;
  formatCurrency: (value: number) => string;
  onEdit: (product: ProductSummary) => void;
};

export function createProductsColumns(
  actions: ProductsTableActions,
): ColumnDef<ProductSummary>[] {
  const { t, formatCurrency, onEdit } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.label} ${row.category?.name ?? ""}`.toLowerCase(),
      enableHiding: false,
      enableSorting: false,
      filterFn: (row, _columnId, filterValue) => {
        const query = String(filterValue ?? "")
          .trim()
          .toLowerCase();

        if (!query) {
          return true;
        }

        return String(row.getValue("search")).includes(query);
      },
    },
    {
      accessorKey: "label",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("employer.products.columns.label")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.label}</div>
      ),
    },
    {
      id: "category",
      accessorFn: (row) => row.category?.name ?? t("employer.products.noCategory"),
      header: t("employer.products.columns.category"),
      enableSorting: false,
    },
    {
      accessorKey: "price",
      header: t("employer.products.columns.price"),
      cell: ({ row }) => formatCurrency(row.original.price),
      enableSorting: false,
    },
    {
      accessorKey: "quantite",
      header: t("employer.products.columns.quantity"),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("employer.products.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <ProductRowActions product={row.original} onEdit={onEdit} />
      ),
    },
  ];
}
