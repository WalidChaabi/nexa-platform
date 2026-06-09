"use client";

import { MoreHorizontal } from "lucide-react";

import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

type DataTableRowActionsMenuProps = {
  menuLabel: string;
  children: React.ReactNode;
};

/**
 * Base UI dropdown menus inside TanStack tables must use this wrapper:
 * - trigger is type="button" (not submit)
 * - stopPropagation so row/table handlers do not fire
 * - items live inside DropdownMenuGroup (Label + Item)
 */
export function DataTableRowActionsMenu({
  menuLabel,
  children,
}: DataTableRowActionsMenuProps) {
  return (
    <div className="flex justify-end">
      <DropdownMenu>
        <DropdownMenuTrigger
          render={
            <Button
              type="button"
              variant="ghost"
              className="size-8 p-0"
              onClick={(event) => event.stopPropagation()}
            >
              <MoreHorizontal />
              <span className="sr-only">{menuLabel}</span>
            </Button>
          }
        />
        <DropdownMenuContent align="end">
          <DropdownMenuGroup>{children}</DropdownMenuGroup>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
