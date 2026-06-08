"use client";

import { useEffect, useState } from "react";
import { Briefcase, List, Pencil, Plus, Trash2 } from "lucide-react";

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
  createActivity,
  deleteActivity,
  fetchActivities,
  updateActivity,
  type ActivitySummary,
} from "@/lib/api/activities";

export default function AdminActivitiesPage() {
  const { t } = useLocale();
  const [activities, setActivities] = useState<ActivitySummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [saving, setSaving] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");

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
  }, [reloadKey]);

  function resetForm() {
    setEditingId(null);
    setName("");
    setDescription("");
  }

  function startEdit(activity: ActivitySummary) {
    setEditingId(activity.id);
    setName(activity.name);
    setDescription(activity.description ?? "");
  }

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!name.trim()) {
      return;
    }

    setSaving(true);
    setErrorKey(null);

    try {
      const payload = {
        name: name.trim(),
        description: description.trim() || undefined,
      };

      if (editingId) {
        await updateActivity(editingId, payload);
      } else {
        await createActivity(payload);
      }

      resetForm();
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey(
        editingId
          ? "admin.activities.updateError"
          : "admin.activities.createError",
      );
    } finally {
      setSaving(false);
    }
  }

  async function handleDelete(activity: ActivitySummary) {
    setErrorKey(null);

    try {
      await deleteActivity(activity.id);
      if (editingId === activity.id) {
        resetForm();
      }
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("admin.activities.deleteError");
    }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Briefcase}
        title={t("admin.activities.title")}
        description={t("admin.activities.description")}
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
              ? t("admin.activities.editTitle")
              : t("admin.activities.createTitle")}
          </CardTitle>
          <CardDescription>
            {editingId
              ? t("admin.activities.editDescription")
              : t("admin.activities.createDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4" onSubmit={handleSubmit}>
            <div className="space-y-2">
              <Label htmlFor="activity-name">
                {t("admin.activities.fields.name")}
              </Label>
              <Input
                id="activity-name"
                value={name}
                onChange={(event) => setName(event.target.value)}
                required
                maxLength={50}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="activity-description">
                {t("admin.activities.fields.description")}
              </Label>
              <Input
                id="activity-description"
                value={description}
                onChange={(event) => setDescription(event.target.value)}
              />
            </div>
            <div className="flex flex-wrap gap-2">
              <Button type="submit" disabled={saving}>
                {saving
                  ? t("admin.activities.saving")
                  : editingId
                    ? t("admin.activities.save")
                    : t("admin.activities.create")}
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
            {t("admin.activities.listTitle")}
          </CardTitle>
          <CardDescription>
            {t("admin.activities.listDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : activities.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("admin.activities.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.activities.columns.name")}</TableHead>
                  <TableHead>
                    {t("admin.activities.columns.description")}
                  </TableHead>
                  <TableHead>{t("admin.activities.columns.stores")}</TableHead>
                  <TableHead>{t("admin.activities.columns.actions")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {activities.map((activity) => (
                  <TableRow key={activity.id}>
                    <TableCell>{activity.name}</TableCell>
                    <TableCell>{activity.description ?? "—"}</TableCell>
                    <TableCell>{activity.stores_count ?? 0}</TableCell>
                    <TableCell>
                      <div className="flex flex-wrap gap-2">
                        <Button
                          type="button"
                          size="sm"
                          variant="outline"
                          onClick={() => startEdit(activity)}
                        >
                          <Pencil className="size-4" />
                          {t("admin.activities.edit")}
                        </Button>
                        <Button
                          type="button"
                          size="sm"
                          variant="outline"
                          disabled={(activity.stores_count ?? 0) > 0}
                          onClick={() => void handleDelete(activity)}
                        >
                          <Trash2 className="size-4" />
                          {t("admin.activities.delete")}
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
