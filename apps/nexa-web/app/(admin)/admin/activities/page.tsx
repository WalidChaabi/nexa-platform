"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Briefcase, Plus } from "lucide-react";

import { ActivityFormDialog } from "@/components/activities/activity-form-dialog";
import { createActivitiesColumns } from "@/components/activities/activities-columns";
import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import {
  deleteActivity,
  fetchActivities,
  type ActivitySummary,
} from "@/lib/api/activities";

type DialogState =
  | { open: false }
  | { open: true; mode: "create" }
  | { open: true; mode: "edit"; activity: ActivitySummary };

export default function AdminActivitiesPage() {
  const { t } = useLocale();
  const [activities, setActivities] = useState<ActivitySummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [dialog, setDialog] = useState<DialogState>({ open: false });

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchActivities();
        if (active) {
          setActivities(data);
        }
      } catch {
        if (active) {
          setErrorKey("admin.activities.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, [reloadKey]);

  const handleDelete = useCallback(async (activity: ActivitySummary) => {
    setErrorKey(null);

    try {
      await deleteActivity(activity.id);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("admin.activities.deleteError");
    }
  }, []);

  const columns = useMemo(
    () =>
      createActivitiesColumns({
        t,
        onEdit: (activity) => setDialog({ open: true, mode: "edit", activity }),
        onDelete: (activity) => void handleDelete(activity),
      }),
    [t, handleDelete],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Briefcase}
        title={t("admin.activities.title")}
        description={t("admin.activities.description")}
      >
        <Button onClick={() => setDialog({ open: true, mode: "create" })}>
          <Plus />
          {t("admin.activities.create")}
        </Button>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <ActivityFormDialog
        open={dialog.open}
        mode={dialog.open ? dialog.mode : "create"}
        activity={
          dialog.open && dialog.mode === "edit" ? dialog.activity : null
        }
        onOpenChange={(open) => {
          if (!open) {
            setDialog({ open: false });
          }
        }}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("admin.activities.listTitle")}</CardTitle>
          <CardDescription>{t("admin.activities.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={activities}
              filterColumn="search"
              filterPlaceholder={t("admin.activities.filterPlaceholder")}
              emptyMessage={t("admin.activities.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
