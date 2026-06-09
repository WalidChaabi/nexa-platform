"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";

import { type AuthUser, fetchCurrentUser } from "@/lib/api/auth";

type Role = AuthUser["role"];

export function useRequireAuth(requiredRole?: Role) {
  const router = useRouter();
  const [user, setUser] = useState<AuthUser | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let active = true;

    async function verify() {
      try {
        const currentUser = await fetchCurrentUser();

        if (!active) {
          return;
        }

        if (!currentUser) {
          router.replace("/login");
          return;
        }

        if (requiredRole && currentUser.role !== requiredRole) {
          router.replace(currentUser.dashboard_path);
          return;
        }

        setUser(currentUser);
      } finally {
        if (active) {
          setLoading(false);
        }
      }
    }

    verify();

    return () => {
      active = false;
    };
  }, [requiredRole, router]);

  return { user, loading };
}
