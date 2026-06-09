"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { ActivityRowActions } from "@/components/activities/activity-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { ActivitySummary } from "@/lib/api/activities";

export type ActivitiesTableActions = {
  t: (key: string) => string;
  onEdit: (activity: ActivitySummary) => void;
  onDelete: (activity: ActivitySummary) => void;
};

export function createActivitiesColumns(
  actions: ActivitiesTableActions,
): ColumnDef<ActivitySummary>[] {
  const { t, onEdit, onDelete } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.name} ${row.description ?? ""}`.toLowerCase(),
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
          title={t("admin.activities.columns.name")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.name}</div>
      ),
    },
    {
      accessorKey: "description",
      header: t("admin.activities.columns.description"),
      cell: ({ row }) => row.original.description ?? "—",
      enableSorting: false,
    },
    {
      accessorKey: "stores_count",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.activities.columns.stores")}
        />
      ),
      cell: ({ row }) => row.original.stores_count ?? 0,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("admin.activities.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <ActivityRowActions
          activity={row.original}
          onEdit={onEdit}
          onDelete={onDelete}
        />
      ),
    },
  ];
}
