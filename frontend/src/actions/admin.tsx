"use server";

import { handleApiResponse } from "@/lib/handleApiResponse";
import {
  AdminAttemptsStats,
  AdminPopularTests,
  AdminUsersStats,
} from "@/interfaces/admin.interface";
import { ApiResponse } from "@/interfaces/response.interface";
import { apiFetch } from "@/lib/apiClient";
import { API } from "@/app/api";

export async function fetchAdminUsersStatsAction(): Promise<ApiResponse<AdminUsersStats>> {
  try {
    const response = await apiFetch(API.admin.getUsersStats(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<AdminUsersStats>(response);
  } catch (error) {
    console.error("fetchAdminUsersStatsAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchAdminAttemptsStatsAction(): Promise<ApiResponse<AdminAttemptsStats>> {
  try {
    const response = await apiFetch(API.admin.getAttemptsStats(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<AdminAttemptsStats>(response);
  } catch (error) {
    console.error("fetchAdminAttemptsStatsAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchAdminPopularTestsAction(): Promise<ApiResponse<AdminPopularTests[]>> {
  try {
    const response = await apiFetch(API.admin.getPopularAttempts(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<AdminPopularTests[]>(response);
  } catch (error) {
    console.error("fetchAdminPopularAttemptsAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
