export interface AdminUsersStats {
  totalUsers: number;
  registrationsToday: number;
  registrationsThisWeek: number;
  registrationsLast30Days: number;
}

export interface AdminAttemptsStats {
  totalAttempts: number;
  attemptsToday: number;
  attemptsThisWeek: number;
  totalPassedAttempts: number;
  successRate: number;
}
