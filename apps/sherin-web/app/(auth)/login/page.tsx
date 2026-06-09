"use client";

import { useState } from "react";
import { AlertCircle } from "lucide-react";

import { BrandLogo } from "@/components/brand/brand-logo";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { login } from "@/lib/api/auth";

export default function LoginPage() {
  const { t } = useLocale();
  const [email, setEmail] = useState("employer@nexa.test");
  const [password, setPassword] = useState("password");
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError(null);
    setLoading(true);

    try {
      const user = await login(email, password);
      window.location.assign(user.dashboard_path);
    } catch (err) {
      setError(
        err instanceof Error ? err.message : t("auth.loginFailed"),
      );
    } finally {
      setLoading(false);
    }
  }

  return (
    <Card className="w-full max-w-md border-border/60 shadow-lg">
      <CardHeader className="space-y-4">
        <div className="flex items-center gap-3 lg:hidden">
          <div className="flex size-9 items-center justify-center rounded-lg bg-primary p-1.5 text-primary-foreground">
            <BrandLogo variant="on-primary" />
          </div>
          <div>
            <p className="font-semibold">{t("common.appName")}</p>
            <p className="text-xs text-muted-foreground">
              {t("auth.signInToContinue")}
            </p>
          </div>
        </div>
        <div className="space-y-1">
          <CardTitle className="text-2xl">{t("auth.welcomeBack")}</CardTitle>
          <CardDescription>{t("auth.signInDescription")}</CardDescription>
        </div>
      </CardHeader>
      <form onSubmit={handleSubmit}>
        <CardContent className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="email">{t("auth.email")}</Label>
            <Input
              id="email"
              type="email"
              autoComplete="email"
              placeholder="you@nexa.test"
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              required
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="password">{t("auth.password")}</Label>
            <Input
              id="password"
              type="password"
              autoComplete="current-password"
              value={password}
              onChange={(event) => setPassword(event.target.value)}
              required
            />
          </div>
          {error ? (
            <Alert variant="destructive">
              <AlertCircle />
              <AlertTitle>{t("auth.signInFailed")}</AlertTitle>
              <AlertDescription>{error}</AlertDescription>
            </Alert>
          ) : null}
          <p className="rounded-lg border bg-muted/50 px-3 py-2 text-xs text-muted-foreground">
            {t("auth.devSeed")}
          </p>
        </CardContent>
        <CardFooter>
          <Button className="w-full" type="submit" disabled={loading}>
            {loading ? t("auth.signingIn") : t("auth.signIn")}
          </Button>
        </CardFooter>
      </form>
    </Card>
  );
}
