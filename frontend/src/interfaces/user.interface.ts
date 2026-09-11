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

export interface UserDTO {
  id: string;
  email: string;
  status: string;
  role: string;
  date: string;
}

export interface PaginatedUsers {
  items: UserDTO[];
  totalCount: number;
  totalPages: number;
}
