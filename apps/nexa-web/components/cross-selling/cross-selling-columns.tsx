"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { CrossSellingRowActions } from "@/components/cross-selling/cross-selling-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CrossProductRule } from "@/lib/api/cross-selling";

export type CrossSellingTableActions = {
  t: (key: string) => string;
  onEdit: (rule: CrossProductRule) => void;
  onDelete: (rule: CrossProductRule) => void;
};

export function createCrossSellingColumns(
  actions: CrossSellingTableActions,
): ColumnDef<CrossProductRule>[] {
  const { t, onEdit, onDelete } = actions;

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
      id: "product",
      accessorFn: (row) => row.product?.label ?? String(row.product_id),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.crossSelling.columns.product")}
        />
      ),
      cell: ({ row }) => row.original.product?.label ?? row.original.product_id,
    },
    {
      id: "store",
      accessorFn: (row) => row.product?.store?.name ?? "",
      header: t("owner.crossSelling.columns.store"),
      cell: ({ row }) => row.original.product?.store?.name ?? "—",
      enableSorting: false,
    },
    {
      accessorKey: "days",
      header: t("owner.crossSelling.columns.days"),
      enableSorting: false,
    },
    {
      accessorKey: "message",
      header: t("owner.crossSelling.columns.message"),
      cell: ({ row }) => (
        <span className="max-w-xs truncate">{row.original.message}</span>
      ),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("owner.crossSelling.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <CrossSellingRowActions
          rule={row.original}
          onEdit={onEdit}
          onDelete={onDelete}
        />
      ),
    },
  ];
}
