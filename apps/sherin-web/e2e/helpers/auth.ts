import { expect, type Page } from "@playwright/test";

export async function loginAs(
  page: Page,
  email: string,
  password = "password",
): Promise<void> {
  await page.goto("/login");
  await page.locator("#email").fill(email);
  await page.locator("#password").fill(password);
  await page.getByRole("button", { name: /sign in/i }).click();
  await page.waitForLoadState("networkidle");
}

export async function expectDashboard(
  page: Page,
  pathPrefix: "/admin" | "/owner" | "/employer",
): Promise<void> {
  await expect(page).toHaveURL(new RegExp(`${pathPrefix}(/|$)`));
}
