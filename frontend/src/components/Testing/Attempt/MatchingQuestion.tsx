import { useEffect, useState, useMemo } from "react";
import { MatchingAnswersDTO } from "@/interfaces/attempt.interface";
import { GripVertical } from "lucide-react";
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
  DragEndEvent,
  DragStartEvent,
  DragOverlay,
  defaultDropAnimationSideEffects,
} from "@dnd-kit/core";
import {
  arrayMove,
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
  useSortable,
} from "@dnd-kit/sortable";
import { CSS } from "@dnd-kit/utilities";
import { PUBLIC_ASSETS_URL } from "@/app/api";
import { Question } from "@/interfaces/attempt.interface";

interface MatchingQuestionProps {
  question: Question;
  selectedAnswersIds: string[];
  onAnswerChange: (answersIds: string[]) => void;
}

// 1. Чисто визуальный компонент карточки (без логики перетаскивания)
function RightItemCard({
  text,
  dragHandleProps = {},
  isOverlay = false,
}: {
  text: string;
  dragHandleProps?: Record<string, unknown>;
  isOverlay?: boolean;
}) {
  return (
    <div
      className={`flex items-center space-x-3 rounded-lg border p-4 bg-card w-full h-full transition-colors ${
        isOverlay ? "shadow-2xl border-primary opacity-100 scale-105" : "hover:border-primary/50"
      }`}
    >
      <div
        {...dragHandleProps}
        className={`text-muted-foreground shrink-0 h-full flex items-center ${
          isOverlay ? "cursor-grabbing" : "cursor-grab active:cursor-grabbing hover:text-foreground"
        }`}
      >
        <GripVertical className="h-5 w-5" />
      </div>
      {text && <span className="text-sm font-medium leading-normal">{text}</span>}
    </div>
  );
}

// 2. Логическая обертка для элементов в списке
function SortableRightItem({ id, text }: { id: string; text: string }) {
  const { attributes, listeners, setNodeRef, transform, transition, isDragging } = useSortable({
    id,
  });

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    // Когда элемент тянут, на его родном месте оставляем полупрозрачный след
    opacity: isDragging ? 0.3 : 1,
  };

  return (
    <div ref={setNodeRef} style={style} className="w-full h-full">
      <RightItemCard text={text} dragHandleProps={{ ...attributes, ...listeners }} />
    </div>
  );
}

export function MatchingQuestion({
  question,
  selectedAnswersIds,
  onAnswerChange,
}: MatchingQuestionProps) {
  const matchingAnswers = question.answers as unknown as MatchingAnswersDTO;
  const leftItems = useMemo(() => matchingAnswers.left || [], [matchingAnswers.left]);
  const rightItems = useMemo(() => matchingAnswers.right || [], [matchingAnswers.right]);

  // 3. Стейт для отслеживания того, какой элемент сейчас в воздухе
  const [activeId, setActiveId] = useState<string | null>(null);

  useEffect(() => {
    if (selectedAnswersIds.length === 0 && rightItems.length > 0) {
      onAnswerChange(rightItems.map((item) => item.id));
    }
  }, [rightItems, selectedAnswersIds.length, onAnswerChange]);

  const sensors = useSensors(
    useSensor(PointerSensor),
    useSensor(KeyboardSensor, { coordinateGetter: sortableKeyboardCoordinates })
  );

  const currentOrderIds =
    selectedAnswersIds.length > 0 ? selectedAnswersIds : rightItems.map((item) => item.id);

  // --- Обработчики событий Drag-and-Drop ---
  const handleDragStart = (event: DragStartEvent) => {
    setActiveId(event.active.id as string);
  };

  const handleDragEnd = (event: DragEndEvent) => {
    setActiveId(null);
    const { active, over } = event;

    if (over && active.id !== over.id) {
      const oldIndex = currentOrderIds.indexOf(active.id as string);
      const newIndex = currentOrderIds.indexOf(over.id as string);

      const newOrder = arrayMove(currentOrderIds, oldIndex, newIndex) as string[];
      onAnswerChange(newOrder);
    }
  };

  const handleDragCancel = () => {
    setActiveId(null);
  };

  // Ищем текст активного элемента, чтобы показать его в Overlay
  const activeItem = activeId ? rightItems.find((a) => a.id === activeId) : null;
  // Настраиваем красивую анимацию "примагничивания" элемента на место
  const dropAnimation = {
    sideEffects: defaultDropAnimationSideEffects({ styles: { active: { opacity: "0.4" } } }),
  };

  return (
    <div className="flex flex-col gap-3 w-full">
      <DndContext
        sensors={sensors}
        collisionDetection={closestCenter}
        onDragStart={handleDragStart}
        onDragEnd={handleDragEnd}
        onDragCancel={handleDragCancel}
      >
        <SortableContext items={currentOrderIds} strategy={verticalListSortingStrategy}>
          {leftItems.map((leftItem, index) => {
            const rightId = currentOrderIds[index];
            const rightItem = rightItems.find((a) => a.id === rightId);

            if (!rightItem) return null;

            return (
              <div key={leftItem.id} className="flex gap-4 w-full items-stretch">
                {/* Левая часть (Статичная) */}
                <div className="flex items-center p-4 rounded-lg border border-dashed bg-muted/40 w-1/2">
                  <div className="flex flex-col gap-3 w-full">
                    {leftItem.text && (
                      <span className="text-sm font-medium leading-normal">{leftItem.text}</span>
                    )}
                    {leftItem.answerImg && (
                      // eslint-disable-next-line @next/next/no-img-element
                      <img
                        src={`${PUBLIC_ASSETS_URL}${process.env.NEXT_PUBLIC_QUESTION_IMAGES}${leftItem.answerImg}`}
                        alt="Иллюстрация"
                        className="max-h-32 object-contain rounded-md border bg-white"
                      />
                    )}
                  </div>
                </div>

                {/* Правая часть (Сортируемая) */}
                <div className="w-1/2 flex">
                  <SortableRightItem id={rightId} text={rightItem.text} />
                </div>
              </div>
            );
          })}
        </SortableContext>

        {/* 4. Магия DragOverlay: рендерится поверх всего интерфейса */}
        <DragOverlay dropAnimation={dropAnimation}>
          {activeItem ? (
            <div className="h-full w-full">
              <RightItemCard text={activeItem.text} isOverlay={true} />
            </div>
          ) : null}
        </DragOverlay>
      </DndContext>
    </div>
  );
}
