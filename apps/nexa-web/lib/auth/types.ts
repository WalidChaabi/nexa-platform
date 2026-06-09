export type AuthRole = "admin" | "owner" | "employer";

export type AuthUser = {
  id: number;
  email: string;
  firstname: string;
  lastname: string;
  full_name: string;
  phone: string | null;
  store_id: number | null;
  role: AuthRole;
  is_active: boolean;
  dashboard_path: string;
};
