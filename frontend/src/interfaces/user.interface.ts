export interface UserAttemptsDTO {
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
