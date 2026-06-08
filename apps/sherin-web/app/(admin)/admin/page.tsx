"use client";

import { Shield } from "lucide-react";

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

export default function AdminDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
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
        <CardContent className="text-sm text-muted-foreground">
          {t("admin.dashboard.phaseBody")}
        </CardContent>
      </Card>
    </div>
  );
}
