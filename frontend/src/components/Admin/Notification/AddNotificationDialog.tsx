"use client";

import { z } from "zod";
import React, { useState } from "react";
import { useRouter } from "next/navigation";
import { Button } from "@/components/ui/button";
import { Controller, useForm } from "react-hook-form";
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
import { Upload } from "lucide-react";
import { toast } from "sonner";
import { Field, FieldError, FieldGroup, FieldLabel } from "@/components/ui/field";
import { Input } from "@/components/ui/input";
import { addNotificationAction } from "@/actions/notification";
import MDEditor from "@uiw/react-md-editor";

const schema = z.object({
  subject: z.string().min(3),
  message: z.string().min(3),
});

type AddNotificationFormData = z.infer<typeof schema>;

export default function AddNotificationDialog() {
  const [open, setOpen] = useState<boolean>(false);

  const router = useRouter();

  const form = useForm<AddNotificationFormData>({
    mode: "onSubmit",
    resolver: zodResolver(schema),
    defaultValues: {
      subject: "",
      message: "",
    },
  });
  async function onSubmit(values: AddNotificationFormData) {
    const result = await addNotificationAction({
      subject: values.subject,
      message: values.message,
    });

    if (!result.ok) {
      form.setError("root", { type: "server", message: result.error });
      return;
    }

    toast.success("Уведомление создано!");
    form.reset();
    setOpen(false);
    router.refresh();
  }
  const submitButton = (
    <Button
      type="submit"
      form="add-notification-form"
      disabled={form.formState.isSubmitting}
      className="cursor-pointer py-2"
    >
      {form.formState.isSubmitting ? "Загрузка..." : "Создать"}
    </Button>
  );

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger render={<Button />}>
        <Upload className="mr-2 h-4 w-4" /> Новое уведомление
      </DialogTrigger>
      <DialogContent className="sm:max-w-3xl">
        <DialogHeader>
          <DialogTitle>Создать уведомление</DialogTitle>
          <DialogDescription>Создание нового уведомления для пользователей сайта</DialogDescription>
        </DialogHeader>
        <form
          id="add-notification-form"
          onSubmit={(e) => {
            void form.handleSubmit(onSubmit)(e);
          }}
          action=""
          method="POST"
          className="grid gap-4 py-4"
        >
          <FieldGroup>
            <Controller
              name="subject"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="name">Тема уведомления</FieldLabel>
                  <Input
                    {...field}
                    id="subject"
                    value={field.value}
                    placeholder=""
                    aria-invalid={fieldState.invalid}
                  />
                  {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
                </Field>
              )}
            ></Controller>
          </FieldGroup>
          <FieldGroup>
            <Controller
              name="message"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="message">Сообщение</FieldLabel>
                  <div
                    data-color-mode="light"
                    className="w-full mt-1 border rounded-md overflow-hidden"
                  >
                    <MDEditor
                      value={field.value}
                      onChange={field.onChange}
                      height={350}
                      preview="edit"
                      textareaProps={{
                        placeholder: "Введите текст уведомления. Поддерживается Markdown...",
                      }}
                    />
                  </div>
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
