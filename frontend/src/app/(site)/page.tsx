import { fetchCategoryTreeAction } from "@/actions/category";
import { CategoryDTO } from "@/interfaces/category.interface";

export default async function HomePage() {
  const result = await fetchCategoryTreeAction();

  if (!result.ok || !result.data.length) {
    return <div className="p-8 text-center">Сервис временно недоступен.</div>;
  }

  const rootCategories: CategoryDTO[] = result.data;
}
