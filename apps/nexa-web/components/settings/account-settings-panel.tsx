"use client";

import { useState } from "react";

import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { LocaleToggle } from "@/components/layout/locale-toggle";
import { ThemeToggle } from "@/components/layout/theme-toggle";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { useAppToast } from "@/hooks/use-app-toast";
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
  updatePassword,
  updateProfile,
} from "@/lib/api/auth";

export function AccountSettingsPanel() {
  const { user, setUser } = useDashboardAuth();
  const { t } = useLocale();
  const toast = useAppToast();

  const [firstname, setFirstname] = useState(user.firstname);
  const [lastname, setLastname] = useState(user.lastname);
  const [phone, setPhone] = useState(user.phone ?? "");
  const [profileSubmitting, setProfileSubmitting] = useState(false);
  const [profileMessage, setProfileMessage] = useState<"success" | "error" | null>(
    null,
  );

  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [passwordSubmitting, setPasswordSubmitting] = useState(false);
  const [passwordMessage, setPasswordMessage] = useState<"success" | "error" | null>(
    null,
  );

  async function handleProfileSubmit(event: React.FormEvent) {
    event.preventDefault();
    setProfileSubmitting(true);
    setProfileMessage(null);

    try {
      const updated = await updateProfile({
        firstname: firstname.trim(),
        lastname: lastname.trim(),
        phone: phone.trim(),
      });
      setUser(updated);
      setProfileMessage("success");
      toast.success("feedback.toast.profileSaved");
    } catch {
      setProfileMessage("error");
    } finally {
      setProfileSubmitting(false);
    }
  }

  async function handlePasswordSubmit(event: React.FormEvent) {
    event.preventDefault();
    setPasswordSubmitting(true);
    setPasswordMessage(null);

    try {
      await updatePassword({
        current_password: currentPassword,
        password: newPassword,
        password_confirmation: confirmPassword,
      });
      setCurrentPassword("");
      setNewPassword("");
      setConfirmPassword("");
      setPasswordMessage("success");
      toast.success("feedback.toast.passwordUpdated");
    } catch {
      setPasswordMessage("error");
    } finally {
      setPasswordSubmitting(false);
    }
  }

  return (
    <div className="grid gap-4 lg:grid-cols-2">
      <Card>
        <CardHeader>
          <CardTitle>{t("settings.profileTitle")}</CardTitle>
          <CardDescription>{t("settings.profileDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="space-y-4" onSubmit={handleProfileSubmit}>
            {profileMessage === "success" ? (
              <Alert>
                <AlertDescription>{t("settings.profileSaved")}</AlertDescription>
              </Alert>
            ) : null}
            {profileMessage === "error" ? (
              <Alert variant="destructive">
                <AlertDescription>{t("settings.profileError")}</AlertDescription>
              </Alert>
            ) : null}

            <div className="space-y-2">
              <Label htmlFor="settings-email">{t("settings.fields.email")}</Label>
              <Input id="settings-email" value={user.email} disabled />
            </div>

            <div className="grid gap-4 sm:grid-cols-2">
              <div className="space-y-2">
                <Label htmlFor="settings-firstname">
                  {t("settings.fields.firstname")}
                </Label>
                <Input
                  id="settings-firstname"
                  value={firstname}
                  onChange={(event) => setFirstname(event.target.value)}
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="settings-lastname">
                  {t("settings.fields.lastname")}
                </Label>
                <Input
                  id="settings-lastname"
                  value={lastname}
                  onChange={(event) => setLastname(event.target.value)}
                  required
                />
              </div>
            </div>

            <div className="space-y-2">
              <Label htmlFor="settings-phone">{t("settings.fields.phone")}</Label>
              <Input
                id="settings-phone"
                value={phone}
                onChange={(event) => setPhone(event.target.value)}
                required
              />
            </div>

            <Button type="submit" disabled={profileSubmitting}>
              {profileSubmitting
                ? t("settings.savingProfile")
                : t("settings.saveProfile")}
            </Button>
          </form>
        </CardContent>
      </Card>

      <div className="space-y-4">
        <Card>
          <CardHeader>
            <CardTitle>{t("settings.passwordTitle")}</CardTitle>
            <CardDescription>{t("settings.passwordDescription")}</CardDescription>
          </CardHeader>
          <CardContent>
            <form className="space-y-4" onSubmit={handlePasswordSubmit}>
              {passwordMessage === "success" ? (
                <Alert>
                  <AlertDescription>{t("settings.passwordSaved")}</AlertDescription>
                </Alert>
              ) : null}
              {passwordMessage === "error" ? (
                <Alert variant="destructive">
                  <AlertDescription>{t("settings.passwordError")}</AlertDescription>
                </Alert>
              ) : null}

              <div className="space-y-2">
                <Label htmlFor="settings-current-password">
                  {t("settings.fields.currentPassword")}
                </Label>
                <Input
                  id="settings-current-password"
                  type="password"
                  value={currentPassword}
                  onChange={(event) => setCurrentPassword(event.target.value)}
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="settings-new-password">
                  {t("settings.fields.newPassword")}
                </Label>
                <Input
                  id="settings-new-password"
                  type="password"
                  value={newPassword}
                  onChange={(event) => setNewPassword(event.target.value)}
                  required
                  minLength={8}
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="settings-confirm-password">
                  {t("settings.fields.confirmPassword")}
                </Label>
                <Input
                  id="settings-confirm-password"
                  type="password"
                  value={confirmPassword}
                  onChange={(event) => setConfirmPassword(event.target.value)}
                  required
                  minLength={8}
                />
              </div>

              <Button type="submit" disabled={passwordSubmitting}>
                {passwordSubmitting
                  ? t("settings.savingPassword")
                  : t("settings.savePassword")}
              </Button>
            </form>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>{t("settings.preferencesTitle")}</CardTitle>
            <CardDescription>
              {t("settings.preferencesDescription")}
            </CardDescription>
          </CardHeader>
          <CardContent className="flex flex-wrap items-center gap-3">
            <LocaleToggle />
            <ThemeToggle />
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
