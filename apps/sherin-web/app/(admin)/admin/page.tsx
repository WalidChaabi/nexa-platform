"use client";

import {
  CreditCard,
  LayoutDashboard,
  Megaphone,
  Package,
  Shield,
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

export default function AdminDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={LayoutDashboard}
        title={t("admin.dashboard.title")}
        description={t("admin.dashboard.signedIn", { name: user.full_name })}
      />

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Shield className="size-5 text-primary" />
            {t("admin.dashboard.phaseTitle")}
          </CardTitle>
          <CardDescription>{t("admin.dashboard.phaseDescription")}</CardDescription>
        </CardHeader>
        <CardContent className="space-y-4 text-sm text-muted-foreground">
          <p>{t("admin.dashboard.phaseBody")}</p>
          <div className="flex flex-wrap gap-3">
            <NavLink href="/admin/stores" variant="outline">
              <Store />
              {t("nav.stores")}
            </NavLink>
            <NavLink href="/admin/customers" variant="outline">
              <Users />
              {t("nav.customers")}
            </NavLink>
            <NavLink href="/admin/products" variant="outline">
              <Package />
              {t("nav.products")}
            </NavLink>
            <NavLink href="/admin/payments" variant="outline">
              <CreditCard />
              {t("nav.payments")}
            </NavLink>
            <NavLink href="/admin/campaigns" variant="outline">
              <Megaphone />
              {t("nav.campaigns")}
            </NavLink>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
