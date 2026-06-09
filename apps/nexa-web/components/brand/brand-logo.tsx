import { cn } from "@/lib/utils";
import { brand } from "@/lib/brand";

type BrandLogoProps = {
  className?: string;
  /** Use on dark / primary backgrounds (login panel, sidebar mark). */
  variant?: "default" | "on-primary";
};

/**
 * Geometric N: left pillar = operations, right = loyalty, bridge = growth.
 */
export function BrandLogo({
  className,
  variant = "default",
}: BrandLogoProps) {
  const pillar =
    variant === "on-primary" ? "#FFFFFF" : brand.colors.primary;
  const bridge = brand.colors.accent;

  return (
    <svg
      viewBox="0 0 32 32"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={cn("size-full", className)}
      aria-hidden
    >
      <path
        d="M8 24V8"
        stroke={pillar}
        strokeWidth="3.5"
        strokeLinecap="round"
      />
      <path
        d="M8 8L24 24"
        stroke={bridge}
        strokeWidth="3.5"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
      <path
        d="M24 24V8"
        stroke={pillar}
        strokeWidth="3.5"
        strokeLinecap="round"
      />
    </svg>
  );
}
