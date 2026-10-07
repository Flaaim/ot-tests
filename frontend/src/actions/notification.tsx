"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { apiFetch } from "@/lib/apiClient";
import { API } from "@/app/api";
import { handleApiResponse } from "@/lib/handleApiResponse";
import {
  AddNotificationPayload,
  FetchMessagesProps,
  PaginatedMessage,
  PaginatedNotifications,
  UnreadCountResponse,
} from "@/interfaces/notification.interface";

export async function fetchMessagesUnreadCountAction(): Promise<ApiResponse<UnreadCountResponse>> {
  try {
    const response = apiFetch(API.notification.message.getUnreadCount(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<UnreadCountResponse>(await response);
  } catch (error) {
    console.error("fetchMessagesUnreadCountAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchNotificationsPaginatedAction(
  page: number,
  perPage: number
): Promise<ApiResponse<PaginatedNotifications>> {
  try {
    const response = await apiFetch(API.notification.getPaginated(page, perPage), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<PaginatedNotifications>(await response);
  } catch (error) {
    console.error("fetchGetNotificationsPaginated Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function removeNotificationAction(id: string): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.notification.remove(id), {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<void>(await response);
  } catch (error) {
    console.error("fetchGetNotificationsPaginated Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function addNotificationAction(
  payload: AddNotificationPayload
): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.notification.add(), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        subject: payload.subject,
        message: payload.message,
      }),
    });
    return handleApiResponse<void>(await response);
  } catch (error) {
    console.error("fetchGetNotificationsPaginated Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
export async function markAsReadMessageAction(id: string): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.notification.message.markAsRead(id), {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("readMessageAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function markAllAsReadMessagesAction(): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.notification.message.markAllAsRead(), {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("readMessageAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchMessagesAction({
  page = 1,
  limit = 5,
}: FetchMessagesProps): Promise<ApiResponse<PaginatedMessage>> {
  try {
    const response = await apiFetch(API.notification.message.get(page, limit), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<PaginatedMessage>(response);
  } catch (error) {
    console.error("fetchMessagesAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
