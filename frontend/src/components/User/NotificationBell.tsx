import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from "@/components/ui/dropdown-menu";
import {Button} from "@/components/ui/button";
import {Bell} from "lucide-react";
import {fetchMessagesUnreadCount} from "@/actions/notification";


export default async function NotificationBell() {

  const unreadCountMessages = await fetchMessagesUnreadCount();
  if(!unreadCountMessages.ok || !unreadCountMessages.data){
    return (
      <>
        <Bell className="h-5 w-5" />
        <span className="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
            0
          </span>
      </>
    )
  }

  const messageCount = unreadCountMessages.data.count
  return (
    <DropdownMenu>
      <DropdownMenuTrigger render={ <Button variant="outline" size="icon" className="relative" />}>
        <Bell className="h-5 w-5" />
          <span className="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
            {messageCount || 0}
          </span>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" className="w-80">
        <div className="flex items-center justify-between p-4 font-semibold border-b">
          <span>Уведомления</span>
        </div>
        <DropdownMenuItem className="p-3 cursor-pointer">
          <div className="flex flex-col gap-1">
            <p className="text-sm font-medium">Новое сообщение</p>
            <p className="text-xs text-muted-foreground">Оставил комментарий к вашему посту.</p>
          </div>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
