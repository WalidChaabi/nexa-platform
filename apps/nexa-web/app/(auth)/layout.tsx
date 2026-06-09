import { PreferencesToolbar } from "@/components/layout/preferences-toolbar";

export default function AuthLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <>
      <div className="absolute top-4 end-4 z-10">
        <PreferencesToolbar />
      </div>
      {children}
    </>
  );
}
