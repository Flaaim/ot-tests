"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { handleApiResponse } from "@/lib/handleApiResponse";
import { API } from "@/app/api";
import { apiFetch } from "@/lib/apiClient";
import { ListAttemptsDTO, PaginatedUsers, UserAttemptStatsDTO } from "@/interfaces/user.interface";
import { ProfileDTO } from "@/interfaces/auth.interface";

export async function fetchUserAttemptsPaginationAction(
  page: number,
  perPage: number
): Promise<ApiResponse<ListAttemptsDTO>> {
  try {
    const response = await apiFetch(API.profile.getAttempts(page, perPage), {
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

export async function fetchUsersPagination(
  currentPage: number,
  perPage: number
): Promise<ApiResponse<PaginatedUsers>> {
  try {
    const response = await apiFetch(API.profile.getPaginated(currentPage, perPage), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    return handleApiResponse<PaginatedUsers>(response);
  } catch (error) {
    console.error("fetchUsersPagination Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchProfile(): Promise<ProfileDTO> {
  const response = await apiFetch(API.profile.getProfile(), {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
  });

  const parsed = await handleApiResponse<ProfileDTO>(response);
  if (!parsed.ok || !parsed.data) {
    throw new Error(parsed.error || "Не удалось загрузить профиль");
  }
  return parsed.data;
}

export async function fetchAttemptStatAction(): Promise<ApiResponse<UserAttemptStatsDTO>> {
  try {
    const response = await apiFetch(API.profile.getTestingStat(), {
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
