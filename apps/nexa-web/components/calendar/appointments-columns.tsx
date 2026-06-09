"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { AppointmentRowActions } from "@/components/calendar/appointment-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { AppointmentSummary } from "@/lib/api/rdvs";
import type { useLocale } from "@/components/providers/locale-provider";

type Locale = ReturnType<typeof useLocale>;

export type CalendarTranslationPrefix =
  | "owner.calendar"
  | "employer.calendar"
  | "admin.calendar";

export type AppointmentsTableActions = {
  t: Locale["t"];
  formatDateTime: Locale["formatDateTime"];
  translationPrefix: CalendarTranslationPrefix;
  readOnly?: boolean;
  onEdit: (appointment: AppointmentSummary) => void;
  onDelete: (appointment: AppointmentSummary) => void;
};

export function createAppointmentsColumns(
  actions: AppointmentsTableActions,
): ColumnDef<AppointmentSummary>[] {
  const {
    t,
    formatDateTime,
    translationPrefix,
    readOnly = false,
    onEdit,
    onDelete,
  } = actions;
  const prefix = translationPrefix;

  const columns: ColumnDef<AppointmentSummary>[] = [
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
        row.original.start ? formatDateTime(row.original.start) : "—",
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
  ];

  if (!readOnly) {
    columns.push({
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t(`${prefix}.columns.actions`)}</div>
      ),
      cell: ({ row }) => (
        <AppointmentRowActions
          appointment={row.original}
          translationPrefix={
            translationPrefix === "admin.calendar"
              ? "owner.calendar"
              : translationPrefix
          }
          onEdit={onEdit}
          onDelete={onDelete}
        />
      ),
    });
  }

  return columns;
}
