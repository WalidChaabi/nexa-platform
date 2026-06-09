/**
 * Nexa brand tokens — single source of truth for product identity.
 * CSS mirrors these values in app/globals.css.
 */
export const brand = {
  name: "Nexa",
  tagline: "Sell. Retain. Grow.",
  headline: "The operating system for modern retail",
  description:
    "POS, CRM, campaigns, and payments — unified for retailers who want to sell smarter and grow faster.",
  domain: "nexa.app",
  colors: {
    primary: "#0F172A",
    accent: "#00C389",
    surface: "#FFFFFF",
    background: "#F8FAFC",
    success: "#00C389",
    warning: "#F59E0B",
    danger: "#EF4444",
  },
} as const;

export type BrandColor = keyof typeof brand.colors;
