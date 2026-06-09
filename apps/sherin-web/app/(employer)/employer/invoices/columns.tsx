"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { InvoiceRowActions } from "./invoice-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { InvoiceSummary } from "@/lib/api/invoices";
import type { useLocale } from "@/components/providers/locale-provider";

type Locale = ReturnType<typeof useLocale>;

export type EmployerInvoiceColumnsOptions = {
  t: Locale["t"];
  formatCurrency: Locale["formatCurrency"];
  formatDateTime: Locale["formatDateTime"];
  cancellingId: number | null;
  onCancel: (invoiceId: number) => void;
};

function customerName(
  invoice: InvoiceSummary,
  t: Locale["t"],
): string {
  const customer = invoice.customer;
  if (!customer) {
    return t("employer.invoices.customerFallback", {
      id: invoice.customer_id,
    });
  }

  const name = [customer.firstname, customer.lastname].filter(Boolean).join(" ");
  return name || customer.phone;
}

export function createEmployerInvoiceColumns({
  t,
  formatCurrency,
  formatDateTime,
  cancellingId,
  onCancel,
}: EmployerInvoiceColumnsOptions): ColumnDef<InvoiceSummary>[] {
  return [
    {
      accessorKey: "id",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("employer.invoices.columns.id")}
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
          title={t("employer.invoices.columns.date")}
        />
      ),
      cell: ({ row }) => formatDateTime(row.getValue("invoice_date")),
    },
    {
      id: "customer_name",
      accessorFn: (row) => customerName(row, t),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("employer.invoices.columns.customer")}
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
          title={t("employer.invoices.columns.total")}
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
          title={t("employer.invoices.columns.status")}
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
        <div className="text-right">{t("employer.invoices.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <InvoiceRowActions
          invoice={row.original}
          cancellingId={cancellingId}
          onCancel={onCancel}
        />
      ),
    },
  ];
}
