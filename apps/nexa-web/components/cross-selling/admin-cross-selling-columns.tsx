"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CrossProductRule } from "@/lib/api/cross-selling";

export type AdminCrossSellingTableActions = {
  t: (key: string) => string;
};

export function createAdminCrossSellingColumns(
  actions: AdminCrossSellingTableActions,
): ColumnDef<CrossProductRule>[] {
  const { t } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.product?.label ?? row.product_id} ${row.message} ${row.product?.store?.name ?? ""}`.toLowerCase(),
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
      id: "store",
      accessorFn: (row) => row.product?.store?.name ?? "—",
      header: t("admin.crossSelling.columns.store"),
      enableSorting: false,
    },
    {
      id: "product",
      accessorFn: (row) => row.product?.label ?? String(row.product_id),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.crossSelling.columns.product")}
        />
      ),
    },
    {
      accessorKey: "days",
      header: t("admin.crossSelling.columns.days"),
      enableSorting: false,
    },
    {
      accessorKey: "message",
      header: t("admin.crossSelling.columns.message"),
      cell: ({ row }) => (
        <span className="max-w-md truncate">{row.original.message}</span>
      ),
      enableSorting: false,
    },
  ];
}
