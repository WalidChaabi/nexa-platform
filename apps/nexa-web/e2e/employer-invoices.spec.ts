import { expect, test } from "@playwright/test";

import { loginAs } from "./helpers/auth";

test.describe("employer invoices", () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, "employer@nexa.test");
  });

  test("lists invoices for assigned store", async ({ page }) => {
    await page.goto("/employer/invoices");
    await expect(
      page.getByRole("heading", { name: /invoices/i }).first(),
    ).toBeVisible();
    await expect(page.getByRole("link", { name: /new invoice/i })).toBeVisible();
  });

  test("opens invoice create page with catalog", async ({ page }) => {
    await page.goto("/employer/invoices/create");
    await expect(
      page.getByRole("heading", { name: /new invoice/i }).first(),
    ).toBeVisible();
  });
});
