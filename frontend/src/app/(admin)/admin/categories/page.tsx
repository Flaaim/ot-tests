import { fetchCategoryTreeAction } from "@/actions/category";
import AdminBreadcrumbs from "@/components/Admin/AdminBreadcrumbs";
import { AlertCircle, CornerDownRight, Folder } from "lucide-react";
import AddCategoryDialog from "@/components/Admin/Category/AddCategoryDialog";
import { CategoryDTO } from "@/interfaces/category.interface";

function flattenCategoriesForSelect(
  categories: CategoryDTO[],
  level = 0
): { id: string; name: string }[] {
  let result: { id: string; name: string }[] = [];

  for (const cat of categories) {
    // Добавляем отступы (тире) в зависимости от глубины вложенности
    const prefix = level > 0 ? "— ".repeat(level) : "";
    result.push({ id: cat.id, name: `${prefix}${cat.name}` });

    if (cat.children && cat.children.length > 0) {
      result = result.concat(flattenCategoriesForSelect(cat.children, level + 1));
    }
  }

  return result;
}

export default async function AdminCategoriesPage() {
  const result = await fetchCategoryTreeAction();

  if (!result.ok || !result.data) {
    return (
      <div className="space-y-6">
        <AdminBreadcrumbs items={[{ title: "Категории" }]} />
        <div className="flex items-center justify-between">
          <h1 className="text-3xl font-bold">Категории</h1>
          <AddCategoryDialog categories={[]} />
        </div>
        <div className="mx-auto max-w-4xl p-4 md:p-8">
          <div className="flex min-h-[40vh] flex-col items-center justify-center space-y-4 text-center">
            <div className="flex size-16 items-center justify-center rounded-full bg-destructive/10 text-destructive">
              <AlertCircle className="size-8" />
            </div>
            <h2 className="text-xl font-semibold">Не удалось загрузить категории</h2>
            <p className="text-sm text-muted-foreground">
              {result.error ?? "Произошла непредвиденная ошибка"}
            </p>
          </div>
        </div>
      </div>
    );
  }

  const tree: CategoryDTO[] = result.data;
  const flatCategoriesForDialog = flattenCategoriesForSelect(tree);

  return (
    <div className="space-y-6">
      <AdminBreadcrumbs items={[{ title: "Категории" }]} />

      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">Категории</h1>
        {/* Передаем сплющенный массив с отступами в диалог */}
        <AddCategoryDialog categories={flatCategoriesForDialog} />
      </div>

      {/* Отрисовка самого дерева категорий для админа */}
      <div className="rounded-md border bg-card">
        {tree.length === 0 ? (
          <div className="p-8 text-center text-muted-foreground">
            Категорий пока нет. Создайте первую!
          </div>
        ) : (
          <div className="divide-y">
            {tree.map((category) => (
              <CategoryRow key={category.id} category={category} level={0} />
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

// Рекурсивный компонент для отрисовки строки таблицы с учетом вложенности
function CategoryRow({ category, level }: { category: CategoryDTO; level: number }) {
  return (
    <>
      <div className="flex items-center justify-between p-4 hover:bg-muted/50 transition-colors">
        <div className="flex items-center gap-3" style={{ paddingLeft: `${level * 2}rem` }}>
          {level === 0 ? (
            <Folder className="h-5 w-5 text-primary" />
          ) : (
            <CornerDownRight className="h-4 w-4 text-muted-foreground" />
          )}
          <div>
            <p className="font-medium">{category.name}</p>
            <p className="text-xs text-muted-foreground">/{category.slug}</p>
          </div>
        </div>

        {/* Здесь в будущем можно добавить кнопки редактирования/удаления */}
        <div className="text-sm text-muted-foreground">{/* id: {category.id} */}</div>
      </div>

      {/* Если есть дочерние категории, рекурсивно рендерим их, увеличивая уровень отступа */}
      {category.children && category.children.length > 0 && (
        <div className="divide-y border-t border-dashed">
          {category.children.map((child) => (
            <CategoryRow key={child.id} category={child} level={level + 1} />
          ))}
        </div>
      )}
    </>
  );
}
