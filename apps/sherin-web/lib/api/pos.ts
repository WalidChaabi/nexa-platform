import { apiGet } from "@/lib/api/client";

export type PosCustomer = {
  id: number;
  store_id: number;
  firstname: string | null;
  lastname: string | null;
  phone: string;
  email: string | null;
  points: string | null;
  display_name: string;
};

export type PosProduct = {
  id: number;
  store_id: number;
  label: string;
  price: number;
  promotion_price: number;
  quantite: number;
};

export type PosCatalog = {
  store_id: number;
  customers: PosCustomer[];
  products: PosProduct[];
};

export async function fetchPosCatalog(storeId: number): Promise<PosCatalog> {
  return apiGet<PosCatalog>(`/api/v1/pos/catalog?store_id=${storeId}`);
}
