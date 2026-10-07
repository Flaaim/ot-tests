export type NotificationStatus = "completed" | "in_progress" | "failed";

export interface NotificationItem {
  notificationId: string;
  status: NotificationStatus;
  subject: string;
  createdAt: string;
  countMessages: number;
  readCountMessages: number;
}

export interface PaginatedNotifications {
  items: NotificationItem[];
  totalCount: number;
  totalPages: number;
}
export interface AddNotificationPayload {
  subject: string;
  message: string;
}
