"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { handleApiResponse } from "@/lib/handleApiResponse";
import { API } from "@/app/api";
import { apiFetch } from "@/lib/apiClient";
import { UserAttemptsDTO } from "@/interfaces/user.interface";

export async function fetchUserAttemptsAction(): Promise<ApiResponse<UserAttemptsDTO[]>> {
  try {
    const response = await apiFetch(API.user.getAttempts(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<UserAttemptsDTO[]>(response);
  } catch (error) {
    console.error("fetchUserAttemptsResultsAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
