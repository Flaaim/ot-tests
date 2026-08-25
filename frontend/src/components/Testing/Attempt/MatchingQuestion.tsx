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

interface MatchingQuestionProps {
  question: Question;
  selectedAnswersIds: string[];
  onAnswerChange: (answersIds: string[]) => void;
}

// Компонент перетаскиваемого элемента для правой колонки
function SortableRightItem({ id, text }: { id: string; text: string }) {
  const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({
    id,
  });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    zIndex: isDragging ? 10 : 1,
  };

  return (
    <div
      ref={setNodeRef}
      style={style}
      className={`flex items-center space-x-3 rounded-lg border p-4 bg-card min-h-[70px] transition-colors ${
        isDragging ? "shadow-md border-primary opacity-90" : "hover:border-primary/50"
      }`}
    >
      <div
        {...attributes}
        {...listeners}
        className="cursor-grab active:cursor-grabbing text-muted-foreground hover:text-foreground shrink-0"
      >
        <GripVertical className="h-5 w-5" />
      </div>
      <span className="text-sm font-medium leading-normal">{text}</span>
    </div>
  );
}

export function MatchingQuestion({
  question,
  selectedAnswersIds,
  onAnswerChange,
}: MatchingQuestionProps) {
  // Поскольку для matching бэкенд возвращает объект { left: [], right: [] }
  // мы приводим тип answers к нужному формату
  const leftItems = (question.answers as any).left || [];
  const rightItems = (question.answers as any).right || [];

  // Инициализируем стейт перемешанным порядком из правой колонки
  useEffect(() => {
    if (selectedAnswersIds.length === 0 && rightItems.length > 0) {
      onAnswerChange(rightItems.map((item: any) => item.id));
    }
  }, [rightItems, selectedAnswersIds.length, onAnswerChange]);

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

  const currentOrderIds =
    selectedAnswersIds.length > 0 ? selectedAnswersIds : rightItems.map((item: any) => item.id);

  const handleDragEnd = (event: DragEndEvent) => {
    const { active, over } = event;

    if (over && active.id !== over.id) {
      const oldIndex = currentOrderIds.indexOf(active.id as string);
      const newIndex = currentOrderIds.indexOf(over.id as string);

      const newOrder = arrayMove(currentOrderIds, oldIndex, newIndex);
      onAnswerChange(newOrder);
    }
  };

  return (
    <div className="flex gap-4 w-full">
      {/* Левая колонка (статичная) */}
      <div className="flex flex-col gap-3 w-1/2">
        {leftItems.map((item: any) => (
          <div
            key={item.id}
            className="flex items-center p-4 rounded-lg border border-dashed bg-muted/40 min-h-[70px] text-sm font-medium leading-normal"
          >
            {item.text}
          </div>
        ))}
      </div>

      {/* Правая колонка (сортируемая) */}
      <div className="flex flex-col gap-3 w-1/2">
        <DndContext sensors={sensors} collisionDetection={closestCenter} onDragEnd={handleDragEnd}>
          <SortableContext items={currentOrderIds} strategy={verticalListSortingStrategy}>
            {currentOrderIds.map((id) => {
              const answer = rightItems.find((a: any) => a.id === id);
              if (!answer) return null;

              return <SortableRightItem key={id} id={id} text={answer.text} />;
            })}
          </SortableContext>
        </DndContext>
      </div>
    </div>
  );
}
