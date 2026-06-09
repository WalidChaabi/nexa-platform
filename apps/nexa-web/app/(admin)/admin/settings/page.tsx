"use client";

import { Settings } from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { LocaleToggle } from "@/components/layout/locale-toggle";
import { PageHeader } from "@/components/layout/page-header";
import { ThemeToggle } from "@/components/layout/theme-toggle";
import { useLocale } from "@/components/providers/locale-provider";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

export default function AdminSettingsPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Settings}
        title={t("admin.settings.title")}
        description={t("admin.settings.description")}
      />

      <div className="grid gap-4 lg:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle>{t("admin.settings.accountTitle")}</CardTitle>
            <CardDescription>
              {t("admin.settings.accountDescription")}
            </CardDescription>
          </CardHeader>
          <CardContent className="space-y-3 text-sm">
            <div className="flex justify-between gap-4">
              <span className="text-muted-foreground">{t("auth.email")}</span>
              <span className="font-medium">{user.email}</span>
            </div>
            <div className="flex justify-between gap-4">
              <span className="text-muted-foreground">
                {t("common.account", { role: t(`roles.${user.role}`) })}
              </span>
              <span className="font-medium">{user.full_name}</span>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>{t("admin.settings.preferencesTitle")}</CardTitle>
            <CardDescription>
              {t("admin.settings.preferencesDescription")}
            </CardDescription>
          </CardHeader>
          <CardContent className="flex flex-wrap items-center gap-3">
            <LocaleToggle />
            <ThemeToggle />
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
