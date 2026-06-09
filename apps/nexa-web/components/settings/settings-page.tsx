"use client";

import { Settings } from "lucide-react";

import { AccountSettingsPanel } from "@/components/settings/account-settings-panel";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";

export function SettingsPage() {
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Settings}
        title={t("settings.title")}
        description={t("settings.description")}
      />
      <AccountSettingsPanel />
    </div>
  );
}
