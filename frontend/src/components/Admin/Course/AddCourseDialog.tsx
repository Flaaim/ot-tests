"use client";


import React, {useState} from "react";
import {useRouter} from "next/navigation";
import {Button} from "@/components/ui/button";
import {Controller, useForm} from "react-hook-form";
import {zodResolver} from "@hookform/resolvers/zod";
import {z} from "zod";
import {addCourseAction} from "@/actions/course";
import {toast} from "sonner";
import {
  Dialog,
  DialogContent,
  DialogDescription, DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger
} from "@/components/ui/dialog";
import {Upload} from "lucide-react";
import {Field, FieldError, FieldGroup, FieldLabel} from "@/components/ui/field";
import {Textarea} from "@/components/ui/textarea";
import {Input} from "@/components/ui/input";


const AnswerSchema = z.object({
  id: z.string().uuid().or(z.string()),
  text: z.string(),
  isCorrect: z.boolean(),
  answerImg: z.string().or(z.literal("")),
});

const QuestionSchema = z.object({
  id: z.string().uuid().or(z.string()),
  number: z.number().int().positive(), // целое положительное число
  text: z.string(),
  questionImg: z.string().url().or(z.literal("")), // валидный URL или пустая строка
  answers: z.array(AnswerSchema), // массив ответов
  form: z.enum(["single_choice", "multiple_choice", "sequence", "matching"]),
});

export const QuestionsArraySchema = z.array(QuestionSchema);

export type Question = z.infer<typeof QuestionSchema>;
export type Answer = z.infer<typeof AnswerSchema>;

const schema = z.object({
  name: z.string(),
  cipher: z.string(),
  rawJson: z
    .string()
    .min(1, "Поле не может быть пустым")
    .transform((str, ctx) => {
      try {
        return JSON.parse(str);
      } catch {
        ctx.addIssue({
          code: "custom",
          message: "Некорректный формат JSON (синтаксическая ошибка)",
        });
        return z.NEVER;
      }
    })
    .pipe(QuestionsArraySchema),
});

type AddCourseFormData = z.infer<typeof schema>;

export default function AddCourseDialog() {
  const [open, setOpen] = useState<boolean>(false);

  const router = useRouter();

  const form = useForm<AddCourseFormData>({
    mode: "onSubmit",
    resolver: zodResolver(schema),
    defaultValues: {
      name: "",
      cipher: "",
      rawJson: ""
    },
  });

  async function onSubmit(values: AddCourseFormData) {
    const jsonString = JSON.stringify(values.rawJson);

    const result = await addCourseAction({
      name: values.name,
      cipher: values.cipher,
      draft: jsonString,
    });

    if (!result.ok) {
      form.setError("root", { type: "server", message: result.error });
      return;
    }

    toast.success("Курс успешно добавлен!");
    form.reset();
    setOpen(false);
    router.refresh();
  }
  const handleFormatJson = () => {
    const currentVal = form.getValues("rawJson");
    if (!currentVal) return;

    try {
      const parsed = JSON.parse(currentVal);
      const formatted = JSON.stringify(parsed, null, 2);
      form.setValue("rawJson", formatted, { shouldValidate: true });
    } catch {
      form.trigger("rawJson");
    }
  };
  const submitButton = (
    <Button
      type="submit"
      form="add-course-form"
      disabled={form.formState.isSubmitting}
      className="w-full cursor-pointer py-2"
    >
      {form.formState.isSubmitting ? "Загрузка..." : "Добавить курс"}
    </Button>
  );

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger render={<Button />}>
        <Upload className="mr-2 h-4 w-4" /> Новый курс
      </DialogTrigger>
      <DialogContent className="sm:max-w-3xl">
        <DialogHeader>
          <DialogTitle>Добавить новый курс</DialogTitle>
          <DialogDescription>Добавление нового курса.</DialogDescription>
        </DialogHeader>
        <form
          id="add-course-form"
          onSubmit={(e) => {
            void form.handleSubmit(onSubmit)(e);
          }}
          action=""
          method="POST"
          className="grid gap-4 py-4"
        >
          <FieldGroup>
            <Controller
              name="name"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="name">Название курса</FieldLabel>
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
            ></Controller>
          </FieldGroup>
          <FieldGroup>
            <Controller
              name="cipher"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid}>
                  <FieldLabel htmlFor="cipher">Шифр курса</FieldLabel>
                  <Input
                    {...field}
                    id="cipher"
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
              name="rawJson"
              control={form.control}
              render={({ field, fieldState }) => (
                <Field data-invalid={fieldState.invalid} className="w-full">
                  <div className="flex items-center justify-between mb-1.5">
                    <FieldLabel htmlFor="rawJson" className="font-medium text-sm block">
                      Json строка с вопросами курса.
                    </FieldLabel>
                    <button
                      type="button"
                      onClick={handleFormatJson}
                      className="text-xs text-muted-foreground hover:text-foreground transition-colors underline underline-offset-2"
                    >
                      Форматировать JSON
                    </button>
                  </div>

                  <Textarea
                    {...field}
                    id="rawJson"
                    placeholder='[{"id": "...", "number": 1, "text": "..."}]'
                    aria-invalid={fieldState.invalid}
                    onPaste={() => {
                      setTimeout(() => {
                        handleFormatJson();
                      }, 50);
                    }}
                    className="h-[60vh] max-h-[600px] overflow-y-auto font-mono text-xs leading-relaxed resize-y p-3 w-full"
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
