"use client";

import {
  Building2,
  CreditCard,
  LayoutDashboard,
  Megaphone,
  Package,
  Receipt,
  Store,
  Users,
} from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";

export default function OwnerDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={LayoutDashboard}
        title={t("owner.dashboard.title")}
        description={t("owner.dashboard.signedIn", { name: user.full_name })}
      />

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Building2 className="size-5 text-primary" />
            {t("owner.dashboard.opsTitle")}
          </CardTitle>
          <CardDescription>{t("owner.dashboard.opsDescription")}</CardDescription>
        </CardHeader>
        <CardContent className="space-y-4 text-sm text-muted-foreground">
          <p>{t("owner.dashboard.opsBody")}</p>
          <div className="flex flex-wrap gap-3">
            <NavLink href="/owner/invoices" variant="outline">
              <Receipt />
              {t("nav.invoices")}
            </NavLink>
            <NavLink href="/owner/payments" variant="outline">
              <CreditCard />
              {t("nav.payments")}
            </NavLink>
            <NavLink href="/owner/campaigns" variant="outline">
              <Megaphone />
              {t("nav.campaigns")}
            </NavLink>
            <NavLink href="/owner/stores" variant="outline">
              <Store />
              {t("nav.stores")}
            </NavLink>
            <NavLink href="/owner/customers" variant="outline">
              <Users />
              {t("nav.customers")}
            </NavLink>
            <NavLink href="/owner/products" variant="outline">
              <Package />
              {t("nav.products")}
            </NavLink>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
