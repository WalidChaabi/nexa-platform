"use client";

import { useState } from "react";
import { AlertCircle } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { login } from "@/lib/api/auth";
import { cn } from "@/lib/utils";

const isDev = process.env.NODE_ENV === "development";

export function LoginForm({
  className,
  ...props
}: React.ComponentProps<"form">) {
  const { t } = useLocale();
  const [email, setEmail] = useState(isDev ? "employer@nexa.test" : "");
  const [password, setPassword] = useState(isDev ? "password" : "");
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
    <form
      className={cn("flex flex-col gap-6", className)}
      onSubmit={handleSubmit}
      {...props}
    >
      <div className="flex flex-col items-center gap-1 text-center">
        <h1 className="text-2xl font-bold">{t("auth.welcomeBack")}</h1>
        <p className="text-sm text-balance text-muted-foreground">
          {t("auth.signInDescription")}
        </p>
      </div>

      <div className="flex flex-col gap-4">
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

        <Button className="w-full" type="submit" disabled={loading}>
          {loading ? t("auth.signingIn") : t("auth.signIn")}
        </Button>

        {isDev ? (
          <p className="text-center text-xs text-balance text-muted-foreground">
            {t("auth.devSeed")}
          </p>
        ) : null}
      </div>
    </form>
  );
}
