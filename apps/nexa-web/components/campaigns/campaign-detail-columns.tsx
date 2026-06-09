"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { CampaignDetailRowActions } from "@/components/campaigns/campaign-detail-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { CampaignDetailSummary } from "@/lib/api/campaigns";

export type CampaignDetailsTableActions = {
  t: (key: string) => string;
  onEdit: (detail: CampaignDetailSummary) => void;
  onToggleActive: (detail: CampaignDetailSummary) => void;
  onDelete: (detail: CampaignDetailSummary) => void;
};

export function createCampaignDetailColumns(
  actions: CampaignDetailsTableActions,
): ColumnDef<CampaignDetailSummary>[] {
  const { t, onEdit, onToggleActive, onDelete } = actions;

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
          title={t("owner.campaigns.columns.store")}
        />
      ),
      cell: ({ row }) => row.original.store?.name ?? row.original.store_id,
    },
    {
      id: "type",
      accessorFn: (row) => row.campaign?.name ?? String(row.campaign_id),
      header: t("owner.campaigns.columns.type"),
      cell: ({ row }) => row.original.campaign?.name ?? row.original.campaign_id,
      enableSorting: false,
    },
    {
      accessorKey: "reduction",
      header: t("owner.campaigns.columns.reduction"),
      cell: ({ row }) => `${row.original.reduction}%`,
      enableSorting: false,
    },
    {
      accessorKey: "active",
      header: t("owner.campaigns.columns.status"),
      cell: ({ row }) => (
        <Badge variant={row.original.active === 1 ? "default" : "secondary"}>
          {row.original.active === 1
            ? t("owner.campaigns.statusActive")
            : t("owner.campaigns.statusInactive")}
        </Badge>
      ),
      enableSorting: false,
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("owner.campaigns.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <CampaignDetailRowActions
          detail={row.original}
          onEdit={onEdit}
          onToggleActive={onToggleActive}
          onDelete={onDelete}
        />
      ),
    },
  ];
}
