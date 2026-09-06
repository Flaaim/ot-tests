import { CategoryFull } from "@/interfaces/category.interface";
import { fetchCategoryAction } from "@/actions/category";
import AdminBreadcrumbs from "@/components/Admin/AdminBreadcrumbs";

interface CategoryOverviewPageProps {
  params: Promise<{ categoryId: string }>;
}
export default async function CourseOverviewPage({ params }: CategoryOverviewPageProps) {
  const { categoryId } = await params;

  const result = await fetchCategoryAction(categoryId);

  if (!result.ok || !result.data) {
    return null;
  }

  const category: CategoryFull = result.data;
  const items = [{ title: "Категории", href: "/admin/categories" }, { title: category.name }];

  return (
    <div className="space-y-6">
      <AdminBreadcrumbs items={items} />
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">Основная информация</h1>
      </div>
    </div>
  );
}
