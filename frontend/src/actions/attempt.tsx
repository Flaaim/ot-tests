"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { AttemptInterface, LaunchAttemptPayload } from "@/interfaces/attempt.interface";
import { apiFetch } from "@/lib/apiClient";
import { API } from "@/app/api";
import { handleApiResponse } from "@/lib/handleApiResponse";

export async function launchAttemptAction(
  payload: LaunchAttemptPayload
): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.attempt.launch(), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        testId: payload.testId,
        ticketNumber: payload.ticketNumber,
      }),
    });

    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("launchAttemptAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchAttemptAction(id: string): Promise<ApiResponse<AttemptInterface>> {
  try {
    const response = await apiFetch(API.attempt.get(id), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    return handleApiResponse<AttemptInterface>(response);
  } catch (error) {
    console.error("fetchAttemptAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
