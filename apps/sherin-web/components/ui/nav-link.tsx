import Link from "next/link";
import type { VariantProps } from "class-variance-authority";

import { cn } from "@/lib/utils";

import { buttonVariants } from "./button";

type NavLinkProps = VariantProps<typeof buttonVariants> & {
  href: string;
  className?: string;
  children: React.ReactNode;
};

export function NavLink({
  href,
  variant,
  size,
  className,
  children,
}: NavLinkProps) {
  return (
    <Link
      href={href}
      className={cn(
        buttonVariants({ variant, size }),
        "inline-flex items-center gap-1.5",
        className,
      )}
    >
      {children}
    </Link>
  );
}
