"use client";

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { CheckCheck, Loader2 } from "lucide-react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { markAllAsReadMessagesAction } from "@/actions/notification";

export default function MarkAllAsReadButton() {
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  const handleMarkAll = async () => {
    setLoading(true);
    try {
      const result = await markAllAsReadMessagesAction();
      if (!result.ok) {
        toast.error("Не удалось обновить статус уведомлений");
        return;
      }
      router.refresh();
    } catch (error) {
      console.error(error);
      toast.error("Произошла ошибка");
    } finally {
      setLoading(false);
    }
  };

  return (
    <Button variant="outline" size="sm" onClick={handleMarkAll} disabled={loading}>
      {loading ? (
        <Loader2 className="mr-2 h-4 w-4 animate-spin" />
      ) : (
        <CheckCheck className="mr-2 h-4 w-4" />
      )}
      Прочитать все
    </Button>
  );
}
