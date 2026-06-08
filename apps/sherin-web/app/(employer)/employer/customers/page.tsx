"use client";

import { useEffect, useState } from "react";
import { List, Pencil, Plus, Search, UserPlus, Users } from "lucide-react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
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
  createCustomer,
  fetchCustomers,
  updateCustomer,
  type CustomerInput,
  type CustomerSummary,
  type CustomerUpdateInput,
} from "@/lib/api/customers";

const emptyCreate: Omit<CustomerInput, "store_id"> = {
  phone: "",
  firstname: "",
  lastname: "",
};

export default function EmployerCustomersPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [reloadKey, setReloadKey] = useState(0);
  const [createForm, setCreateForm] = useState(emptyCreate);
  const [creating, setCreating] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [editForm, setEditForm] = useState<CustomerUpdateInput>({});
  const [savingEdit, setSavingEdit] = useState(false);

  const storeId = user.store_id;

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    let active = true;

    async function loadCustomers() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCustomers({
          store_id: activeStoreId,
          q: search.trim() || undefined,
        });
        if (active) {
          setCustomers(data);
        }
      } catch {
        if (active) {
          setErrorKey("employer.customers.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadCustomers();
  }, [storeId, reloadKey, search]);

  function startEdit(customer: CustomerSummary) {
    setEditingId(customer.id);
    setEditForm({
      phone: customer.phone,
      firstname: customer.firstname ?? "",
      lastname: customer.lastname ?? "",
      email: customer.email ?? "",
    });
  }

  async function handleCreate(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (storeId == null) {
      return;
    }

    setCreating(true);
    setErrorKey(null);

    try {
      await createCustomer({ store_id: storeId, ...createForm });
      setCreateForm(emptyCreate);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.customers.createError");
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
      await updateCustomer(editingId, editForm);
      setEditingId(null);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.customers.updateError");
    } finally {
      setSavingEdit(false);
    }
  }

  if (storeId == null) {
    return (
      <Alert variant="destructive">
        <AlertTitle>{t("common.error")}</AlertTitle>
        <AlertDescription>{t("employer.customers.noStore")}</AlertDescription>
      </Alert>
    );
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Users}
        title={t("employer.customers.title")}
        description={t("employer.customers.description")}
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
            <Search className="size-4 text-primary" />
            {t("employer.customers.searchTitle")}
          </CardTitle>
          <CardDescription>{t("employer.customers.searchDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <Input
            placeholder={t("employer.customers.searchPlaceholder")}
            value={search}
            onChange={(event) => setSearch(event.target.value)}
          />
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <UserPlus className="size-4 text-primary" />
            {t("employer.customers.createTitle")}
          </CardTitle>
          <CardDescription>{t("employer.customers.createDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 sm:grid-cols-2" onSubmit={handleCreate}>
            <div className="space-y-2">
              <Label htmlFor="create-phone">{t("employer.customers.fields.phone")}</Label>
              <Input
                id="create-phone"
                required
                value={createForm.phone}
                onChange={(event) =>
                  setCreateForm((value) => ({
                    ...value,
                    phone: event.target.value,
                  }))
                }
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="create-firstname">
                {t("employer.customers.fields.firstname")}
              </Label>
              <Input
                id="create-firstname"
                value={createForm.firstname ?? ""}
                onChange={(event) =>
                  setCreateForm((value) => ({
                    ...value,
                    firstname: event.target.value,
                  }))
                }
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="create-lastname">
                {t("employer.customers.fields.lastname")}
              </Label>
              <Input
                id="create-lastname"
                value={createForm.lastname ?? ""}
                onChange={(event) =>
                  setCreateForm((value) => ({
                    ...value,
                    lastname: event.target.value,
                  }))
                }
              />
            </div>
            <div className="sm:col-span-2">
              <Button type="submit" disabled={creating}>
                <Plus />
                {creating
                  ? t("employer.customers.creating")
                  : t("employer.customers.create")}
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
              {t("employer.customers.editTitle")}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form className="grid gap-4 sm:grid-cols-2" onSubmit={handleUpdate}>
              <div className="space-y-2">
                <Label htmlFor="edit-phone">{t("employer.customers.fields.phone")}</Label>
                <Input
                  id="edit-phone"
                  required
                  value={editForm.phone ?? ""}
                  onChange={(event) =>
                    setEditForm((value) => ({
                      ...value,
                      phone: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-firstname">
                  {t("employer.customers.fields.firstname")}
                </Label>
                <Input
                  id="edit-firstname"
                  value={editForm.firstname ?? ""}
                  onChange={(event) =>
                    setEditForm((value) => ({
                      ...value,
                      firstname: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-lastname">
                  {t("employer.customers.fields.lastname")}
                </Label>
                <Input
                  id="edit-lastname"
                  value={editForm.lastname ?? ""}
                  onChange={(event) =>
                    setEditForm((value) => ({
                      ...value,
                      lastname: event.target.value,
                    }))
                  }
                />
              </div>
              <div className="flex gap-2 sm:col-span-2">
                <Button type="submit" disabled={savingEdit}>
                  {savingEdit
                    ? t("employer.customers.saving")
                    : t("employer.customers.save")}
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
            {t("employer.customers.listTitle")}
          </CardTitle>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : customers.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("employer.customers.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("employer.customers.columns.name")}</TableHead>
                  <TableHead>{t("employer.customers.columns.phone")}</TableHead>
                  <TableHead>{t("employer.customers.columns.points")}</TableHead>
                  <TableHead className="text-end">
                    {t("employer.customers.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {customers.map((customer) => (
                  <TableRow key={customer.id}>
                    <TableCell className="font-medium">
                      {customer.display_name}
                    </TableCell>
                    <TableCell>{customer.phone}</TableCell>
                    <TableCell>{customer.points ?? "0"}</TableCell>
                    <TableCell className="text-end">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => startEdit(customer)}
                      >
                        <Pencil />
                        {t("employer.customers.edit")}
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
