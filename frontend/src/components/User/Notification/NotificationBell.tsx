import { Button } from "@/components/ui/button";
import { Bell } from "lucide-react";
import { fetchMessagesAction, fetchMessagesUnreadCountAction } from "@/actions/notification";
import NotificationDropdown from "@/components/User/Notification/NotificationDropdown";
import { PaginatedMessage, UnreadCountResponse } from "@/interfaces/notification.interface";

export default async function NotificationBell() {
  const [countRes, messagesRes] = await Promise.all([
    fetchMessagesUnreadCountAction(),
    fetchMessagesAction({ page: 1, limit: 3 }),
  ]);

  if (!countRes.ok || !messagesRes.ok || !countRes.data || !messagesRes.data) {
    return (
      <Button variant="outline" size="icon" disabled>
        <Bell className="h-5 w-5 opacity-50" />
      </Button>
    );
  }

  const messages: PaginatedMessage = messagesRes.data;
  const unreadCountMessages: UnreadCountResponse = countRes.data;
  return (
    <NotificationDropdown
      initialCount={unreadCountMessages.count || 0}
      initialMessages={messages.items || []}
    />
  );
}
