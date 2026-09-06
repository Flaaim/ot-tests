"use client";

import React, { useState } from "react";

import { Plus } from "lucide-react";
import { zodResolver } from "@hookform/resolvers/zod";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { useRouter } from "next/navigation";
import { Controller, useForm } from "react-hook-form";
import { z } from "zod";
import { Field, FieldError, FieldGroup, FieldLabel } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { toast } from "sonner";
import { Textarea } from "@/components/ui/textarea";
import { addCategoryAction } from "@/actions/category";

const schema = z.object({
  name: z.string().min(1).max(255),
  description: z.string().min(1),
  parentId: z.string().nullable().optional(),
});

type AddCategoryFormData = z.infer<typeof schema>;

interface AddCategoryDialogProps {
  categories: { id: string; name: string }[];
}

export default function AddCategoryDialog({ categories = [] }: AddCategoryDialogProps) {
  const [open, setOpen] = useState<boolean>(false);

  const router = useRouter();
  async function onSubmit(values: AddCategoryFormData) {
    const result = await addCategoryAction({
      name: values.name,
      description: values.description,
      parendId: values.paremdId || null,
    });

    if (!result.ok) {
      form.setError("root", { type: "server", message: result.error });
      return;
    }

    toast.success("Категория успешно добавлена!");
    form.reset();
    setOpen(false);
    router.refresh();
  }

  const form = useForm<AddCategoryFormData>({
    mode: "onSubmit",
    resolver: zodResolver(schema),
    defaultValues: {
      name: "",
      description: "",
      parentId: null,
    },
  });

  const submitButton = (
    <Button
      type="submit"
      form="add-category-form"
      disabled={form.formState.isSubmitting}
      className="w-full cursor-pointer py-2"
    >
      {form.formState.isSubmitting ? "Загрузка..." : "Добавить категорию"}
    </Button>
  );

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger render={<Button />}>
        <Plus className="mr-2 h-4 w-4" /> Добавить
      </DialogTrigger>
      <DialogContent className="sm:max-w-[800px]">
        <DialogHeader>
          <DialogTitle>Новая категория</DialogTitle>
          <DialogDescription>Добавление новой категории.</DialogDescription>
        </DialogHeader>
        <form
          id="add-category-form"
          onSubmit={(e) => {
            void form.handleSubmit(onSubmit)(e);
          }}
          method="POST"
          className="grid gap-4 py-4"
        >
          <FieldGroup>
            <FieldGroup>
              <Controller
                name="parentId"
                control={form.control}
                render={({ field, fieldState }) => (
                  <Field data-invalid={fieldState.invalid}>
                    <FieldLabel htmlFor="parentId">
                      Родительская категория (необязательно)
                    </FieldLabel>
                    <select
                      {...field}
                      id="parentId"
                      value={field.value || ""}
                      onChange={(e) => field.onChange(e.target.value || null)}
                      className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                    >
                      <option value="">-- Корневая категория --</option>
                      {categories.map((cat) => (
                        <option key={cat.id} value={cat.id}>
                          {cat.name}
                        </option>
                      ))}
                    </select>
                    {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
                  </Field>
                )}
              />
            </FieldGroup>
            <Controller
              name="name"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="host">Название</FieldLabel>
                  <Input
                    {...field}
                    id="name"
                    value={field.value}
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
                </Field>
              )}
            ></Controller>
          </FieldGroup>

          <FieldGroup>
            <Controller
              name="description"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid} className="w-full">
                  <Textarea
                    {...field}
                    id="description"
                    placeholder="Описание"
                    aria-invalid={fieldState.invalid}
                    className="overflow-y-auto font-mono text-xs leading-relaxed resize-y p-3 w-full"
                  ></Textarea>
                  {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
                </Field>
              )}
            ></Controller>
          </FieldGroup>
        </form>
        {form.formState.errors.root && (
          <div className="rounded-md bg-destructive/10 p-2 text-center text-sm font-medium text-destructive">
            {form.formState.errors.root.message}
          </div>
        )}
        <DialogFooter>{submitButton}</DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
