"use client";

import { Pencil, Trash2 } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { AppointmentSummary } from "@/lib/api/rdvs";

type AppointmentRowActionsProps = {
  appointment: AppointmentSummary;
  translationPrefix: "owner.calendar" | "employer.calendar";
  onEdit: (appointment: AppointmentSummary) => void;
  onDelete: (appointment: AppointmentSummary) => void;
};

export function AppointmentRowActions({
  appointment,
  translationPrefix,
  onEdit,
  onDelete,
}: AppointmentRowActionsProps) {
  const { t } = useLocale();
  const prefix = translationPrefix;

  return (
    <DataTableRowActionsMenu menuLabel={t(`${prefix}.openMenu`)}>
      <DropdownMenuLabel>{t(`${prefix}.columns.actions`)}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(appointment)}>
        <Pencil />
        {t(`${prefix}.editTitle`)}
      </DropdownMenuItem>
      <DropdownMenuItem onClick={() => onDelete(appointment)}>
        <Trash2 />
        {t(`${prefix}.delete`)}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
