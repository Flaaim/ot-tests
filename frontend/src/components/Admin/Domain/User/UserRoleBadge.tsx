import {Badge} from "@/components/ui/badge";

const USER_ROLE: Record<string, string> = {
  admin: "Админ",
  user: "Пользователь",
  company: "Компания"
}

interface UserRoleBadgeProps {
  type: string;
}

export default function UserRoleBadge({type}: UserRoleBadgeProps) {
  return <Badge variant="outline">{USER_ROLE[type]}</Badge>;
}
