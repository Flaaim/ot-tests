import { fetchAttemptAction } from "@/actions/attempt";
import { AttemptInterface } from "@/interfaces/attempt.interface";
import TestRunnerClient from "@/components/Testing/TestRunnerClient";

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

  return (
    <div className="container mx-auto py-8">
      <TestRunnerClient attempt={attempt} />
    </div>
  );
}
