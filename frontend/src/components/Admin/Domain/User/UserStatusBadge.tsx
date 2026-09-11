import {Badge} from "@/components/ui/badge";


const USER_STATUS: Record<string, string> = {
  wait: "Подтверждение",
  active: "Активный"
}

interface UserStatusBadgeProps {
  type: string;
}

export default function UserStatusBadge({ type }: UserStatusBadgeProps) {
  return <Badge variant="outline">{USER_STATUS[type]}</Badge>;
}
