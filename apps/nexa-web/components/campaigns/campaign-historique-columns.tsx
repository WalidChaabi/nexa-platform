"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CampaignHistoriqueSummary } from "@/lib/api/campaigns";

export type CampaignHistoriqueTableActions = {
  t: (key: string) => string;
};

export function createCampaignHistoriqueColumns(
  actions: CampaignHistoriqueTableActions,
): ColumnDef<CampaignHistoriqueSummary>[] {
  const { t } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.customer?.display_name ?? row.customer_id} ${row.phone} ${row.code_red}`.toLowerCase(),
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
      id: "customer",
      accessorFn: (row) => row.customer?.display_name ?? String(row.customer_id),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("owner.campaigns.columns.customer")}
        />
      ),
    },
    {
      accessorKey: "phone",
      header: t("owner.campaigns.columns.phone"),
      enableSorting: false,
    },
    {
      accessorKey: "code_red",
      header: t("owner.campaigns.columns.code"),
      enableSorting: false,
    },
    {
      id: "date",
      accessorFn: (row) => row.start_date ?? "",
      header: t("owner.campaigns.columns.date"),
      cell: ({ row }) =>
        row.original.start_date
          ? new Date(row.original.start_date).toLocaleDateString()
          : "—",
      enableSorting: false,
    },
  ];
}
