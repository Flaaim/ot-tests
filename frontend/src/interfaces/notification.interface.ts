export type NotificationStatus = "completed" | "in_progress" | "failed";
export type MessageStatus = "read" | "not_read";
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

export interface MessageItem {
  messageId: string;
  subject: string;
  message: string;
  status: MessageStatus;
  createdAt: string;
}
export interface PaginatedMessage {
  items: MessageItem[];
  totalCount: number;
  totalPages: number;
}
export interface UnreadCountResponse {
  count: number;
}

export interface FetchMessagesProps {
  page: number;
  limit: number;
}
