"use client";

import { MessageItem } from "@/interfaces/notification.interface";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { markAllAsReadMessagesAction, markAsReadMessageAction } from "@/actions/notification";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import { Bell, CheckCheck } from "lucide-react";
import Link from "next/link";
import { cn } from "@/lib/utils";
import { format } from "date-fns";
import { ru } from "date-fns/locale";

interface NotificationDropdownProps {
  initialCount: number;
  initialMessages: MessageItem[];
}

export default function NotificationDropdown({
  initialCount,
  initialMessages,
}: NotificationDropdownProps) {
  const router = useRouter();

  // Храним данные в стейте для мгновенного визуального обновления (Optimistic UI)
  const [count, setCount] = useState(initialCount);
  const [messages, setMessages] = useState<MessageItem[]>(initialMessages);

  const handleItemClick = async (id: string, currentStatus: string) => {
    router.push(`/user/notifications#${id}`);

    // 2. Если сообщение не прочитано, обновляем счетчики
    if (currentStatus === "not_read") {
      setCount((prev) => Math.max(0, prev - 1));
      setMessages((prev) =>
        prev.map((msg) => (msg.messageId === id ? { ...msg, status: "read" } : msg))
      );
      await markAsReadMessageAction(id);
      router.refresh();
    }
  };

  const formatDate = (dateString: string | null) => {
    if (!dateString) return "—";
    return format(new Date(dateString), "dd.MM.yyyy", { locale: ru });
  };

  const handleMarkAllAsRead = async () => {
    if (count === 0) return;

    setCount(0);
    setMessages((prev) => prev.map((msg) => ({ ...msg, status: "read" })));

    await markAllAsReadMessagesAction();
    router.refresh();
  };

  return (
    <DropdownMenu modal={false}>
      <DropdownMenuTrigger
        render={<Button variant="outline" size="icon" className="relative cursor-pointer" />}
      >
        <Bell className="h-5 w-5" />
        {count > 0 && (
          <span className="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
            {count > 99 ? "99+" : count}
          </span>
        )}
      </DropdownMenuTrigger>

      <DropdownMenuContent align="end" className="w-80 p-0">
        <div className="flex items-center justify-between p-4 border-b">
          <span className="font-semibold">Уведомления</span>
          {count > 0 && (
            <Button
              variant="ghost"
              size="sm"
              onClick={handleMarkAllAsRead}
              className="text-xs text-muted-foreground hover:text-primary h-auto px-2 py-1"
            >
              <CheckCheck className="w-4 h-4 mr-1" />
              Прочитать все
            </Button>
          )}
        </div>

        <div className="max-h-[300px] overflow-y-auto">
          {messages.length === 0 ? (
            <div className="p-4 text-center text-sm text-muted-foreground">
              Нет новых уведомлений
            </div>
          ) : (
            messages.map((msg: MessageItem) => (
              <DropdownMenuItem
                key={msg.messageId}
                className={cn(
                  "flex flex-col items-start p-3 cursor-pointer border-b last:border-0 rounded-none focus:bg-accent",
                  msg.status === "not_read" ? "bg-primary/5" : ""
                )}
                onClick={() => handleItemClick(msg.messageId, msg.status)}
              >
                <div className="flex items-center justify-between w-full mb-1">
                  <p
                    className={cn(
                      "text-sm font-medium leading-none",
                      msg.status === "not_read" ? "text-primary" : ""
                    )}
                  >
                    {msg.subject}
                  </p>
                  {msg.status === "not_read" && (
                    <span className="w-2 h-2 rounded-full bg-primary shrink-0" />
                  )}
                </div>
                <span className="text-[10px] text-muted-foreground/70 mt-2">
                  {formatDate(msg.createdAt)}
                </span>
              </DropdownMenuItem>
            ))
          )}
        </div>

        <div className="p-2 border-t text-center">
          <Link href="/user/notifications" className="text-sm text-primary hover:underline">
            Смотреть все уведомления
          </Link>
        </div>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
