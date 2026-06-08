"use client";

import { useEffect, useRef, useState } from "react";
import { useRouter } from "next/navigation";
import { ChevronDown, LogOut, User } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { logout, type AuthUser } from "@/lib/api/auth";
import { cn } from "@/lib/utils";

function initials(user: AuthUser): string {
  const parts = [user.firstname, user.lastname]
    .filter(Boolean)
    .map((part) => part.charAt(0).toUpperCase());

  if (parts.length > 0) {
    return parts.join("").slice(0, 2);
  }

  return user.email.charAt(0).toUpperCase();
}

export function UserMenu({ user }: { user: AuthUser }) {
  const router = useRouter();
  const { t } = useLocale();
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const rootRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    function handlePointerDown(event: MouseEvent) {
      if (!rootRef.current?.contains(event.target as Node)) {
        setOpen(false);
      }
    }

    function handleEscape(event: KeyboardEvent) {
      if (event.key === "Escape") {
        setOpen(false);
      }
    }

    document.addEventListener("mousedown", handlePointerDown);
    document.addEventListener("keydown", handleEscape);

    return () => {
      document.removeEventListener("mousedown", handlePointerDown);
      document.removeEventListener("keydown", handleEscape);
    };
  }, [open]);

  async function handleLogout() {
    setLoading(true);
    try {
      await logout();
      router.replace("/login");
      router.refresh();
    } finally {
      setLoading(false);
      setOpen(false);
    }
  }

  return (
    <div ref={rootRef} className="relative">
      <Button
        type="button"
        variant="ghost"
        className="h-9 gap-2 px-2"
        aria-label={t("common.openUserMenu")}
        aria-expanded={open}
        aria-haspopup="menu"
        onClick={() => setOpen((value) => !value)}
      >
        <Avatar size="sm">
          <AvatarFallback className="bg-primary/10 text-primary">
            {initials(user)}
          </AvatarFallback>
        </Avatar>
        <span className="hidden max-w-32 truncate text-sm font-medium sm:inline">
          {user.full_name}
        </span>
        <ChevronDown
          className={cn(
            "hidden size-4 text-muted-foreground transition-transform sm:block",
            open && "rotate-180",
          )}
        />
      </Button>

      {open ? (
        <div
          role="menu"
          className="absolute top-full end-0 z-50 mt-2 w-56 rounded-lg border bg-popover p-1 text-popover-foreground shadow-md ring-1 ring-foreground/10"
        >
          <div className="px-2 py-2">
            <p className="text-sm font-medium">{user.full_name}</p>
            <p className="text-xs text-muted-foreground">{user.email}</p>
          </div>
          <Separator />
          <div className="flex items-center gap-2 px-2 py-2 text-sm text-muted-foreground">
            <User className="size-4" />
            {t("common.account", { role: t(`roles.${user.role}`) })}
          </div>
          <Separator />
          <Button
            type="button"
            variant="ghost"
            role="menuitem"
            className="h-8 w-full justify-start gap-2 px-2 text-destructive hover:bg-destructive/10 hover:text-destructive"
            disabled={loading}
            onClick={() => void handleLogout()}
          >
            <LogOut className="size-4" />
            {loading ? t("common.signingOut") : t("common.signOut")}
          </Button>
        </div>
      ) : null}
    </div>
  );
}
