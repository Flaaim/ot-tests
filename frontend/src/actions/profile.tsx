"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { handleApiResponse } from "@/lib/handleApiResponse";
import { API } from "@/app/api";
import { apiFetch } from "@/lib/apiClient";
import {
  AddProfilePayload,
  ChangePersonalDataPayload,
  ListAttemptsDTO,
  PaginatedProfiles,
  ProfileDTO,
  ProfileFull,
  UserAttemptStatsDTO,
} from "@/interfaces/user.interface";

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
): Promise<ApiResponse<PaginatedProfiles>> {
  try {
    const response = await apiFetch(API.profile.getPaginated(currentPage, perPage), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    return handleApiResponse<PaginatedProfiles>(response);
  } catch (error) {
    console.error("fetchUsersPagination Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchProfile(): Promise<ApiResponse<ProfileDTO>> {
  try {
    const response = await apiFetch(API.profile.getProfile(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return await handleApiResponse<ProfileDTO>(response);
  } catch (error) {
    console.error("fetchProfile Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
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

export async function removeProfileAction(id: string): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.profile.remove(id), {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("removeProfileAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function addProfileAction(values: AddProfilePayload): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.profile.add(), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        email: values.email,
        role: values.role,
        name: values.name,
        surname: values.surname,
      }),
    });

    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("removeProfileAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchProfileAction(id: string): Promise<ApiResponse<ProfileFull>> {
  try {
    const response = await apiFetch(API.profile.get(id), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    return handleApiResponse<ProfileFull>(response);
  } catch (error) {
    console.error("fetchProfileAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function changePersonalDataAction(
  payload: ChangePersonalDataPayload
): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.profile.changePersonalData(), {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        name: payload.name,
        surname: payload.surname,
      }),
    });
    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("changePersonalDataAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
