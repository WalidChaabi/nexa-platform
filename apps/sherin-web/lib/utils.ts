import { clsx, type ClassValue } from "clsx"
import { twMerge } from "tailwind-merge"

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs))
}

export function onSelectValue(
  setter: (value: string) => void,
  fallback = "",
): (value: string | null) => void {
  return (value) => setter(value ?? fallback);
}
