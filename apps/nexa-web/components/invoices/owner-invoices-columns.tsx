"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { OwnerInvoiceRowActions } from "@/components/invoices/owner-invoice-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { InvoiceSummary } from "@/lib/api/invoices";
import type { useLocale } from "@/components/providers/locale-provider";

type Locale = ReturnType<typeof useLocale>;

export type OwnerInvoiceColumnsOptions = {
  t: Locale["t"];
  formatCurrency: Locale["formatCurrency"];
  formatDateTime: Locale["formatDateTime"];
};

function customerName(invoice: InvoiceSummary, t: Locale["t"]): string {
  const customer = invoice.customer;
  if (!customer) {
    return t("owner.invoices.customerFallback", { id: invoice.customer_id });
  }

  const name = [customer.firstname, customer.lastname].filter(Boolean).join(" ");
  return name || customer.phone;
}

export function createOwnerInvoiceColumns({
  t,
  formatCurrency,
  formatDateTime,
}: OwnerInvoiceColumnsOptions): ColumnDef<InvoiceSummary>[] {
  return [
    {
      accessorKey: "id",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.invoices.columns.id")}
        />
      ),
      cell: ({ row }) => (
        <span className="font-medium">{row.getValue("id")}</span>
      ),
    },
    {
      accessorKey: "invoice_date",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.invoices.columns.date")}
        />
      ),
      cell: ({ row }) => formatDateTime(row.getValue("invoice_date")),
    },
    {
      id: "store_name",
      accessorFn: (row) => row.store?.name ?? `#${row.store_id}`,
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.invoices.columns.store")}
        />
      ),
      cell: ({ row }) => row.original.store?.name ?? `#${row.original.store_id}`,
      filterFn: "includesString",
    },
    {
      id: "customer_name",
      accessorFn: (row) => customerName(row, t),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.invoices.columns.customer")}
        />
      ),
      cell: ({ row }) => customerName(row.original, t),
      filterFn: "includesString",
    },
    {
      accessorKey: "total",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.invoices.columns.total")}
          className="justify-end"
        />
      ),
      cell: ({ row }) => (
        <div className="text-right font-medium">
          {formatCurrency(row.getValue("total"))}
        </div>
      ),
    },
    {
      accessorKey: "status_label",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.invoices.columns.status")}
        />
      ),
      cell: ({ row }) => {
        const status = row.getValue("status_label") as string;
        return (
          <Badge variant={status === "pending" ? "default" : "secondary"}>
            {status}
          </Badge>
        );
      },
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-right">{t("owner.invoices.columns.actions")}</div>
      ),
      cell: ({ row }) => <OwnerInvoiceRowActions invoice={row.original} />,
    },
  ];
}
