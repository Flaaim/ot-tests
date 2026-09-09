export interface UserAttemptDTO {
  id: string;
  status: string;
  score: number;
  mistakes: number;
  ticketNumber: number;
  startedAt: string;
  finishedAt: string | null;
  name: string;
  cipher: string;
  allowedMistakes: number;
}

export interface ListAttemptsDTO {
  items: UserAttemptDTO[];
  totalCount: number;
  totalPages: number;
}

export interface UserAttemptStatsDTO {
  completedTests: number;
  inProgressTests: number;
  averageScore: number;
}
