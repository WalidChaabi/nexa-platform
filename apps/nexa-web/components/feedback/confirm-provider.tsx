"use client";

import {
  createContext,
  useCallback,
  useContext,
  useRef,
  useState,
  type ReactNode,
} from "react";

import { useLocale } from "@/components/providers/locale-provider";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";

export type ConfirmOptions = {
  title: string;
  description?: string;
  confirmLabel?: string;
  cancelLabel?: string;
  destructive?: boolean;
  onConfirm: () => void | Promise<void>;
};

type ConfirmContextValue = {
  confirm: (options: ConfirmOptions) => void;
};

const ConfirmContext = createContext<ConfirmContextValue | null>(null);

export function ConfirmProvider({ children }: { children: ReactNode }) {
  const { t } = useLocale();
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const optionsRef = useRef<ConfirmOptions | null>(null);
  const [snapshot, setSnapshot] = useState<ConfirmOptions | null>(null);

  const confirm = useCallback((options: ConfirmOptions) => {
    optionsRef.current = options;
    setSnapshot(options);
    setOpen(true);
  }, []);

  function handleOpenChange(nextOpen: boolean) {
    if (!nextOpen && loading) {
      return;
    }

    setOpen(nextOpen);

    if (!nextOpen) {
      optionsRef.current = null;
      setSnapshot(null);
      setLoading(false);
    }
  }

  async function handleConfirm() {
    const options = optionsRef.current;
    if (!options) {
      return;
    }

    setLoading(true);

    try {
      await options.onConfirm();
      setOpen(false);
      optionsRef.current = null;
      setSnapshot(null);
    } finally {
      setLoading(false);
    }
  }

  return (
    <ConfirmContext.Provider value={{ confirm }}>
      {children}
      <AlertDialog open={open} onOpenChange={handleOpenChange}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>{snapshot?.title}</AlertDialogTitle>
            {snapshot?.description ? (
              <AlertDialogDescription>
                {snapshot.description}
              </AlertDialogDescription>
            ) : null}
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel disabled={loading}>
              {snapshot?.cancelLabel ?? t("common.cancel")}
            </AlertDialogCancel>
            <AlertDialogAction
              variant={snapshot?.destructive ? "destructive" : "default"}
              disabled={loading}
              onClick={() => void handleConfirm()}
            >
              {loading
                ? t("common.loading")
                : (snapshot?.confirmLabel ?? t("feedback.confirm.continue"))}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </ConfirmContext.Provider>
  );
}

export function useConfirm() {
  const context = useContext(ConfirmContext);

  if (!context) {
    throw new Error("useConfirm must be used within ConfirmProvider");
  }

  return context.confirm;
}
