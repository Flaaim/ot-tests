"use client";

import React, { useState } from "react";
import { toast } from "sonner";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog";
import { Button } from "@/components/ui/button";
import { Loader2, Trash } from "lucide-react";
import { useRouter } from "next/navigation";
import { removeCategoryAction } from "@/actions/category";

interface RemoveCategoryDialogProps {
  id: string;
}

export default function RemoveCategoryDialog({ id }: RemoveCategoryDialogProps) {
  const [loading, setLoading] = useState<boolean>(false);
  const [open, setOpen] = useState<boolean>(false);

  const router = useRouter();

  const onSubmit = async (e: React.MouseEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      const result = await removeCategoryAction(id);
      if (!result.ok) {
        toast.error(result.error ?? "Не удалось удалить данные.");
        setOpen(false);
        return;
      }
      toast.success("Категория успешно удалена!");
      router.refresh();
    } catch (error) {
      console.error("Error:", error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <AlertDialog open={open} onOpenChange={setOpen}>
      <AlertDialogTrigger render={<Button />}>
        <Trash className="h-4 w-4" />
      </AlertDialogTrigger>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>Удалить?</AlertDialogTitle>
          <AlertDialogDescription>
            Данное действие полностью удалит категорию. Это действие нельзя отменить.
          </AlertDialogDescription>
        </AlertDialogHeader>
        {loading ? (
          <div>Загрузка...</div>
        ) : (
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={onSubmit}
              disabled={loading}
              className="bg-red-600 hover:bg-red-700 text-white"
            >
              {loading && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
              Удалить
            </AlertDialogAction>
          </AlertDialogFooter>
        )}
      </AlertDialogContent>
    </AlertDialog>
  );
}
