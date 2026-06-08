"use client";

import { Badge } from "@/components/ui/badge";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { LogoutButton } from "@/components/auth/logout-button";
import { useRequireAuth } from "@/hooks/use-require-auth";

export default function AdminDashboardPage() {
  const { user, loading } = useRequireAuth("admin");

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        Loading dashboard...
      </div>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <div className="mx-auto flex min-h-screen max-w-3xl flex-col gap-6 px-6 py-12">
      <div className="flex items-center justify-between">
        <div>
          <Badge>Admin</Badge>
          <h1 className="mt-2 text-3xl font-semibold">Admin dashboard</h1>
          <p className="text-muted-foreground">Signed in as {user.full_name}</p>
        </div>
        <LogoutButton />
      </div>
      <Card>
        <CardHeader>
          <CardTitle>Phase 1 foundation</CardTitle>
        </CardHeader>
        <CardContent className="text-sm text-muted-foreground">
          Auth and role routing are live. Business modules land in Phase 2.
        </CardContent>
      </Card>
    </div>
  );
}
