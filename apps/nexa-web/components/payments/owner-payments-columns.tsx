"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { PaymentSummary } from "@/lib/api/payments";

export type OwnerPaymentsTableActions = {
  t: (key: string) => string;
  formatCurrency: (value: number) => string;
  formatDateTime: (value: string) => string;
};

export function createOwnerPaymentsColumns(
  actions: OwnerPaymentsTableActions,
): ColumnDef<PaymentSummary>[] {
  const { t, formatCurrency, formatDateTime } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.cmd} ${row.status_label}`.toLowerCase(),
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
      accessorKey: "cmd",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.payments.columns.invoice")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.cmd}</div>
      ),
    },
    {
      accessorKey: "total_ht",
      header: t("owner.payments.columns.totalHt"),
      cell: ({ row }) => formatCurrency(row.original.total_ht),
      enableSorting: false,
    },
    {
      accessorKey: "total_ttc",
      header: t("owner.payments.columns.totalTtc"),
      cell: ({ row }) => formatCurrency(row.original.total_ttc),
      enableSorting: false,
    },
    {
      accessorKey: "created_at",
      header: t("owner.payments.columns.date"),
      cell: ({ row }) => formatDateTime(row.original.created_at),
      enableSorting: false,
    },
    {
      id: "status",
      accessorFn: (row) => row.status_label,
      header: t("owner.payments.columns.status"),
      cell: ({ row }) => (
        <Badge
          variant={
            row.original.status_label === "paid" ? "default" : "secondary"
          }
        >
          {row.original.status_label}
        </Badge>
      ),
      enableSorting: false,
    },
  ];
}
