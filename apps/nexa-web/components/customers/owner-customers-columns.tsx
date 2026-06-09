"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CustomerSummary } from "@/lib/api/customers";

export type OwnerCustomersTableActions = {
  t: (key: string) => string;
};

export function createOwnerCustomersColumns(
  actions: OwnerCustomersTableActions,
): ColumnDef<CustomerSummary>[] {
  const { t } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.display_name} ${row.phone} ${row.email ?? ""} ${row.store?.name ?? ""}`.toLowerCase(),
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
      accessorKey: "display_name",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.customers.columns.name")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.display_name}</div>
      ),
    },
    {
      accessorKey: "phone",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.customers.columns.phone")}
        />
      ),
    },
    {
      id: "store",
      accessorFn: (row) => row.store?.name ?? `#${row.store_id}`,
      header: t("owner.customers.columns.store"),
      enableSorting: false,
    },
    {
      accessorKey: "points",
      header: t("owner.customers.columns.points"),
      cell: ({ row }) => row.original.points ?? "0",
      enableSorting: false,
    },
  ];
}
