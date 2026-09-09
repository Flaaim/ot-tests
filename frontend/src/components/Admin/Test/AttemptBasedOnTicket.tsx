"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { launchAttemptAction } from "@/actions/attempt";
import { Loader2, PlayIcon } from "lucide-react";
import { Button } from "@/components/ui/button";
import { toast } from "sonner";

interface AttemptBasedOnTicketProps {
  testId: string;
  ticketNumber: number;
}

export default function AttemptBasedOnTicket({ testId, ticketNumber }: AttemptBasedOnTicketProps) {
  const [loading, setLoading] = useState<boolean>(false);
  const router = useRouter();

  const handleLaunch = async () => {
    setLoading(true);

    const result = await launchAttemptAction({ testId, ticketNumber });

    if (!result.ok || !result.data) {
      toast.error(result.error ?? "Не удалось запустить тест.");
      setLoading(false);
      return;
    }

    router.push(`/admin/attempts/${result.data.attemptId}`);
  };

  return (
    <Button
      onClick={handleLaunch}
      disabled={loading}
      variant="default"
      size="sm"
      className="w-full sm:w-auto"
    >
      {loading ? (
        <Loader2 className="mr-2 h-4 w-4 animate-spin" />
      ) : (
        <PlayIcon className="mr-2 h-4 w-4" />
      )}
      Пройти билет
    </Button>
  );
}
