import { CategoryFull } from "@/interfaces/category.interface";
import { fetchCategoryAction, fetchCategoryTreeAction } from "@/actions/category";
import AdminBreadcrumbs from "@/components/Admin/AdminBreadcrumbs";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import RenameCategoryDialog from "@/components/Admin/Category/RenameCategoryDialog";
import MoveCategoryDialog from "@/components/Admin/Category/MoveCategoryDialog";

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

  const categoriesResult = await fetchCategoryTreeAction();
  const categoriesTree = categoriesResult.ok && categoriesResult.data ? categoriesResult.data : [];

  return (
    <div className="space-y-6">
      <AdminBreadcrumbs items={items} />
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">Основная информация</h1>
      </div>
      <div className="space-y-6">
        <Card>
          <CardHeader>
            <div className="grid grid-cols-2 gap-4 items-center">
              <CardTitle>Категория: {category.name}</CardTitle>
              <div className="justify-self-end">
                <RenameCategoryDialog id={category.id} name={category.name} />
              </div>
            </div>
          </CardHeader>
          <CardContent className="space-y-4 text-sm">
            <p className="text-muted-foreground border-b pb-4">{category.description}</p>
            <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
              <div>
                <p className="text-muted-foreground font-medium">ID</p>
                <p className="font-mono">{category.id}</p>
                <MoveCategoryDialog categories={categoriesTree} category={category} />
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
