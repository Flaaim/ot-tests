"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { handleApiResponse } from "@/lib/handleApiResponse";
import { API } from "@/app/api";
import { apiFetch } from "@/lib/apiClient";
import { ListAttemptsDTO, UserAttemptStatsDTO } from "@/interfaces/user.interface";

export async function fetchUserAttemptsPaginationAction(
  page: number,
  perPage: number
): Promise<ApiResponse<ListAttemptsDTO>> {
  try {
    const response = await apiFetch(API.user.getAttempts(page, perPage), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<ListAttemptsDTO>(response);
  } catch (error) {
    console.error("fetchUserAttemptsResultsAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchUserAttemptStatsAction(): Promise<ApiResponse<UserAttemptStatsDTO>> {
  try {
    const response = await apiFetch(API.user.getAttemptStats(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    return handleApiResponse<UserAttemptStatsDTO>(response);
  } catch (error) {
    console.error("fetchUserStatsAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
