import { useEffect } from "react";
import { Question } from "@/interfaces/attempt.interface";
import { GripVertical } from "lucide-react";
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
} from "@dnd-kit/core";
import {
  arrayMove,
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
  useSortable,
} from "@dnd-kit/sortable";
import { CSS } from "@dnd-kit/utilities";

interface SequenceQuestionProps {
  question: Question;
  selectedAnswersIds: string[];
  onAnswerChange: (answersIds: string[]) => void;
}

// Компонент одного перетаскиваемого элемента
function SortableItem({ id, text }: { id: string; text: string }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    // Немного приподнимаем элемент визуально, когда он "взят"
    zIndex: isDragging ? 10 : 1,
  };

  return (
    <div
      ref={setNodeRef}
      style={style}
      className={`flex items-center space-x-3 rounded-lg border p-4 bg-card transition-colors ${
        isDragging ? "shadow-md border-primary opacity-90" : "hover:border-primary/50"
      }`}
    >
      {/* Область захвата (ручка) */}
      <div
        {...attributes}
        {...listeners}
        className="cursor-grab active:cursor-grabbing text-muted-foreground hover:text-foreground"
      >
        <GripVertical className="h-5 w-5" />
      </div>
      <span className="text-sm font-medium leading-normal select-none">
        {text}
      </span>
    </div>
  );
}

export function SequenceQuestion({
                                   question,
                                   selectedAnswersIds,
                                   onAnswerChange,
                                 }: SequenceQuestionProps) {
  // Инициализируем порядок ответов изначальным массивом с бэкенда,
  // если пользователь еще ничего не двигал (массив пуст)
  useEffect(() => {
    if (selectedAnswersIds.length === 0) {
      onAnswerChange(question.answers.map((a) => a.id));
    }
  }, [question.answers, selectedAnswersIds.length, onAnswerChange]);

  // Настраиваем сенсоры для мыши/тача и клавиатуры
  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  );

  // Текущий порядок элементов для отображения
  const currentOrderIds =
    selectedAnswersIds.length > 0
      ? selectedAnswersIds
      : question.answers.map((a) => a.id);

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;

    if (over && active.id !== over.id) {
      const oldIndex = currentOrderIds.indexOf(active.id as string);
      const newIndex = currentOrderIds.indexOf(over.id as string);

      // arrayMove - утилита dnd-kit, которая меняет элементы местами
      const newOrder = arrayMove(currentOrderIds, oldIndex, newIndex);
      onAnswerChange(newOrder);
    }
  };

  return (
    <DndContext
      sensors={sensors}
      collisionDetection={closestCenter}
      onDragEnd={handleDragEnd}
    >
      <SortableContext
        items={currentOrderIds}
        strategy={verticalListSortingStrategy}
      >
        <div className="space-y-3">
          {currentOrderIds.map((id) => {
            // Находим текст ответа по его ID
            const answer = question.answers.find((a) => a.id === id);
            if (!answer) return null;

            return <SortableItem key={id} id={id} text={answer.text} />;
          })}
        </div>
      </SortableContext>
    </DndContext>
  );
}
