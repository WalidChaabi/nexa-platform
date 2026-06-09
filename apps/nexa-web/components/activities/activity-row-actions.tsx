"use client";

import { Pencil, Trash2 } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { ActivitySummary } from "@/lib/api/activities";

type ActivityRowActionsProps = {
  activity: ActivitySummary;
  onEdit: (activity: ActivitySummary) => void;
  onDelete: (activity: ActivitySummary) => void;
};

export function ActivityRowActions({
  activity,
  onEdit,
  onDelete,
}: ActivityRowActionsProps) {
  const { t } = useLocale();
  const canDelete = (activity.stores_count ?? 0) === 0;

  return (
    <DataTableRowActionsMenu menuLabel={t("admin.activities.openMenu")}>
      <DropdownMenuLabel>{t("admin.activities.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(activity)}>
        <Pencil />
        {t("admin.activities.edit")}
      </DropdownMenuItem>
      <DropdownMenuItem
        disabled={!canDelete}
        onClick={() => onDelete(activity)}
      >
        <Trash2 />
        {t("admin.activities.delete")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
