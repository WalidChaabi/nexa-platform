"use client";

import { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { ChevronsUpDown, LogOut, Settings } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
  SidebarFooter,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar";
import { logout, type AuthUser } from "@/lib/api/auth";
import { settingsPathForRole } from "@/lib/settings-path";

function initials(user: AuthUser): string {
  const parts = [user.firstname, user.lastname]
    .filter(Boolean)
    .map((part) => part.charAt(0).toUpperCase());

  if (parts.length > 0) {
    return parts.join("").slice(0, 2);
  }

  return user.email.charAt(0).toUpperCase();
}

export function SidebarUserFooter({ user }: { user: AuthUser }) {
  const router = useRouter();
  const { t } = useLocale();
  const [loading, setLoading] = useState(false);

  async function handleLogout() {
    setLoading(true);
    try {
      await logout();
      router.replace("/login");
      router.refresh();
    } finally {
      setLoading(false);
    }
  }

  return (
    <SidebarFooter>
      <SidebarMenu>
        <SidebarMenuItem>
          <DropdownMenu>
            <DropdownMenuTrigger
              render={
                <SidebarMenuButton
                  size="lg"
                  className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                >
                  <Avatar className="size-8 rounded-lg">
                    <AvatarFallback className="rounded-lg bg-primary/10 text-primary">
                      {initials(user)}
                    </AvatarFallback>
                  </Avatar>
                  <div className="grid flex-1 text-start text-sm leading-tight">
                    <span className="truncate font-semibold">
                      {user.full_name}
                    </span>
                    <span className="truncate text-xs text-muted-foreground">
                      {user.email}
                    </span>
                  </div>
                  <ChevronsUpDown className="ms-auto size-4" />
                </SidebarMenuButton>
              }
            />
            <DropdownMenuContent
              className="w-(--anchor-width) min-w-56 rounded-lg"
              side="bottom"
              align="end"
              sideOffset={4}
            >
              <DropdownMenuGroup>
                <DropdownMenuLabel className="p-0 font-normal">
                  <div className="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                    <Avatar className="size-8 rounded-lg">
                      <AvatarFallback className="rounded-lg bg-primary/10 text-primary">
                        {initials(user)}
                      </AvatarFallback>
                    </Avatar>
                    <div className="grid flex-1 text-start text-sm leading-tight">
                      <span className="truncate font-semibold">
                        {user.full_name}
                      </span>
                      <span className="truncate text-xs text-muted-foreground">
                        {user.email}
                      </span>
                    </div>
                  </div>
                </DropdownMenuLabel>
              </DropdownMenuGroup>
              <DropdownMenuSeparator />
              <DropdownMenuGroup>
                <DropdownMenuItem
                  render={
                    <Link href={settingsPathForRole(user.role)} />
                  }
                >
                  <Settings />
                  {t("nav.settings")}
                </DropdownMenuItem>
              </DropdownMenuGroup>
              <DropdownMenuSeparator />
              <DropdownMenuGroup>
                <DropdownMenuItem
                  variant="destructive"
                  disabled={loading}
                  onClick={() => void handleLogout()}
                >
                  <LogOut />
                  {loading ? t("common.signingOut") : t("common.signOut")}
                </DropdownMenuItem>
              </DropdownMenuGroup>
            </DropdownMenuContent>
          </DropdownMenu>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarFooter>
  );
}
