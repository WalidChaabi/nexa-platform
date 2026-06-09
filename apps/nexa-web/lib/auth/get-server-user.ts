import { cookies } from "next/headers";

import { fetchCurrentUserOnServer } from "@/lib/auth/server";

export async function getServerUser() {
  const cookieStore = await cookies();
  return fetchCurrentUserOnServer(cookieStore.toString());
}
