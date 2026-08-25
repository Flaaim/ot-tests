import { Question } from "@/interfaces/attempt.interface";
import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";

interface MultipleChoiceQuestionProps {
  question: Question;
  selectedAnswersIds: string[];
  onAnswerChange: (answerId: string[]) => void;
}

export function MultipleChoiceQuestion({
  question,
  selectedAnswersIds,
  onAnswerChange,
}: MultipleChoiceQuestionProps) {
  const handleToggle = (answerId: string) => {
    if (selectedAnswersIds.includes(answerId)) {
      // Если уже есть в массиве — удаляем
      onAnswerChange(selectedAnswersIds.filter((id) => id !== answerId));
    } else {
      // Если нет — добавляем
      onAnswerChange([...selectedAnswersIds, answerId]);
    }
  };

  return (
    <div className="space-y-3">
      {question.answers.map((answer) => {
        const isSelected = selectedAnswersIds.includes(answer.id);

        return (
          <div
            key={answer.id}
            onClick={() => handleToggle(answer.id)}
            className={`flex items-center space-x-3 rounded-lg border p-4 transition-colors cursor-pointer ${
              isSelected ? "bg-primary/5 border-primary" : "hover:bg-muted/50 border-input"
            }`}
          >
            <Checkbox
              id={`answer-${answer.id}`}
              checked={isSelected}
              // Отключаем собственные события чекбокса, чтобы клик ловил только родительский div
              className="pointer-events-none"
            />
            <Label
              htmlFor={`answer-${answer.id}`}
              className="flex-1 cursor-pointer text-sm font-medium leading-normal pointer-events-none"
            >
              {answer.text}
            </Label>
          </div>
        );
      })}
    </div>
  );
}
