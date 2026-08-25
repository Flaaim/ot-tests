"use client";

import { AttemptInterface, SubmitAnswerPayload } from "@/interfaces/attempt.interface";
import { useState } from "react";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { PUBLIC_ASSETS_URL } from "@/app/api";
import { Button } from "@/components/ui/button";
import { submitAnswerAction } from "@/actions/attempt";

interface TestRunnerClientProps {
  attempt: AttemptInterface;
}

export default function TestRunnerClient({ attempt }: TestRunnerClientProps) {
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const questions = attempt.questions || [];
  const currentQuestion = questions[currentQuestionIndex];

  if (!currentQuestion) {
    return <div className="p-8 text-center text-muted-foreground">Вопросы не найдены.</div>;
  }

  const isLastQuestion = currentQuestionIndex === questions.length - 1;
  const handleNext = async () => {
    setIsSubmitting(true);

    const payload: SubmitAnswerPayload = {
      id: attempt.id,
      questionId: currentQuestion.id,
      selectedAnswersIds: [],
    };
    await submitAnswerAction(payload);

    setIsSubmitting(false);

    if (!isLastQuestion) {
      setCurrentQuestionIndex((prev) => prev + 1);
    } else {
      // Здесь будет логика завершения теста
      alert("Тест завершен!");
    }
  };

  return (
    <div className="max-w-3xl mx-auto space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold">Билет № {attempt.ticketNumber}</h1>
        Вопрос {currentQuestionIndex + 1} из {questions.length}
      </div>
      <Card>
        <CardHeader>
          <CardTitle className="leading-relaxed">{currentQuestion.text}</CardTitle>
          {currentQuestion.questionImg && (
            <div className="mt-4 border rounded-md overflow-hidden relative inline-block">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={`${PUBLIC_ASSETS_URL}${process.env.NEXT_PUBLIC_QUESTION_IMAGES}${currentQuestion.questionImg}`}
                alt="Изображение к вопросу"
                className="max-h-64 object-contain"
              />
            </div>
          )}
        </CardHeader>
        <CardContent>
          {/*
            Здесь мы будем рендерить разные компоненты в зависимости от currentQuestion.form
            Например: SingleChoice, MultipleChoice, Sequence, Matching
          */}
          <div className="p-4 bg-muted/50 rounded-lg text-sm text-muted-foreground border border-dashed">
            Здесь будут варианты ответов для формы: <strong>{currentQuestion.form}</strong>
          </div>
        </CardContent>

        <CardFooter className="flex justify-end pt-6 border-t">
          <Button onClick={handleNext} disabled={isSubmitting}>
            {isLastQuestion ? "Завершить тест" : "Ответить"}
          </Button>
        </CardFooter>
      </Card>
    </div>
  );
}
