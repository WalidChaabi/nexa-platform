"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { CustomerRowActions } from "@/components/customers/customer-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CustomerSummary } from "@/lib/api/customers";

export type CustomersTableActions = {
  t: (key: string) => string;
  onEdit: (customer: CustomerSummary) => void;
};

export function createCustomersColumns(
  actions: CustomersTableActions,
): ColumnDef<CustomerSummary>[] {
  const { t, onEdit } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.display_name} ${row.phone} ${row.email ?? ""}`.toLowerCase(),
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
          title={t("employer.customers.columns.name")}
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
          title={t("employer.customers.columns.phone")}
        />
      ),
    },
    {
      accessorKey: "points",
      header: t("employer.customers.columns.points"),
      cell: ({ row }) => row.original.points ?? "0",
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("employer.customers.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <CustomerRowActions customer={row.original} onEdit={onEdit} />
      ),
    },
  ];
}
