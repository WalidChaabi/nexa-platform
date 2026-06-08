"use client";

import { Building2 } from "lucide-react";

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

export default function OwnerDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
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
        <CardContent className="text-sm text-muted-foreground">
          {t("owner.dashboard.opsBody")}
        </CardContent>
      </Card>
    </div>
  );
}
