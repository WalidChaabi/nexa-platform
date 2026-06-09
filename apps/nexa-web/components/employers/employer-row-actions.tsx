"use client";

import { Pencil, UserMinus, UserPlus } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { EmployerSummary } from "@/lib/api/employers";

type EmployerRowActionsProps = {
  employer: EmployerSummary;
  onEdit: (employer: EmployerSummary) => void;
  onToggleActive: (employer: EmployerSummary) => void;
};

export function EmployerRowActions({
  employer,
  onEdit,
  onToggleActive,
}: EmployerRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("owner.team.openMenu")}>
      <DropdownMenuLabel>{t("owner.team.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(employer)}>
        <Pencil />
        {t("owner.team.edit")}
      </DropdownMenuItem>
      <DropdownMenuItem onClick={() => onToggleActive(employer)}>
        {employer.is_active === 1 ? (
          <>
            <UserMinus />
            {t("owner.team.deactivate")}
          </>
        ) : (
          <>
            <UserPlus />
            {t("owner.team.activate")}
          </>
        )}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
