"use client";

import { Badge } from "@/components/ui/badge";
import { NavLink } from "@/components/ui/nav-link";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { LogoutButton } from "@/components/auth/logout-button";
import { useRequireAuth } from "@/hooks/use-require-auth";

export default function EmployerDashboardPage() {
  const { user, loading } = useRequireAuth("employer");

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
          <Badge variant="outline">Employer</Badge>
          <h1 className="mt-2 text-3xl font-semibold">Employer dashboard</h1>
          <p className="text-muted-foreground">Signed in as {user.full_name}</p>
        </div>
        <LogoutButton />
      </div>
      <Card>
        <CardHeader>
          <CardTitle>POS workspace</CardTitle>
        </CardHeader>
        <CardContent className="flex flex-wrap gap-3 text-sm text-muted-foreground">
          <NavLink href="/employer/invoices/create">New invoice</NavLink>
          <NavLink href="/employer/invoices" variant="outline">
            Invoice history
          </NavLink>
        </CardContent>
      </Card>
    </div>
  );
}
