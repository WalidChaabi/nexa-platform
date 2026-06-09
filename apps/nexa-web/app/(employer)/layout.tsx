import { RoleDashboardLayout } from "@/components/layout/role-dashboard-layout";
import { getServerUser } from "@/lib/auth/get-server-user";

export default async function EmployerLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const initialUser = await getServerUser();

  return (
    <RoleDashboardLayout role="employer" initialUser={initialUser}>
      {children}
    </RoleDashboardLayout>
  );
}
