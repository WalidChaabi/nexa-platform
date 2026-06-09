import { expect, test } from "@playwright/test";

import { loginAs } from "./helpers/auth";

test.describe("admin catalog lists", () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, "admin@nexa.test");
  });

  test("products page loads with DataTable search", async ({ page }) => {
    await page.goto("/admin/products");
    await expect(
      page.getByRole("heading", { name: /products/i }).first(),
    ).toBeVisible();
    await expect(page.getByPlaceholder(/search products/i)).toBeVisible();
  });

  test("customers page loads with DataTable search", async ({ page }) => {
    await page.goto("/admin/customers");
    await expect(
      page.getByRole("heading", { name: /customers/i }).first(),
    ).toBeVisible();
    await expect(page.getByPlaceholder(/search customers/i)).toBeVisible();
  });
});
