"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { OwnerRowActions } from "@/components/owners/owner-row-actions";
import { Badge } from "@/components/ui/badge";
import type { OwnerSummary } from "@/lib/api/owners";

export type OwnersTableActions = {
  t: (key: string) => string;
  onEdit: (owner: OwnerSummary) => void;
  onToggleActive: (owner: OwnerSummary) => void;
};

export function createOwnersColumns(
  actions: OwnersTableActions,
): ColumnDef<OwnerSummary>[] {
  const { t, onEdit, onToggleActive } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.full_name} ${row.email} ${row.phone}`.toLowerCase(),
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
      accessorKey: "full_name",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.owners.columns.name")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.full_name}</div>
      ),
    },
    {
      accessorKey: "phone",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.owners.columns.phone")}
        />
      ),
    },
    {
      accessorKey: "email",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.owners.columns.email")}
        />
      ),
    },
    {
      accessorKey: "stores_count",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.owners.columns.stores")}
        />
      ),
      cell: ({ row }) => row.original.stores_count ?? 0,
    },
    {
      accessorKey: "is_active",
      header: t("admin.owners.columns.status"),
      cell: ({ row }) => (
        <Badge
          variant={row.original.is_active === 1 ? "default" : "secondary"}
        >
          {row.original.is_active === 1
            ? t("admin.owners.statusActive")
            : t("admin.owners.statusInactive")}
        </Badge>
      ),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("admin.owners.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <OwnerRowActions
          owner={row.original}
          onEdit={onEdit}
          onToggleActive={onToggleActive}
        />
      ),
    },
  ];
}
