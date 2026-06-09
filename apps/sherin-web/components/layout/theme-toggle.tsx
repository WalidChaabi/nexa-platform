"use client";

import { useEffect, useState } from "react";
import { useTheme } from "next-themes";
import { Monitor, Moon, Sun } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

type ThemeChoice = "light" | "dark" | "system";

const choices: ThemeChoice[] = ["light", "dark", "system"];

export function ThemeToggle() {
  const { theme, setTheme } = useTheme();
  const { t } = useLocale();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    queueMicrotask(() => setMounted(true));
  }, []);

  const active = mounted ? (theme ?? "system") : "system";

  return (
    <div
      className="inline-flex items-center rounded-lg border p-0.5"
      role="group"
      aria-label={t("common.theme")}
    >
      {choices.map((choice) => {
        const Icon =
          choice === "light" ? Sun : choice === "dark" ? Moon : Monitor;
        const label =
          choice === "light"
            ? t("common.light")
            : choice === "dark"
              ? t("common.dark")
              : t("common.system");

        return (
          <Button
            key={choice}
            type="button"
            variant={active === choice ? "secondary" : "ghost"}
            size="icon-sm"
            className={cn("size-7", active === choice && "shadow-sm")}
            aria-label={label}
            aria-pressed={active === choice}
            onClick={() => setTheme(choice)}
          >
            <Icon />
          </Button>
        );
      })}
    </div>
  );
}
