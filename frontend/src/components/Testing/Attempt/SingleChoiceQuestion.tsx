import { Question } from "@/interfaces/attempt.interface";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Label } from "@/components/ui/label";

interface SingleChoiceQuestionProps {
  question: Question;
  selectedAnswerId?: string;
  onAnswerChange: (answerId: string) => void;
}

export function SingleChoiceQuestion({
  question,
  selectedAnswerId,
  onAnswerChange,
}: SingleChoiceQuestionProps) {
  return (
    <RadioGroup value={selectedAnswerId} onValueChange={onAnswerChange} className="space-y-3">
      {question.answers.map((answer) => (
        // Делаем всю карточку кликабельной
        <div
          key={answer.id}
          className={`flex items-center space-x-3 rounded-lg border p-4 transition-colors cursor-pointer ${
            selectedAnswerId === answer.id
              ? "bg-primary/5 border-primary"
              : "hover:bg-muted/50 border-input"
          }`}
          onClick={() => onAnswerChange(answer.id)}
        >
          <RadioGroupItem value={answer.id} id={`answer-${answer.id}`} />
          <Label
            htmlFor={`answer-${answer.id}`}
            className="flex-1 cursor-pointer text-sm font-medium leading-normal"
          >
            {answer.text}
          </Label>
        </div>
      ))}
    </RadioGroup>
  );
}
