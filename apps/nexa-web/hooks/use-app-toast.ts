"use client";

import { toast } from "sonner";

import { useLocale } from "@/components/providers/locale-provider";

type ToastParams = Record<string, string | number>;

export function useAppToast() {
  const { t } = useLocale();

  return {
    success(messageKey: string, params?: ToastParams) {
      toast.success(t(messageKey, params));
    },
    error(messageKey: string, params?: ToastParams) {
      toast.error(t(messageKey, params));
    },
    info(messageKey: string, params?: ToastParams) {
      toast.info(t(messageKey, params));
    },
  };
}
