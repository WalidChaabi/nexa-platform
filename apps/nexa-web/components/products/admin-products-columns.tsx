"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { ProductSummary } from "@/lib/api/products";

export type AdminProductsTableActions = {
  t: (key: string) => string;
  formatCurrency: (value: number) => string;
};

export function createAdminProductsColumns(
  actions: AdminProductsTableActions,
): ColumnDef<ProductSummary>[] {
  const { t, formatCurrency } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.label} ${row.store?.name ?? ""} ${row.category?.name ?? ""}`.toLowerCase(),
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
          title={t("admin.products.columns.label")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.label}</div>
      ),
    },
    {
      id: "store",
      accessorFn: (row) => row.store?.name ?? `#${row.store_id}`,
      header: t("admin.products.columns.store"),
      enableSorting: false,
    },
    {
      id: "category",
      accessorFn: (row) => row.category?.name ?? "—",
      header: t("admin.products.columns.category"),
      enableSorting: false,
    },
    {
      accessorKey: "price",
      header: t("admin.products.columns.price"),
      cell: ({ row }) => formatCurrency(row.original.price),
      enableSorting: false,
    },
    {
      accessorKey: "quantite",
      header: t("admin.products.columns.quantity"),
      enableSorting: false,
    },
  ];
}
