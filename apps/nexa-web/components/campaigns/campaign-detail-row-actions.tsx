"use client";

import { Pencil, Power, Trash2 } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { CampaignDetailSummary } from "@/lib/api/campaigns";

type CampaignDetailRowActionsProps = {
  detail: CampaignDetailSummary;
  onEdit: (detail: CampaignDetailSummary) => void;
  onToggleActive: (detail: CampaignDetailSummary) => void;
  onDelete: (detail: CampaignDetailSummary) => void;
};

export function CampaignDetailRowActions({
  detail,
  onEdit,
  onToggleActive,
  onDelete,
}: CampaignDetailRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("owner.campaigns.openMenu")}>
      <DropdownMenuLabel>{t("owner.campaigns.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(detail)}>
        <Pencil />
        {t("owner.campaigns.editTitle")}
      </DropdownMenuItem>
      <DropdownMenuItem onClick={() => onToggleActive(detail)}>
        <Power />
        {detail.active === 1
          ? t("owner.campaigns.deactivate")
          : t("owner.campaigns.activate")}
      </DropdownMenuItem>
      <DropdownMenuItem onClick={() => onDelete(detail)}>
        <Trash2 />
        {t("owner.campaigns.delete")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
