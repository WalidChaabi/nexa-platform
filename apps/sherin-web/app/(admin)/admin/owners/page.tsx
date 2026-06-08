"use client";

import { useEffect, useState } from "react";
import { List, Pencil, Plus, UserCog, UserMinus, UserPlus } from "lucide-react";

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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
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
  activateOwner,
  createOwner,
  deactivateOwner,
  fetchOwners,
  updateOwner,
  type OwnerSummary,
} from "@/lib/api/owners";

export default function AdminOwnersPage() {
  const { t } = useLocale();
  const [owners, setOwners] = useState<OwnerSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [saving, setSaving] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [firstname, setFirstname] = useState("");
  const [lastname, setLastname] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [allowShare, setAllowShare] = useState("0");

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
  }, [reloadKey]);

  function resetForm() {
    setEditingId(null);
    setFirstname("");
    setLastname("");
    setEmail("");
    setPhone("");
    setPassword("");
    setAllowShare("0");
  }

  function startEdit(owner: OwnerSummary) {
    setEditingId(owner.id);
    setFirstname(owner.firstname);
    setLastname(owner.lastname);
    setEmail(owner.email);
    setPhone(owner.phone);
    setPassword("");
    setAllowShare(String(owner.allow_share));
  }

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!firstname.trim() || !lastname.trim() || !phone.trim()) {
      return;
    }

    if (!editingId && (!email.trim() || password.length < 8)) {
      return;
    }

    setSaving(true);
    setErrorKey(null);

    try {
      if (editingId) {
        await updateOwner(editingId, {
          firstname: firstname.trim(),
          lastname: lastname.trim(),
          phone: phone.trim(),
          allow_share: allowShare === "1",
          ...(password ? { password } : {}),
        });
      } else {
        await createOwner({
          firstname: firstname.trim(),
          lastname: lastname.trim(),
          email: email.trim(),
          phone: phone.trim(),
          password,
          allow_share: allowShare === "1",
        });
      }

      resetForm();
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey(
        editingId ? "admin.owners.updateError" : "admin.owners.createError",
      );
    } finally {
      setSaving(false);
    }
  }

  async function handleToggleActive(owner: OwnerSummary) {
    setErrorKey(null);

    try {
      if (owner.is_active === 1) {
        await deactivateOwner(owner.id);
      } else {
        await activateOwner(owner.id);
      }
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("admin.owners.statusError");
    }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={UserCog}
        title={t("admin.owners.title")}
        description={t("admin.owners.description")}
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
            {editingId
              ? t("admin.owners.editTitle")
              : t("admin.owners.createTitle")}
          </CardTitle>
          <CardDescription>
            {editingId
              ? t("admin.owners.editDescription")
              : t("admin.owners.createDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 md:grid-cols-2" onSubmit={handleSubmit}>
            <div className="space-y-2">
              <Label htmlFor="owner-firstname">
                {t("admin.owners.fields.firstname")}
              </Label>
              <Input
                id="owner-firstname"
                value={firstname}
                onChange={(event) => setFirstname(event.target.value)}
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="owner-lastname">
                {t("admin.owners.fields.lastname")}
              </Label>
              <Input
                id="owner-lastname"
                value={lastname}
                onChange={(event) => setLastname(event.target.value)}
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="owner-email">{t("admin.owners.fields.email")}</Label>
              <Input
                id="owner-email"
                type="email"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                disabled={editingId !== null}
                required={!editingId}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="owner-phone">{t("admin.owners.fields.phone")}</Label>
              <Input
                id="owner-phone"
                value={phone}
                onChange={(event) => setPhone(event.target.value)}
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="owner-password">
                {editingId
                  ? t("admin.owners.fields.passwordOptional")
                  : t("admin.owners.fields.password")}
              </Label>
              <Input
                id="owner-password"
                type="password"
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                required={!editingId}
                minLength={editingId ? undefined : 8}
              />
            </div>
            <div className="space-y-2">
              <Label>{t("admin.owners.fields.allowShare")}</Label>
              <Select value={allowShare} onValueChange={setAllowShare}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="0">{t("common.no")}</SelectItem>
                  <SelectItem value="1">{t("common.yes")}</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="flex flex-wrap gap-2 md:col-span-2">
              <Button type="submit" disabled={saving}>
                {saving
                  ? t("admin.owners.saving")
                  : editingId
                    ? t("admin.owners.save")
                    : t("admin.owners.create")}
              </Button>
              {editingId ? (
                <Button type="button" variant="outline" onClick={resetForm}>
                  {t("common.cancel")}
                </Button>
              ) : null}
            </div>
          </form>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("admin.owners.listTitle")}
          </CardTitle>
          <CardDescription>{t("admin.owners.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : owners.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("admin.owners.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.owners.columns.name")}</TableHead>
                  <TableHead>{t("admin.owners.columns.phone")}</TableHead>
                  <TableHead>{t("admin.owners.columns.email")}</TableHead>
                  <TableHead>{t("admin.owners.columns.stores")}</TableHead>
                  <TableHead>{t("admin.owners.columns.status")}</TableHead>
                  <TableHead>{t("admin.owners.columns.actions")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {owners.map((owner) => (
                  <TableRow key={owner.id}>
                    <TableCell>{owner.full_name}</TableCell>
                    <TableCell>{owner.phone}</TableCell>
                    <TableCell>{owner.email}</TableCell>
                    <TableCell>{owner.stores_count ?? 0}</TableCell>
                    <TableCell>
                      <Badge
                        variant={owner.is_active === 1 ? "default" : "secondary"}
                      >
                        {owner.is_active === 1
                          ? t("admin.owners.statusActive")
                          : t("admin.owners.statusInactive")}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <div className="flex flex-wrap gap-2">
                        <Button
                          type="button"
                          size="sm"
                          variant="outline"
                          onClick={() => startEdit(owner)}
                        >
                          <Pencil className="size-4" />
                          {t("admin.owners.edit")}
                        </Button>
                        <Button
                          type="button"
                          size="sm"
                          variant="outline"
                          onClick={() => void handleToggleActive(owner)}
                        >
                          {owner.is_active === 1 ? (
                            <>
                              <UserMinus className="size-4" />
                              {t("admin.owners.deactivate")}
                            </>
                          ) : (
                            <>
                              <UserPlus className="size-4" />
                              {t("admin.owners.activate")}
                            </>
                          )}
                        </Button>
                      </div>
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
