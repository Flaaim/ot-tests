import { fetchMessagesAction } from "@/actions/notification";
import UserBreadcrumbs from "@/components/User/UserBreadcrumbs";
import { MessageItem, PaginatedMessage } from "@/interfaces/notification.interface";
import { BellOff } from "lucide-react";
import Pagination from "@/components/Pagination/Pagination";
import MessageCard from "@/components/User/Notification/MessageCard";
import MarkAllAsReadButton from "@/components/User/Notification/MarkAllAsReadButton";

interface NotificationPageProps {
  searchParams: Promise<{ page?: string; perPage?: string }>;
}

export default async function NotificationPage({ searchParams }: NotificationPageProps) {
  const currentPage = Number((await searchParams).page) || 1;
  const perPage = Number((await searchParams).perPage) || 5;

  const result = await fetchMessagesAction({ page: currentPage, limit: perPage });

  if (!result.ok || !result.data) {
    return (
      <div className="space-y-6">
        <UserBreadcrumbs items={[{ title: "Уведомления" }]} />
        <div className="flex items-center justify-between">
          <h1 className="text-3xl font-bold tracking-tight">Уведомления</h1>
        </div>
        <div className="flex h-64 w-full items-center justify-center rounded-lg border border-dashed bg-muted/30 text-muted-foreground">
          Проблема при загрузке данных. Попробуйте обновить страницу.
        </div>
      </div>
    );
  }

  const notifications: PaginatedMessage = result.data;
  const hasNotifications: boolean =
    Array.isArray(notifications.items) && notifications.items.length > 0;

  return (
    <div className="space-y-6">
      <UserBreadcrumbs items={[{ title: "Уведомления" }]} />

      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold tracking-tight">Уведомления</h1>
        {hasNotifications && <MarkAllAsReadButton />}
      </div>

      {!hasNotifications ? (
        <div className="flex h-64 w-full flex-col items-center justify-center rounded-lg border border-dashed bg-muted/30 text-muted-foreground">
          <BellOff className="mb-4 h-10 w-10 opacity-20" />
          <p>У вас пока нет уведомлений.</p>
        </div>
      ) : (
        <div className="flex flex-col gap-3">
          {notifications.items.map((msg: MessageItem) => (
            <MessageCard key={msg.messageId} message={msg} />
          ))}
        </div>
      )}

      <Pagination
        currentPage={currentPage}
        totalPages={notifications.totalPages}
        baseUrl="/user/notifications"
      />
    </div>
  );
}
