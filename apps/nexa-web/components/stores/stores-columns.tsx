"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { StoreRowActions } from "@/components/stores/store-row-actions";
import { Badge } from "@/components/ui/badge";
import type { StoreSummary } from "@/lib/api/stores";

export type StoresTableActions = {
  t: (key: string) => string;
  onEdit: (store: StoreSummary) => void;
};

export function createStoresColumns(
  actions: StoresTableActions,
): ColumnDef<StoreSummary>[] {
  const { t, onEdit } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.name} ${row.phone ?? ""} ${row.address ?? ""}`.toLowerCase(),
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
      accessorKey: "name",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.stores.columns.name")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.name}</div>
      ),
    },
    {
      accessorKey: "phone",
      header: t("owner.stores.columns.phone"),
      cell: ({ row }) => row.original.phone || "—",
      enableSorting: false,
    },
    {
      accessorKey: "status",
      header: t("owner.stores.columns.status"),
      cell: ({ row }) => (
        <Badge variant={row.original.status === 1 ? "default" : "secondary"}>
          {row.original.status === 1
            ? t("owner.stores.statusActive")
            : t("owner.stores.statusInactive")}
        </Badge>
      ),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("owner.stores.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <StoreRowActions store={row.original} onEdit={onEdit} />
      ),
    },
  ];
}
