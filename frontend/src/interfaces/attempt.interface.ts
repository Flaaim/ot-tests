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

interface Question {
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
