"use client";

import { useEffect, useState } from "react";
import { List, Pencil, Plus, Store } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Skeleton } from "@/components/ui/skeleton";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  createStore,
  fetchStores,
  updateStore,
  type StoreInput,
  type StoreSummary,
} from "@/lib/api/stores";

const emptyForm: StoreInput = {
  name: "",
  phone: "",
  address: "",
};

export default function OwnerStoresPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [createForm, setCreateForm] = useState<StoreInput>(emptyForm);
  const [creating, setCreating] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [editForm, setEditForm] = useState<StoreInput>(emptyForm);
  const [savingEdit, setSavingEdit] = useState(false);

  useEffect(() => {
    let active = true;

    async function loadStores() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchStores();
        if (active) {
          setStores(data);
        }
      } catch {
        if (active) {
          setErrorKey("owner.stores.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadStores();
  }, [reloadKey]);

  function startEdit(store: StoreSummary) {
    setEditingId(store.id);
    setEditForm({
      name: store.name,
      phone: store.phone,
      address: store.address ?? "",
      invoice_message: store.invoice_message ?? "",
    });
  }

  async function handleCreate(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setCreating(true);
    setErrorKey(null);

    try {
      await createStore(createForm);
      setCreateForm(emptyForm);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.stores.createError");
    } finally {
      setCreating(false);
    }
  }

  async function handleUpdate(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (editingId == null) {
      return;
    }

    setSavingEdit(true);
    setErrorKey(null);

    try {
      await updateStore(editingId, editForm);
      setEditingId(null);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.stores.updateError");
    } finally {
      setSavingEdit(false);
    }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Store}
        title={t("owner.stores.title")}
        description={t("owner.stores.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Plus className="size-4 text-primary" />
            {t("owner.stores.createTitle")}
          </CardTitle>
          <CardDescription>{t("owner.stores.createDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 sm:grid-cols-2" onSubmit={handleCreate}>
            <div className="space-y-2">
              <Label htmlFor="create-name">{t("owner.stores.fields.name")}</Label>
              <Input
                id="create-name"
                required
                value={createForm.name}
                onChange={(event) =>
                  setCreateForm((value) => ({
                    ...value,
                    name: event.target.value,
                  }))
                }
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="create-phone">{t("owner.stores.fields.phone")}</Label>
              <Input
                id="create-phone"
                value={createForm.phone ?? ""}
                onChange={(event) =>
                  setCreateForm((value) => ({
                    ...value,
                    phone: event.target.value,
                  }))
                }
              />
            </div>
            <div className="space-y-2 sm:col-span-2">
              <Label htmlFor="create-address">
                {t("owner.stores.fields.address")}
              </Label>
              <Input
                id="create-address"
                value={createForm.address ?? ""}
                onChange={(event) =>
                  setCreateForm((value) => ({
                    ...value,
                    address: event.target.value,
                  }))
                }
              />
            </div>
            <div className="sm:col-span-2">
              <Button type="submit" disabled={creating}>
                <Plus />
                {creating ? t("owner.stores.creating") : t("owner.stores.create")}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      {editingId != null ? (
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Pencil className="size-4 text-primary" />
              {t("owner.stores.editTitle")}
            </CardTitle>
            <CardDescription>{t("owner.stores.editDescription")}</CardDescription>
          </CardHeader>
          <CardContent>
            <form className="grid gap-4 sm:grid-cols-2" onSubmit={handleUpdate}>
              <div className="space-y-2">
                <Label htmlFor="edit-name">{t("owner.stores.fields.name")}</Label>
                <Input
                  id="edit-name"
                  required
                  value={editForm.name}
                  onChange={(event) =>
                    setEditForm((value) => ({
                      ...value,
                      name: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-phone">{t("owner.stores.fields.phone")}</Label>
                <Input
                  id="edit-phone"
                  value={editForm.phone ?? ""}
                  onChange={(event) =>
                    setEditForm((value) => ({
                      ...value,
                      phone: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-2 sm:col-span-2">
                <Label htmlFor="edit-address">
                  {t("owner.stores.fields.address")}
                </Label>
                <Input
                  id="edit-address"
                  value={editForm.address ?? ""}
                  onChange={(event) =>
                    setEditForm((value) => ({
                      ...value,
                      address: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="flex gap-2 sm:col-span-2">
                <Button type="submit" disabled={savingEdit}>
                  {savingEdit ? t("owner.stores.saving") : t("owner.stores.save")}
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  onClick={() => setEditingId(null)}
                >
                  {t("common.cancel")}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.stores.listTitle")}
          </CardTitle>
          <CardDescription>{t("owner.stores.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : stores.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.stores.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.stores.columns.name")}</TableHead>
                  <TableHead>{t("owner.stores.columns.phone")}</TableHead>
                  <TableHead>{t("owner.stores.columns.status")}</TableHead>
                  <TableHead className="text-end">
                    {t("owner.stores.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {stores.map((store) => (
                  <TableRow key={store.id}>
                    <TableCell className="font-medium">{store.name}</TableCell>
                    <TableCell>{store.phone || "—"}</TableCell>
                    <TableCell>
                      <Badge variant={store.status === 1 ? "default" : "secondary"}>
                        {store.status === 1
                          ? t("owner.stores.statusActive")
                          : t("owner.stores.statusInactive")}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-end">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => startEdit(store)}
                      >
                        <Pencil />
                        {t("owner.stores.edit")}
                      </Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
