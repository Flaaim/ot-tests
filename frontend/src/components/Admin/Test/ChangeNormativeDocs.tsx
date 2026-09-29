"use client";

import { z } from "zod";
import React, { useState } from "react";
import { useRouter } from "next/navigation";
import { Controller, useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
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
import { Textarea } from "@/components/ui/textarea";
import { toast } from "sonner";
import { changeNormativeDocsAction } from "@/actions/test";

const schema = z.object({
  normativeDocs: z.string().transform((val) => {
    return val
      .split(/[;\n]+/)
      .map((doc) => doc.trim())
      .filter((doc) => doc.length > 0);
  }),
});

interface ChangeNormativeDocsProps {
  testId: string;
  normativeDocs: string[];
}

type FormInput = z.input<typeof schema>; // { normativeDocs: string }
type FormOutput = z.output<typeof schema>; // { normativeDocs: string[] }

export default function ChangeNormativeDocs({ testId, normativeDocs }: ChangeNormativeDocsProps) {
  const [open, setOpen] = useState<boolean>(false);
  const router = useRouter();

  const form = useForm<FormInput, unknown, FormOutput>({
    mode: "onSubmit",
    resolver: zodResolver(schema),
    defaultValues: {
      normativeDocs: normativeDocs.join(";\n"),
    },
  });
  async function onSubmit(values: FormOutput) {
    const result = await changeNormativeDocsAction({
      id: testId,
      normativeDocs: values.normativeDocs,
    });

    if (!result.ok) {
      form.setError("root", { type: "server", message: result.error });
      return;
    }

    toast.success("Список НПА успешно изменен.");
    form.reset({ normativeDocs: values.normativeDocs.join(";\n") });
    setOpen(false);
    router.refresh();
  }
  const submitButton = (
    <Button
      type="submit"
      form="change-normativeDocs-test-form"
      disabled={form.formState.isSubmitting}
      className="cursor-pointer py-2"
    >
      {form.formState.isSubmitting ? "Загрузка..." : "Изменить"}
    </Button>
  );

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger render={<Button />}>
        <Pencil />
      </DialogTrigger>
      <DialogContent className="sm:max-w-3xl">
        <DialogHeader>
          <DialogTitle>Изменить НПА</DialogTitle>
          <DialogDescription>Изменение нормативно-правовых документов</DialogDescription>
        </DialogHeader>
        <form
          id="change-normativeDocs-test-form"
          onSubmit={(e) => {
            void form.handleSubmit(onSubmit)(e);
          }}
          action=""
          method="PUT"
          className="grid gap-4 py-4"
        >
          <FieldGroup>
            <Controller
              name="normativeDocs"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="name">Нормативно-правовые акты</FieldLabel>
                  <Textarea
                    {...field}
                    id="normativeDocs"
                    placeholder="Введите документы через точку с запятой или с новой строки"
                    aria-invalid={fieldState.invalid}
                    className="h-[15vh] max-h-[250px]"
                    value={field.value}
                  />
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
