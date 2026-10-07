export type NotificationStatus = "completed" | "in_progress" | "failed"

export interface NotificationItem {
  notificationId: string;
  status: NotificationStatus;
  subject: string;
  createdAt: string;
  countMessages: number;
  unreadMessages: number;
}

export interface PaginatedNotifications {
  items: NotificationItem[];
  totalCount: number;
  totalPages: number;
}
