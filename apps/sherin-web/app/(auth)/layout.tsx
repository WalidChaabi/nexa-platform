import { AuthBranding } from "@/components/auth/auth-branding";
import { PreferencesToolbar } from "@/components/layout/preferences-toolbar";

export default function AuthLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <div className="relative grid min-h-svh lg:grid-cols-2">
      <div className="absolute top-4 end-4 z-10 lg:end-6">
        <PreferencesToolbar />
      </div>
      <AuthBranding />
      <div className="flex items-center justify-center bg-muted/30 p-6 sm:p-10">
        {children}
      </div>
    </div>
  );
}
