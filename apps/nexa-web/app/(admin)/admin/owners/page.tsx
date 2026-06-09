"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Plus, UserCog } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { useConfirm } from "@/components/feedback/confirm-provider";
import { OwnerFormDialog } from "@/components/owners/owner-form-dialog";
import { createOwnersColumns } from "@/components/owners/owners-columns";
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
import { useAppToast } from "@/hooks/use-app-toast";
import {
  activateOwner,
  deactivateOwner,
  fetchOwners,
  type OwnerSummary,
} from "@/lib/api/owners";

type DialogState =
  | { open: false }
  | { open: true; mode: "create" }
  | { open: true; mode: "edit"; owner: OwnerSummary };

export default function AdminOwnersPage() {
  const { t } = useLocale();
  const confirm = useConfirm();
  const toast = useAppToast();
  const [owners, setOwners] = useState<OwnerSummary[]>([]);
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
        const data = await fetchOwners();
        if (active) {
          setOwners(data);
        }
      } catch {
        if (active) {
          setErrorKey("admin.owners.loadError");
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

  const handleToggleActive = useCallback(
    (owner: OwnerSummary) => {
      if (owner.is_active !== 1) {
        void (async () => {
          setErrorKey(null);

          try {
            await activateOwner(owner.id);
            setReloadKey((value) => value + 1);
            toast.success("feedback.toast.updated");
          } catch {
            setErrorKey("admin.owners.statusError");
          }
        })();
        return;
      }

      confirm({
        title: t("feedback.confirm.deactivateOwner.title"),
        description: t("feedback.confirm.deactivateOwner.description"),
        confirmLabel: t("feedback.confirm.deactivate"),
        destructive: true,
        onConfirm: async () => {
          setErrorKey(null);

          try {
            await deactivateOwner(owner.id);
            setReloadKey((value) => value + 1);
            toast.success("feedback.toast.deactivated");
          } catch {
            setErrorKey("admin.owners.statusError");
          }
        },
      });
    },
    [confirm, t, toast],
  );

  const columns = useMemo(
    () =>
      createOwnersColumns({
        t,
        onEdit: (owner) => setDialog({ open: true, mode: "edit", owner }),
        onToggleActive: (owner) => void handleToggleActive(owner),
      }),
    [t, handleToggleActive],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={UserCog}
        title={t("admin.owners.title")}
        description={t("admin.owners.description")}
      >
        <Button onClick={() => setDialog({ open: true, mode: "create" })}>
          <Plus />
          {t("admin.owners.create")}
        </Button>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <OwnerFormDialog
        open={dialog.open}
        mode={dialog.open ? dialog.mode : "create"}
        owner={dialog.open && dialog.mode === "edit" ? dialog.owner : null}
        onOpenChange={(open) => {
          if (!open) {
            setDialog({ open: false });
          }
        }}
        onSuccess={() => {
          setReloadKey((value) => value + 1);
        }}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("admin.owners.listTitle")}</CardTitle>
          <CardDescription>{t("admin.owners.listDescription")}</CardDescription>
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
              data={owners}
              filterColumn="search"
              filterPlaceholder={t("admin.owners.filterPlaceholder")}
              emptyMessage={t("admin.owners.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
