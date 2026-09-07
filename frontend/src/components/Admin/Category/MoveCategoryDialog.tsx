"use client";

import { CategoryDTO } from "@/interfaces/category.interface";
import { moveCategoryAction } from "@/actions/category";
import { toast } from "sonner";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { Controller, useForm } from "react-hook-form";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { FolderInput } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Field, FieldError, FieldGroup, FieldLabel } from "@/components/ui/field";

interface MoveCategoryDialogProps {
  categories: CategoryDTO[];
  category: CategoryDTO;
}

const schema = z.object({
  parentId: z.string().nullable().optional(),
});

type MoveCategoryFormData = z.infer<typeof schema>;
function getValidParents(
  categories: CategoryDTO[],
  currentCategoryId: string,
  level = 0
): { id: string; name: string }[] {
  let result: { id: string; name: string }[] = [];

  for (const cat of categories) {
    // ❗️ ГЛАВНАЯ ЗАЩИТА: Если это та самая категория, которую мы двигаем,
    // мы пропускаем её И ВСЕХ ЕЁ ДЕТЕЙ (не вызываем рекурсию для них).
    if (cat.id === currentCategoryId) {
      continue;
    }

    const prefix = level > 0 ? "— ".repeat(level) : "";

    result.push({
      id: cat.id,
      name: `${prefix}${cat.name}`,
    });

    if (cat.children && cat.children.length > 0) {
      // Рекурсия сама пройдет на любой уровень глубины (3, 5, 10...)
      result = result.concat(getValidParents(cat.children, currentCategoryId, level + 1));
    }
  }

  return result;
}
export default function MoveCategoryDialog({ categories, category }: MoveCategoryDialogProps) {
  const [open, setOpen] = useState<boolean>(false);
  const router = useRouter();
  const validParents = getValidParents(categories, category.id);

  async function onSubmit(values: MoveCategoryFormData) {
    // Вызов серверного экшена (передаем ID категории и новый parentId)
    const result = await moveCategoryAction({
      id: category.id,
      parentId: values.parentId || null,
    });

    if (!result.ok) {
      form.setError("root", { type: "server", message: result.error });
      return;
    }

    toast.success("Категория успешно перемещена!");
    form.reset();
    setOpen(false);
    router.refresh();
  }

  const form = useForm<MoveCategoryFormData>({
    mode: "onSubmit",
    resolver: zodResolver(schema),
    defaultValues: {
      parentId: category.parentId, // Устанавливаем текущего родителя по умолчанию
    },
  });

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger render={<Button variant="outline" size="sm" className="mt-4" />}>
        <FolderInput className="mr-2 h-4 w-4" /> Переместить
      </DialogTrigger>
      <DialogContent className="sm:max-w-[500px]">
        <DialogHeader>
          <DialogTitle>Перемещение категории</DialogTitle>
          <DialogDescription>
            Выберите новую родительскую категорию для «{category.name}».
          </DialogDescription>
        </DialogHeader>
        <form
          id="move-category-form"
          onSubmit={(e) => {
            void form.handleSubmit(onSubmit)(e);
          }}
          className="grid gap-4 py-4"
        >
          <FieldGroup>
            <Controller
              name="parentId"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="parentId">Новое расположение</FieldLabel>
                  <select
                    {...field}
                    id="parentId"
                    value={field.value || ""}
                    onChange={(e) => field.onChange(e.target.value || null)}
                    className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                  >
                    <option value="" className="font-semibold">
                      [ Корневая категория ]
                    </option>
                    {validParents.map((parent) => (
                      <option key={parent.id} value={parent.id}>
                        {parent.name}
                      </option>
                    ))}
                  </select>
                  {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
                </Field>
              )}
            />
          </FieldGroup>
        </form>

        {form.formState.errors.root && (
          <div className="rounded-md bg-destructive/10 p-2 text-center text-sm font-medium text-destructive">
            {form.formState.errors.root.message}
          </div>
        )}

        <DialogFooter>
          <Button
            type="submit"
            form="move-category-form"
            disabled={form.formState.isSubmitting}
            className="w-full"
          >
            {form.formState.isSubmitting ? "Сохранение..." : "Сохранить"}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
