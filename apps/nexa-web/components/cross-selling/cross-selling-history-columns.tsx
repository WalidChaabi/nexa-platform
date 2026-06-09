"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { SmsCrossSellingRecord } from "@/lib/api/cross-selling";

export type CrossSellingHistoryTableActions = {
  t: (key: string) => string;
};

export function createCrossSellingHistoryColumns(
  actions: CrossSellingHistoryTableActions,
): ColumnDef<SmsCrossSellingRecord>[] {
  const { t } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.store?.name ?? row.store_id} ${row.product?.label ?? row.cross_product_id}`.toLowerCase(),
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
      accessorFn: (row) => row.store?.name ?? String(row.store_id),
      header: t("owner.crossSelling.columns.store"),
      enableSorting: false,
    },
    {
      id: "product",
      accessorFn: (row) => row.product?.label ?? String(row.cross_product_id),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.crossSelling.columns.product")}
        />
      ),
    },
    {
      id: "sentDate",
      accessorFn: (row) => row.sent_date ?? "",
      header: t("owner.crossSelling.columns.sentDate"),
      cell: ({ row }) =>
        row.original.sent_date
          ? new Date(row.original.sent_date).toLocaleDateString()
          : "—",
      enableSorting: false,
    },
    {
      accessorKey: "credit",
      header: t("owner.crossSelling.columns.credit"),
      enableSorting: false,
    },
  ];
}
