import { Badge } from "@/components/ui/badge";

const PROFILE_STATUS: Record<string, string> = {
  ok: "ОК",
  banned: "Забанен",
};

interface ProfileStatusBadgeProps {
  type: string;
}

export default function ProfileStatusBadge({ type }: ProfileStatusBadgeProps) {
  return <Badge variant="outline">{PROFILE_STATUS[type]}</Badge>;
}
