"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { CampaignDetailSummary } from "@/lib/api/campaigns";

export type AdminCampaignDetailsTableActions = {
  t: (key: string) => string;
};

export function createAdminCampaignDetailColumns(
  actions: AdminCampaignDetailsTableActions,
): ColumnDef<CampaignDetailSummary>[] {
  const { t } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.store?.name ?? row.store_id} ${row.campaign?.name ?? row.campaign_id}`.toLowerCase(),
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
      id: "store",
      accessorFn: (row) => row.store?.name ?? String(row.store_id),
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.campaigns.columns.store")}
        />
      ),
    },
    {
      id: "type",
      accessorFn: (row) => row.campaign?.name ?? String(row.campaign_id),
      header: t("admin.campaigns.columns.type"),
      enableSorting: false,
    },
    {
      accessorKey: "reduction",
      header: t("admin.campaigns.columns.reduction"),
      cell: ({ row }) => `${row.original.reduction}%`,
      enableSorting: false,
    },
    {
      accessorKey: "active",
      header: t("admin.campaigns.columns.status"),
      cell: ({ row }) => (
        <Badge variant={row.original.active === 1 ? "default" : "secondary"}>
          {row.original.active === 1
            ? t("admin.campaigns.statusActive")
            : t("admin.campaigns.statusInactive")}
        </Badge>
      ),
      enableSorting: false,
    },
  ];
}
