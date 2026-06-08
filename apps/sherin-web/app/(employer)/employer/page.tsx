"use client";

import {
  CalendarDays,
  LayoutDashboard,
  Package,
  PlusCircle,
  Receipt,
  Store,
  Users,
} from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { NavLink } from "@/components/ui/nav-link";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

export default function EmployerDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={LayoutDashboard}
        title={t("employer.dashboard.title")}
        description={t("employer.dashboard.signedIn", { name: user.full_name })}
      />

      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>{t("employer.dashboard.store")}</CardDescription>
            <CardTitle className="text-2xl">
              {user.store_id ? `#${user.store_id}` : "—"}
            </CardTitle>
          </CardHeader>
          <CardContent className="flex items-center gap-2 text-sm text-muted-foreground">
            <Store className="size-4" />
            {t("employer.dashboard.assignedLocation")}
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>{t("employer.dashboard.quickAction")}</CardDescription>
            <CardTitle className="text-lg">
              {t("employer.dashboard.newSale")}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <NavLink href="/employer/invoices/create" className="w-full">
              <PlusCircle />
              {t("employer.dashboard.createInvoice")}
            </NavLink>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>{t("employer.dashboard.history")}</CardDescription>
            <CardTitle className="text-lg">{t("nav.invoices")}</CardTitle>
          </CardHeader>
          <CardContent>
            <NavLink
              href="/employer/invoices"
              variant="outline"
              className="w-full"
            >
              <Receipt />
              {t("employer.dashboard.viewAllInvoices")}
            </NavLink>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>{t("employer.dashboard.posTitle")}</CardTitle>
          <CardDescription>{t("employer.dashboard.posDescription")}</CardDescription>
        </CardHeader>
        <CardContent className="flex flex-wrap gap-3">
          <NavLink href="/employer/invoices/create">
            <PlusCircle />
            {t("nav.newInvoice")}
          </NavLink>
          <NavLink href="/employer/invoices" variant="outline">
            <Receipt />
            {t("employer.dashboard.invoiceHistory")}
          </NavLink>
          <NavLink href="/employer/customers" variant="outline">
            <Users />
            {t("nav.customers")}
          </NavLink>
          <NavLink href="/employer/products" variant="outline">
            <Package />
            {t("nav.products")}
          </NavLink>
          <NavLink href="/employer/calendar" variant="outline">
            <CalendarDays />
            {t("nav.calendar")}
          </NavLink>
        </CardContent>
      </Card>
    </div>
  );
}
