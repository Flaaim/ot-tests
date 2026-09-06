"use server";

import { ApiResponse } from "@/interfaces/response.interface";
import { AddCategoryPayload, CategoryDTO } from "@/interfaces/category.interface";
import { apiFetch } from "@/lib/apiClient";
import { API } from "@/app/api";
import { handleApiResponse } from "@/lib/handleApiResponse";

export async function fetchCategoryTreeAction(): Promise<ApiResponse<CategoryDTO[]>> {
  try {
    const response = await apiFetch(API.category.getAll(), {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });
    return handleApiResponse<CategoryDTO[]>(response);
  } catch (error) {
    console.error("fetchCategoryTreeAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}

export async function addCategoryAction(payload: AddCategoryPayload): Promise<ApiResponse<void>> {
  try {
    const response = await apiFetch(API.category.add(), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        name: payload.name,
        description: payload.description,
        parentId: payload.parentId,
      }),
    });
    return handleApiResponse<void>(response);
  } catch (error) {
    console.error("addCategoryAction Fetch error:", error);
    return { ok: false, error: "Не удалось подключиться к серверу API." };
  }
}
