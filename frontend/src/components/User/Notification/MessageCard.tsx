"use client";

import React, { useState, useEffect, useRef } from "react";
import { MessageItem } from "@/interfaces/notification.interface";
import { markAsReadMessageAction } from "@/actions/notification"; // Убедитесь, что импорт правильный
import { useRouter } from "next/navigation";
import { cn } from "@/lib/utils";
import { ChevronDown, ChevronUp } from "lucide-react";
import MDEditor from "@uiw/react-md-editor";

export default function MessageCard({ message }: { message: MessageItem }) {
  const router = useRouter();
  const [isExpanded, setIsExpanded] = useState(false);
  const [isRead, setIsRead] = useState(message.status === "read");

  // Реф для скролла к элементу
  const cardRef = useRef<HTMLDivElement>(null);

  // ❗️ Хук для отслеживания URL-хэша
  useEffect(() => {
    const checkHashAndExpand = () => {
      // Получаем хэш без решетки (например "123-uuid")
      const currentHash = window.location.hash.replace("#", "");

      if (currentHash === message.messageId) {
        // Разворачиваем карточку
        setIsExpanded(true);

        // Если не прочитано — помечаем как прочитанное
        if (!isRead) {
          setIsRead(true);
          markAsReadMessageAction(message.messageId).then(() => router.refresh());
        }

        // Плавно скроллим к карточке с небольшой задержкой (чтобы дать DOM отрисовать развернутый текст)
        setTimeout(() => {
          cardRef.current?.scrollIntoView({ behavior: "smooth", block: "center" });
        }, 150);
      }
    };

    // Проверяем при первой загрузке страницы
    checkHashAndExpand();

    // Слушаем изменения хэша (если пользователь переходит из шапки, уже находясь на этой странице)
    window.addEventListener("hashchange", checkHashAndExpand);
    return () => window.removeEventListener("hashchange", checkHashAndExpand);
  }, [message.messageId, isRead, router]);

  const toggleExpand = async () => {
    setIsExpanded((prev) => !prev);
    if (!isExpanded && !isRead) {
      setIsRead(true);
      await markAsReadMessageAction(message.messageId);
      router.refresh();
    }
  };

  const formattedDate = new Date(message.createdAt).toLocaleDateString("ru-RU", {
    day: "numeric",
    month: "long",
    hour: "2-digit",
    minute: "2-digit",
  });

  return (
    <div
      ref={cardRef}
      id={message.messageId} // ❗️ Устанавливаем ID для нативной поддержки якорей
      className={cn(
        "group rounded-lg border transition-all hover:bg-muted/50 overflow-hidden",
        !isRead ? "bg-primary/5 border-primary/20" : "bg-white",
        // Подсвечиваем активную карточку (если её id совпадает с хэшем)
        isExpanded && "ring-1 ring-primary/20 shadow-sm"
      )}
    >
      <div
        onClick={toggleExpand}
        className="flex cursor-pointer items-start justify-between gap-4 p-4"
      >
        <div className="flex flex-col gap-1 w-full">
          <div className="flex items-center gap-2">
            {!isRead && <span className="h-2.5 w-2.5 shrink-0 rounded-full bg-primary" />}
            <h3
              className={cn("font-medium", !isRead ? "text-foreground" : "text-muted-foreground")}
            >
              {message.subject}
            </h3>
          </div>
          <p className="text-xs text-muted-foreground">{formattedDate}</p>
        </div>

        <div className="text-muted-foreground shrink-0 mt-1">
          {isExpanded ? <ChevronUp className="h-5 w-5" /> : <ChevronDown className="h-5 w-5" />}
        </div>
      </div>

      {isExpanded && (
        <div className="border-t">
          <div data-color-mode="light" className="p-4 wm-md-editor">
            <MDEditor.Markdown
              source={message.message}
              style={{ backgroundColor: "transparent", color: "inherit" }}
            />
          </div>
        </div>
      )}
    </div>
  );
}
