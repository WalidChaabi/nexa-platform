"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Plus, UserCheck } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { EmployerFormDialog } from "@/components/employers/employer-form-dialog";
import { createEmployersColumns } from "@/components/employers/employers-columns";
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
  activateEmployer,
  deactivateEmployer,
  fetchEmployers,
  type EmployerSummary,
} from "@/lib/api/employers";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

type DialogState =
  | { open: false }
  | { open: true; mode: "create" }
  | { open: true; mode: "edit"; employer: EmployerSummary };

export default function OwnerTeamPage() {
  const { t } = useLocale();
  const [employers, setEmployers] = useState<EmployerSummary[]>([]);
  const [stores, setStores] = useState<StoreSummary[]>([]);
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
        const [employerData, storeData] = await Promise.all([
          fetchEmployers(),
          fetchStores(),
        ]);

        if (active) {
          setEmployers(employerData);
          setStores(storeData);
        }
      } catch {
        if (active) {
          setErrorKey("owner.team.loadError");
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

  const handleToggleActive = useCallback(async (employer: EmployerSummary) => {
    setErrorKey(null);

    try {
      if (employer.is_active === 1) {
        await deactivateEmployer(employer.id);
      } else {
        await activateEmployer(employer.id);
      }
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.team.statusError");
    }
  }, []);

  const columns = useMemo(
    () =>
      createEmployersColumns({
        t,
        onEdit: (employer) =>
          setDialog({ open: true, mode: "edit", employer }),
        onToggleActive: handleToggleActive,
      }),
    [t, handleToggleActive],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={UserCheck}
        title={t("owner.team.title")}
        description={t("owner.team.description")}
        action={
          <Button
            onClick={() => setDialog({ open: true, mode: "create" })}
            disabled={stores.length === 0}
          >
            <Plus />
            {t("owner.team.invite")}
          </Button>
        }
      />

      {stores.length === 0 && !fetching ? (
        <Alert>
          <AlertTitle>{t("owner.team.noStoresTitle")}</AlertTitle>
          <AlertDescription>{t("owner.team.noStoresDescription")}</AlertDescription>
        </Alert>
      ) : null}

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.team.listTitle")}</CardTitle>
          <CardDescription>{t("owner.team.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={employers}
              filterColumnId="search"
              filterPlaceholder={t("owner.team.filterPlaceholder")}
              emptyMessage={t("owner.team.empty")}
            />
          )}
        </CardContent>
      </Card>

      <EmployerFormDialog
        open={dialog.open}
        onOpenChange={(open) => {
          if (!open) {
            setDialog({ open: false });
          }
        }}
        mode={dialog.open ? dialog.mode : "create"}
        employer={dialog.open && dialog.mode === "edit" ? dialog.employer : null}
        stores={stores}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />
    </div>
  );
}
