"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { BrandLogo } from "@/components/brand/brand-logo";
import { AuthLoading } from "@/components/layout/auth-loading";
import { DashboardAuthProvider } from "@/components/layout/dashboard-auth";
import { PreferencesToolbar } from "@/components/layout/preferences-toolbar";
import { SidebarUserFooter } from "@/components/layout/sidebar-user-footer";
import { useLocale } from "@/components/providers/locale-provider";
import { Separator } from "@/components/ui/separator";
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarInset,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarProvider,
  SidebarRail,
  SidebarTrigger,
} from "@/components/ui/sidebar";
import { useRequireAuth } from "@/hooks/use-require-auth";
import type { AuthUser } from "@/lib/api/auth";
import { isNavActive, type NavItem } from "@/lib/nav-config";

type DashboardLayoutProps = {
  role: AuthUser["role"];
  navItems: NavItem[];
  workspaceKey: string;
  children: React.ReactNode;
};

function AppSidebar({
  user,
  navItems,
  workspaceLabel,
}: {
  user: AuthUser;
  navItems: NavItem[];
  workspaceLabel: string;
}) {
  const pathname = usePathname();
  const { t } = useLocale();

  return (
    <Sidebar collapsible="icon" variant="inset">
      <SidebarHeader>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton
              size="lg"
              render={<Link href={user.dashboard_path} />}
            >
              <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary p-1.5 text-primary-foreground">
                <BrandLogo variant="on-primary" />
              </div>
              <div className="grid flex-1 text-start text-sm leading-tight">
                <span className="truncate font-semibold">
                  {t("common.appName")}
                </span>
                <span className="truncate text-xs text-muted-foreground">
                  {workspaceLabel}
                </span>
              </div>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel>{t("common.navigation")}</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {navItems.map((item) => (
                <SidebarMenuItem key={item.href}>
                  <SidebarMenuButton
                    isActive={isNavActive(pathname, item)}
                    tooltip={t(item.titleKey)}
                    render={<Link href={item.href} />}
                  >
                    <item.icon />
                    <span>{t(item.titleKey)}</span>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>
      <SidebarUserFooter user={user} />
      <SidebarRail />
    </Sidebar>
  );
}

export function DashboardLayout({
  role,
  navItems,
  workspaceKey,
  children,
}: DashboardLayoutProps) {
  const pathname = usePathname();
  const { user, loading } = useRequireAuth(role);
  const { t } = useLocale();
  const isPrintView = pathname.endsWith("/print");
  const workspaceLabel = t(workspaceKey);

  if (loading) {
    return (
      <SidebarProvider>
        <div className="flex min-h-svh w-full">
          <div className="hidden w-64 shrink-0 border-r bg-sidebar md:block" />
          <AuthLoading />
        </div>
      </SidebarProvider>
    );
  }

  if (!user) {
    return null;
  }

  if (isPrintView) {
    return (
      <DashboardAuthProvider user={user}>{children}</DashboardAuthProvider>
    );
  }

  return (
    <DashboardAuthProvider user={user}>
      <SidebarProvider>
        <AppSidebar
          user={user}
          navItems={navItems}
          workspaceLabel={workspaceLabel}
        />
        <SidebarInset>
          <header className="flex h-14 shrink-0 items-center gap-2 border-b px-4">
            <SidebarTrigger className="-ml-1" />
            <Separator
              orientation="vertical"
              className="mr-2 hidden h-4 sm:block"
            />
            <div className="flex flex-1 items-center justify-between gap-2">
              <p className="truncate text-sm text-muted-foreground">
                {user.store_id
                  ? t("common.storeNumber", { id: user.store_id })
                  : t("common.platform")}
              </p>
              <PreferencesToolbar />
            </div>
          </header>
          <main className="flex flex-1 flex-col gap-6 p-6 md:p-8">
            {children}
          </main>
        </SidebarInset>
      </SidebarProvider>
    </DashboardAuthProvider>
  );
}
