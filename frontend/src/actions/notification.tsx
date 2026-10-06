"use server";

import {ApiResponse} from "@/interfaces/response.interface";
import {apiFetch} from "@/lib/apiClient";
import {API} from "@/app/api";
import {handleApiResponse} from "@/lib/handleApiResponse";

export async function fetchMessagesUnreadCount(): Promise<ApiResponse<number>> {
  try{
    const response = apiFetch(API.notification.message.getUnreadCount(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<number>(await response);
  }catch (error){
    console.error("fetchNotificationCountMessages Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
