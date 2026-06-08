"use client";

import { createContext, useContext } from "react";

import type { AuthUser } from "@/lib/api/auth";

type DashboardAuthContextValue = {
  user: AuthUser;
};

const DashboardAuthContext = createContext<DashboardAuthContextValue | null>(
  null,
);

export function DashboardAuthProvider({
  user,
  children,
}: {
  user: AuthUser;
  children: React.ReactNode;
}) {
  return (
    <DashboardAuthContext.Provider value={{ user }}>
      {children}
    </DashboardAuthContext.Provider>
  );
}

export function useDashboardAuth(): DashboardAuthContextValue {
  const context = useContext(DashboardAuthContext);

  if (!context) {
    throw new Error("useDashboardAuth must be used within DashboardAuthProvider.");
  }

  return context;
}
