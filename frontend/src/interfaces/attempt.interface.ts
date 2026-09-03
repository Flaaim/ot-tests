export interface AttemptInterface {
  id: string;
  status: string;
  ticketNumber: number;
  questions: Question[];
}

export interface LaunchAttemptPayload {
  testId: string;
  ticketNumber: number;
}

export interface Question {
  id: string;
  text: string;
  questionImg: string;
  answers: Answer[];
  form: string;
}

interface Answer {
  id: string;
  text: string;
  answerImg: string;
}

export interface SubmitAnswerPayload {
  id: string;
  questionId: string;
  selectedAnswersIds: string[];
}

export interface UserResultDTO {
  selectedIds: string[];
  isCorrect: boolean;
}

export interface ResultQuestionDTO {
  id: string;
  text: string;
  questionImg: string | null;
  answers: any; // Массив для choice/sequence, объект { left, right } для matching
  form: "single_choice" | "multiple_choice" | "sequence" | "matching";
  userResult: UserResultDTO | null;
}
export interface TestInfoDTO {
  name: string;
  cipher: string;
  allowedMistakes: number;
}

export interface AttemptResultData {
  id: string;
  status: string;
  score: number;
  mistakes: number;
  ticketNumber: number;
  startedAt: string;
  finishedAt: string | null;
  test: TestInfoDTO;
  questions: ResultQuestionDTO[];
  email: string;
}
