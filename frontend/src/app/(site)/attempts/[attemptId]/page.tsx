import { fetchAttemptAction } from "@/actions/attempt";
import { AttemptInterface } from "@/interfaces/attempt.interface";
import TestRunnerClient from "@/components/Testing/Attempt/TestRunnerClient";
import { notFound, redirect } from "next/navigation";

interface AttemptOverviewPageProps {
  params: Promise<{ attemptId: string }>;
}

export default async function AttemptOverviewPage({ params }: AttemptOverviewPageProps) {
  const { attemptId } = await params;
  const result = await fetchAttemptAction(attemptId);
  if (!result.ok || !result.data) {
    notFound();
  }

  const attempt: AttemptInterface = result.data;

  if (attempt.status !== "in_progress") {
    redirect(`/attempts/${attemptId}/result`);
  }

  return (
    <div className="container mx-auto py-8">
      <TestRunnerClient attempt={attempt} />
    </div>
  );
}
