"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { AppointmentRowActions } from "@/components/calendar/appointment-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { AppointmentSummary } from "@/lib/api/rdvs";

export type AppointmentsTableActions = {
  t: (key: string) => string;
  translationPrefix: "owner.calendar" | "employer.calendar";
  onEdit: (appointment: AppointmentSummary) => void;
  onDelete: (appointment: AppointmentSummary) => void;
};

export function createAppointmentsColumns(
  actions: AppointmentsTableActions,
): ColumnDef<AppointmentSummary>[] {
  const { t, translationPrefix, onEdit, onDelete } = actions;
  const prefix = translationPrefix;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.title} ${row.customer?.display_name ?? row.customer_id} ${row.store?.name ?? ""}`.toLowerCase(),
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
      accessorKey: "start",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t(`${prefix}.columns.start`)}
        />
      ),
      cell: ({ row }) =>
        row.original.start
          ? new Date(row.original.start).toLocaleString()
          : "—",
    },
    {
      accessorKey: "title",
      header: t(`${prefix}.columns.service`),
      cell: ({ row }) => (
        <Badge
          variant="outline"
          style={{
            borderColor: row.original.color,
            color: row.original.color,
          }}
        >
          {row.original.title}
        </Badge>
      ),
      enableSorting: false,
    },
    {
      id: "customer",
      accessorFn: (row) => row.customer?.display_name ?? String(row.customer_id),
      header: t(`${prefix}.columns.customer`),
      enableSorting: false,
    },
    {
      id: "store",
      accessorFn: (row) => row.store?.name ?? String(row.store_id),
      header: t(`${prefix}.columns.store`),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t(`${prefix}.columns.actions`)}</div>
      ),
      cell: ({ row }) => (
        <AppointmentRowActions
          appointment={row.original}
          translationPrefix={translationPrefix}
          onEdit={onEdit}
          onDelete={onDelete}
        />
      ),
    },
  ];
}
