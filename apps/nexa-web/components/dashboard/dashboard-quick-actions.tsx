"use client";

import type { LucideIcon } from "lucide-react";
import {
  CalendarDays,
  CreditCard,
  FileBarChart,
  Megaphone,
  PlusCircle,
  Receipt,
  Store,
  UserCog,
  Users,
} from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";

type QuickAction = {
  href: string;
  labelKey: string;
  icon: LucideIcon;
  primary?: boolean;
};

const actionsByRole: Record<
  "admin" | "owner" | "employer",
  QuickAction[]
> = {
  employer: [
    {
      href: "/employer/invoices/create",
      labelKey: "nav.newInvoice",
      icon: PlusCircle,
      primary: true,
    },
    {
      href: "/employer/invoices",
      labelKey: "nav.invoices",
      icon: Receipt,
    },
    {
      href: "/employer/customers",
      labelKey: "nav.customers",
      icon: Users,
    },
    {
      href: "/employer/calendar",
      labelKey: "nav.calendar",
      icon: CalendarDays,
    },
  ],
  owner: [
    {
      href: "/owner/invoices",
      labelKey: "nav.invoices",
      icon: Receipt,
      primary: true,
    },
    {
      href: "/owner/campaigns",
      labelKey: "nav.campaigns",
      icon: Megaphone,
    },
    {
      href: "/owner/reports",
      labelKey: "nav.reports",
      icon: FileBarChart,
    },
    {
      href: "/owner/stores",
      labelKey: "nav.stores",
      icon: Store,
    },
    {
      href: "/owner/payments",
      labelKey: "nav.payments",
      icon: CreditCard,
    },
  ],
  admin: [
    {
      href: "/admin/owners",
      labelKey: "nav.owners",
      icon: UserCog,
      primary: true,
    },
    {
      href: "/admin/stores",
      labelKey: "nav.stores",
      icon: Store,
    },
    {
      href: "/admin/payments",
      labelKey: "nav.payments",
      icon: CreditCard,
    },
    {
      href: "/admin/reports",
      labelKey: "nav.reports",
      icon: FileBarChart,
    },
    {
      href: "/admin/campaigns",
      labelKey: "nav.campaigns",
      icon: Megaphone,
    },
  ],
};

export function DashboardQuickActions({
  role,
}: {
  role: "admin" | "owner" | "employer";
}) {
  const { t } = useLocale();
  const actions = actionsByRole[role];

  return (
    <Card className="border-border/80 shadow-sm">
      <CardHeader className="pb-3">
        <CardTitle className="text-base font-medium">
          {t("dashboard.kpis.quickActions")}
        </CardTitle>
      </CardHeader>
      <CardContent className="flex flex-wrap gap-2">
        {actions.map((action) => {
          const Icon = action.icon;

          return (
            <NavLink
              key={action.href}
              href={action.href}
              variant={action.primary ? "default" : "outline"}
              size="sm"
            >
              <Icon />
              {t(action.labelKey)}
            </NavLink>
          );
        })}
      </CardContent>
    </Card>
  );
}
