"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { EmployerRowActions } from "@/components/employers/employer-row-actions";
import { Badge } from "@/components/ui/badge";
import type { EmployerSummary } from "@/lib/api/employers";

export type EmployersTableActions = {
  t: (key: string) => string;
  onEdit: (employer: EmployerSummary) => void;
  onToggleActive: (employer: EmployerSummary) => void;
};

export function createEmployersColumns(
  actions: EmployersTableActions,
): ColumnDef<EmployerSummary>[] {
  const { t, onEdit, onToggleActive } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.full_name} ${row.email} ${row.phone} ${row.store?.name ?? ""}`.toLowerCase(),
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
          title={t("owner.team.columns.name")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.full_name}</div>
      ),
    },
    {
      id: "store",
      accessorFn: (row) => row.store?.name ?? "",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.team.columns.store")}
        />
      ),
      cell: ({ row }) => row.original.store?.name ?? "—",
    },
    {
      accessorKey: "phone",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.team.columns.phone")}
        />
      ),
    },
    {
      accessorKey: "email",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.team.columns.email")}
        />
      ),
    },
    {
      accessorKey: "is_active",
      header: t("owner.team.columns.status"),
      cell: ({ row }) => (
        <Badge
          variant={row.original.is_active === 1 ? "default" : "secondary"}
        >
          {row.original.is_active === 1
            ? t("owner.team.statusActive")
            : t("owner.team.statusInactive")}
        </Badge>
      ),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("owner.team.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <EmployerRowActions
          employer={row.original}
          onEdit={onEdit}
          onToggleActive={onToggleActive}
        />
      ),
    },
  ];
}
