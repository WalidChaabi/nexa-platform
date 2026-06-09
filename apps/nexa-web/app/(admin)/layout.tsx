import { RoleDashboardLayout } from "@/components/layout/role-dashboard-layout";
import { getServerUser } from "@/lib/auth/get-server-user";

export default async function AdminLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const initialUser = await getServerUser();

  return (
    <RoleDashboardLayout role="admin" initialUser={initialUser}>
      {children}
    </RoleDashboardLayout>
  );
}
