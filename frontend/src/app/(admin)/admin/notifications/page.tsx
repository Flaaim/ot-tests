import {
  fetchGetNotificationsPaginatedAction,
  fetchNotificationsPaginatedAction,
} from "@/actions/notification";
import AdminBreadcrumbs from "@/components/Admin/AdminBreadcrumbs";
import { AlertCircle } from "lucide-react";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { NotificationItem, PaginatedNotifications } from "@/interfaces/notification.interface";
import Link from "next/link";
import RemoveNotificationDialog from "@/components/Admin/Notification/RemoveNotificationDialog";
import AddNotificationDialog from "@/components/Admin/Notification/AddNotificationDialog";

interface AdminNotificationPageProps {
  searchParams: Promise<{ page?: string; perPage?: string }>;
}
export default async function AdminNotificationPage({ searchParams }: AdminNotificationPageProps) {
  const currentPage = Number((await searchParams).page) || 1;
  const perPage = Number((await searchParams).perPage) || 15;

  const result = await fetchNotificationsPaginatedAction(currentPage, perPage);

  if (!result.ok || !result.data) {
    return (
      <div className="space-y-6">
        <AdminBreadcrumbs items={[{ title: "Уведомления" }]} />
        <div className="flex items-center justify-between">
          <h1 className="text-3xl font-bold">Уведомления</h1>
        </div>
        <div className="mx-auto max-w-4xl p-4 md:p-8">
          <div className="flex min-h-[40vh] flex-col items-center justify-center space-y-4 text-center">
            <div className="flex size-16 items-center justify-center rounded-full bg-destructive/10 text-destructive">
              <AlertCircle className="size-8" />
            </div>
            <h2 className="text-xl font-semibold">Не удалось загрузить уведомления</h2>
            <p className="text-sm text-muted-foreground">
              {result.error ?? "Произошла непредвиденная ошибка"}
            </p>
          </div>
        </div>
      </div>
    );
  }

  const notifications: PaginatedNotifications = result.data;
  const hasNotifications: boolean = notifications && notifications.items.length > 0;

  return (
    <div className="space-y-6">
      <AdminBreadcrumbs items={[{ title: "Уведомления" }]} />
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">Уведомления</h1>
        <AddNotificationDialog />
      </div>
      <div className="rounded-md border bg-white">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Id</TableHead>
              <TableHead>Тема</TableHead>
              <TableHead>Статус</TableHead>
              <TableHead>Создано</TableHead>
              <TableHead>Отправлено/Прочитано</TableHead>
              <TableHead>Удалить</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {!hasNotifications ? (
              <TableRow>
                <TableCell colSpan={6} className="text-muted-foreground">
                  Уведомления отсутствуют...
                </TableCell>
              </TableRow>
            ) : (
              notifications.items.map((notification: NotificationItem) => (
                <TableRow key={notification.notificationId}>
                  <TableCell className="font-medium">
                    <Link
                      href={`/admin/notifications/${notification.notificationId}`}
                      className="hover:underline"
                    >
                      {notification.notificationId}
                    </Link>
                  </TableCell>
                  <TableCell className="font-medium">{notification.subject}</TableCell>
                  <TableCell className="font-medium">{notification.status}</TableCell>
                  <TableCell className="font-medium">{notification.createdAt}</TableCell>
                  <TableCell className="font-medium">
                    {notification.countMessages}/{notification.readCountMessages}
                  </TableCell>
                  <TableCell>
                    <RemoveNotificationDialog id={notification.notificationId} />{" "}
                  </TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>
    </div>
  );
}
