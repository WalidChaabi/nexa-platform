import { expect, test } from "@playwright/test";

import { expectDashboard, loginAs } from "./helpers/auth";

test.describe("middleware auth gate", () => {
  test("redirects unauthenticated owner route to login with next param", async ({
    page,
  }) => {
    await page.goto("/owner/products");
    await expect(page).toHaveURL(/\/login\?next=%2Fowner%2Fproducts/);
    await expect(page.locator("#email")).toBeVisible();
  });

  test("redirects authenticated user away from login", async ({ page }) => {
    await loginAs(page, "owner@nexa.test");
    await expectDashboard(page, "/owner");

    await page.goto("/login");
    await expect(page).toHaveURL(/\/owner(\/|$)/);
  });

  test("redirects employer away from owner area", async ({ page }) => {
    await loginAs(page, "employer@nexa.test");
    await page.goto("/owner/products");
    await expect(page).toHaveURL(/\/employer(\/|$)/);
  });

  test("preserves next destination after login", async ({ page }) => {
    await page.goto("/owner/products");
    await expect(page).toHaveURL(/\/login\?next=/);

    await page.locator("#email").fill("owner@nexa.test");
    await page.locator("#password").fill("password");
    await page.getByRole("button", { name: /sign in/i }).click();
    await page.waitForLoadState("networkidle");

    await expect(page).toHaveURL(/\/owner\/products/);
    await expect(
      page.getByRole("heading", { name: /products/i }).first(),
    ).toBeVisible();
  });
});
