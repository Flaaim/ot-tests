import { fetchAttemptAction } from "@/actions/attempt";
import { AttemptInterface } from "@/interfaces/attempt.interface";

interface AttemptOverviewPageProps {
  params: Promise<{ attemptId: string }>;
}

export default async function AttemptOverviewPage({ params }: AttemptOverviewPageProps) {
  const { attemptId } = await params;
  const result = await fetchAttemptAction(attemptId);
  if (!result.ok || !result.data) {
    return null;
  }

  const attempt: AttemptInterface = result.data;

  console.log(attempt);
}
