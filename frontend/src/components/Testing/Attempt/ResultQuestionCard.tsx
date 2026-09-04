"use client";

import {
  AnswerItemDTO,
  MatchingAnswersDTO,
  ResultQuestionDTO,
} from "@/interfaces/attempt.interface";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { PUBLIC_ASSETS_URL } from "@/app/api";
import { CheckCircle2, XCircle, ArrowRight } from "lucide-react";

interface ResultQuestionCardProps {
  question: ResultQuestionDTO;
  index: number;
}

export function ResultQuestionCard({ question, index }: ResultQuestionCardProps) {
  const isCorrect = question.userResult?.isCorrect;
  const isSkipped = !question.userResult;

  const renderChoices = () => {
    const options = question.answers as AnswerItemDTO[];
    return (
      <div className="space-y-3">
        {options.map((option) => {
          const isSelected = question.userResult?.selectedIds.includes(option.id);
          const isActuallyCorrect = option.isCorrect;

          let style = "border-border opacity-50";
          if (isActuallyCorrect && isSelected)
            style = "border-green-500 bg-green-500/10 text-green-700 dark:text-green-400";
          if (isActuallyCorrect && !isSelected)
            style = "border-green-500/50 border-dashed text-muted-foreground";
          if (!isActuallyCorrect && isSelected)
            style = "border-red-500 bg-red-500/10 text-red-700 dark:text-red-400";

          return (
            <div
              key={option.id}
              className={`flex items-center space-x-3 rounded-lg border p-3 transition-colors ${style}`}
            >
              {isActuallyCorrect && isSelected && (
                <CheckCircle2 className="w-5 h-5 shrink-0 text-green-600" />
              )}
              {!isActuallyCorrect && isSelected && (
                <XCircle className="w-5 h-5 shrink-0 text-red-600" />
              )}
              {!isSelected && <div className="w-5 h-5 shrink-0" />}
              <span className="text-sm font-medium leading-normal">{option.text}</span>
            </div>
          );
        })}
      </div>
    );
  };

  const renderSequence = () => {
    const correctOrder = question.answers as AnswerItemDTO[];
    const userOrderIds = question.userResult?.selectedIds || [];

    return (
      <div className="space-y-6">
        {!isCorrect && !isSkipped && (
          <div>
            <span className="text-sm font-semibold text-red-600 mb-2 block">Ваш ответ:</span>
            <div className="space-y-2 opacity-70">
              {userOrderIds.map((id, i) => {
                const answer = correctOrder.find((a) => a.id === id);
                return (
                  <div
                    key={id}
                    className="flex gap-3 p-3 rounded-md bg-red-50 text-red-900 border border-red-200 text-sm"
                  >
                    <span className="font-bold">{i + 1}.</span> {answer?.text}
                  </div>
                );
              })}
            </div>
          </div>
        )}

        <div>
          <span className="text-sm font-semibold text-green-600 mb-2 block">
            Правильная последовательность:
          </span>
          <div className="space-y-2">
            {correctOrder.map((answer, i) => (
              <div
                key={answer.id}
                className="flex gap-3 p-3 rounded-md bg-green-50 text-green-900 border border-green-200 text-sm font-medium"
              >
                <span className="font-bold">{i + 1}.</span> {answer.text}
              </div>
            ))}
          </div>
        </div>
      </div>
    );
  };

  const renderMatching = () => {
    const matchingAnswers = question.answers as MatchingAnswersDTO;
    const leftItems = matchingAnswers.left || [];
    const correctRightItems = matchingAnswers.right || [];
    const userRightIds = question.userResult?.selectedIds || [];

    return (
      <div className="space-y-3">
        {leftItems.map((leftItem, i) => {
          const correctRight = correctRightItems[i];
          const userRightId = userRightIds[i];
          const userRight = correctRightItems.find((r) => r.id === userRightId);

          const isRowCorrect = correctRight.id === userRightId;

          return (
            <div
              key={leftItem.id}
              className={`flex flex-col md:flex-row gap-4 p-4 rounded-lg border ${!isSkipped && !isRowCorrect ? "bg-red-50/50 border-red-200" : "bg-muted/30"}`}
            >
              <div className="flex-1 space-y-2">
                <span className="text-sm text-muted-foreground block">Понятие:</span>
                <span className="text-sm font-medium">{leftItem.text}</span>
                {leftItem.answerImg && (
                  // eslint-disable-next-line @next/next/no-img-element
                  <img
                    src={`${PUBLIC_ASSETS_URL}${process.env.NEXT_PUBLIC_QUESTION_IMAGES}${leftItem.answerImg}`}
                    alt=""
                    className="max-h-24 rounded border bg-white"
                  />
                )}
              </div>

              <div className="flex items-center justify-center shrink-0">
                <ArrowRight className="text-muted-foreground hidden md:block" />
              </div>

              <div className="flex-1 space-y-4">
                {/* То, что ответил пользователь (если неправильно) */}
                {!isSkipped && !isRowCorrect && userRight && (
                  <div>
                    <span className="text-xs font-semibold text-red-600 block mb-1">
                      Ваш ответ (ошибка):
                    </span>
                    <div className="p-3 bg-red-100 border border-red-300 rounded text-sm text-red-900">
                      {userRight.text}
                    </div>
                  </div>
                )}

                {/* Правильный ответ */}
                <div>
                  <span
                    className={`text-xs font-semibold block mb-1 ${isRowCorrect ? "text-green-600" : "text-green-700 opacity-80"}`}
                  >
                    {isRowCorrect ? "Ваш ответ (Верно):" : "Правильный ответ:"}
                  </span>
                  <div
                    className={`p-3 border rounded text-sm font-medium ${isRowCorrect ? "bg-green-100 border-green-300 text-green-900" : "bg-white border-green-500/50 border-dashed text-foreground"}`}
                  >
                    {correctRight.text}
                  </div>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    );
  };

  return (
    <Card
      className={`border-l-4 ${isSkipped ? "border-l-muted" : isCorrect ? "border-l-green-500" : "border-l-red-500"}`}
    >
      <CardHeader className="pb-3">
        <div className="flex items-center justify-between mb-2">
          <Badge variant={isSkipped ? "secondary" : isCorrect ? "default" : "destructive"}>
            Вопрос {index + 1}
          </Badge>
          {isCorrect && (
            <span className="text-green-600 text-sm font-medium flex items-center gap-1">
              <CheckCircle2 className="w-4 h-4" /> Верно
            </span>
          )}
          {!isCorrect && !isSkipped && (
            <span className="text-red-600 text-sm font-medium flex items-center gap-1">
              <XCircle className="w-4 h-4" /> Ошибка
            </span>
          )}
          {isSkipped && <span className="text-muted-foreground text-sm font-medium">Пропущен</span>}
        </div>
        <CardTitle className="text-lg leading-relaxed">{question.text}</CardTitle>
        {question.questionImg && (
          <div className="mt-4 border rounded-md overflow-hidden relative inline-block bg-white p-2">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={`${PUBLIC_ASSETS_URL}${process.env.NEXT_PUBLIC_QUESTION_IMAGES}${question.questionImg}`}
              alt="Иллюстрация к вопросу"
              className="max-h-64 object-contain"
            />
          </div>
        )}
      </CardHeader>

      <CardContent>
        {question.form === "single_choice" || question.form === "multiple_choice" ? (
          renderChoices()
        ) : question.form === "sequence" ? (
          renderSequence()
        ) : question.form === "matching" ? (
          renderMatching()
        ) : (
          <div className="text-destructive">Неизвестный тип вопроса</div>
        )}
      </CardContent>
    </Card>
  );
}
