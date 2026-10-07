"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { apiFetch } from "@/lib/apiClient";
import { API } from "@/app/api";
import { handleApiResponse } from "@/lib/handleApiResponse";
import {PaginatedNotifications} from "@/interfaces/notification.interface";

export async function fetchMessagesUnreadCountAction(): Promise<ApiResponse<number>> {
  try {
    const response = apiFetch(API.notification.message.getUnreadCount(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<number>(await response);
  } catch (error) {
    console.error("fetchMessagesUnreadCountAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function fetchNotificationsPaginatedAction(page: number, perPage: number): Promise<ApiResponse<PaginatedNotifications>> {
  try{
    const response = await apiFetch(API.notification.getPaginated(page, perPage), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<PaginatedNotifications>(await response);
  }catch(error){
    console.error("fetchGetNotificationsPaginated Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
