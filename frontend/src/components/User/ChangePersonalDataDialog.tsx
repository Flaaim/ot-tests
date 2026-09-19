"use client";

import { z } from "zod";
import React, { useState } from "react";
import { useRouter } from "next/navigation";
import { Controller, useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { toast } from "sonner";
import { changePersonalDataAction } from "@/actions/profile";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Pencil } from "lucide-react";
import { Field, FieldError, FieldGroup, FieldLabel } from "@/components/ui/field";
import { Input } from "@/components/ui/input";

const schema = z.object({
  name: z.string().trim().min(1, "Имя обязательно для заполнения").max(60, "Имя слишком длинное!"),
  surname: z
    .string()
    .trim()
    .min(1, "Фамилия обязательно для заполнения")
    .max(80, "Фамилия слишком длинная!"),
});

interface ChangePersonalDataDialogProps {
  name: string;
  surname: string;
}
type ChangePersonalFormData = z.infer<typeof schema>;
export default function ChangePersonalDataDialog({ name, surname }: ChangePersonalDataDialogProps) {
  const [open, setOpen] = useState<boolean>(false);

  const router = useRouter();

  async function onSubmit(values: ChangePersonalFormData) {
    const result = await changePersonalDataAction({
      name: values.name,
      surname: values.surname,
    });

    if (!result.ok) {
      form.setError("root", { type: "server", message: result.error });
      return;
    }

    toast.success("Персональные данные успешно изменены");
    form.reset(values);
    setOpen(false);
    router.refresh();
  }

  const form = useForm({
    mode: "onSubmit",
    resolver: zodResolver(schema),
    defaultValues: {
      name: name,
      surname: surname,
    },
  });

  const submitButton = (
    <Button
      type="submit"
      form="change-personal-data-form"
      disabled={form.formState.isSubmitting}
      className="cursor-pointer py-2"
    >
      {form.formState.isSubmitting ? "Загрузка..." : "Сохранить"}
    </Button>
  );

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger render={<Button variant="secondary" size="sm" />}>
        <Pencil className=" h-4 w-4" /> Изменить
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Изменить персональные данные</DialogTitle>
          <DialogDescription>Изменение своих персональных данных</DialogDescription>
        </DialogHeader>
        <form
          id="change-personal-data-form"
          onSubmit={(e) => {
            void form.handleSubmit(onSubmit)(e);
          }}
          action=""
          method="PUT"
          className="grid gap-4 py-4"
        >
          <FieldGroup>
            <Controller
              name="name"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid} className="w-full">
                  <FieldLabel htmlFor="name">Имя</FieldLabel>
                  <Input
                    {...field}
                    id="name"
                    value={field.value}
                    placeholder=""
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
                </Field>
              )}
            />
            <Controller
              name="surname"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid} className="w-full">
                  <FieldLabel htmlFor="name">Фамилия</FieldLabel>
                  <Input
                    {...field}
                    id="surname"
                    value={field.value}
                    placeholder=""
                    aria-invalid={fieldState.invalid}
                  />
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
        <DialogFooter>{submitButton}</DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
