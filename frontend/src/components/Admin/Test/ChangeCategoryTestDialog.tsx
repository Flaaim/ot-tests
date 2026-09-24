"use client";

import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { z } from "zod";
import { CategoryDTO } from "@/interfaces/category.interface";
import { fetchCoursesToSelectAction } from "@/actions/course";
import { toast } from "sonner";
import { fetchCategoryTreeAction } from "@/actions/category";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Pencil } from "lucide-react";

const schema = z.object({
  categoryId: z.string().uuid("Некорректный формат UUID"),
});

interface ChangeCategoryTestDialogProps {
  testId: string;
  currentCategoryId: string;
}

function flattenCategories(
  categories: CategoryDTO[],
  level = 0
): { id: string; name: string; isLeaf: boolean }[] {
  let result: { id: string; name: string; isLeaf: boolean }[] = [];

  for (const cat of categories) {
    const hasChildren = cat.children && cat.children.length > 0;
    const prefix = level > 0 ? "— ".repeat(level) : "";

    result.push({
      id: cat.id,
      name: `${prefix}${cat.name}`,
      isLeaf: !hasChildren, // Если нет детей, значит это конечная категория (лист)
    });

    if (hasChildren) {
      result = result.concat(flattenCategories(cat.children || [], level + 1));
    }
  }

  return result;
}

export default function ChangeCategoryTestDialog({
  testId,
  currentCategoryId,
}: ChangeCategoryTestDialogProps) {
  const [open, setOpen] = useState<boolean>(false);
  const [loading, setLoading] = useState<boolean>(false);
  const [categories, setCategories] = useState<CategoryDTO[]>([]);
  const router = useRouter();

  useEffect(() => {
    if (open) {
      const initData = async () => {
        setLoading(true);
        try {
          const response = await fetchCategoryTreeAction();
          if (response.ok && response.data) {
            setCategories(response.data);
          }
        } catch (error) {
          const err = error instanceof Error ? error : new Error("Ошибка при получении данных");
          toast.error(err.message);
        } finally {
          setLoading(false);
        }
      };
      void initData();
    }
  }, [open]);

  const flatCategories = flattenCategories(categories);
  console.log(flatCategories);

  return (
    <Dialog>
      <DialogTrigger>
        <Pencil size={12} className="mr-1 " />
        Изменить
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Изменить категорию</DialogTitle>
          <DialogDescription>Изменение категории теста</DialogDescription>
        </DialogHeader>
      </DialogContent>
    </Dialog>
  );
}
