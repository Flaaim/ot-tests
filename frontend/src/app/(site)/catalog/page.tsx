import { fetchPublicCategoryTreeAction } from "@/actions/category";
import { CategoryDTO } from "@/interfaces/category.interface";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import Link from "next/link";
import { ArrowRight, ChevronRight, Flame, FolderOpen, ShieldCheck, Zap } from "lucide-react";
import { Metadata } from "next";

// Тот же хелпер для иконок, что и на главной странице
const getCategoryIcon = (slug: string) => {
  if (slug.includes("pozharnaya")) return <Flame className="h-6 w-6 text-orange-500" />;
  if (slug.includes("elektro")) return <Zap className="h-6 w-6 text-yellow-500" />;
  if (slug.includes("ohrana-truda")) return <ShieldCheck className="h-6 w-6 text-blue-500" />;
  return <FolderOpen className="h-6 w-6 text-primary" />;
};

export const metadata: Metadata = {
  title: "Каталог тестов",
  description: "Выберите направление тестирования: охрана труда, пожарная безопасность и другие.",
};

export default async function CatalogPage() {
  const result = await fetchPublicCategoryTreeAction();

  if (!result.ok || !result.data) {
    return (
      <div className="flex h-64 w-full items-center justify-center rounded-lg border border-dashed text-muted-foreground">
        Не удалось загрузить каталог тестов.
      </div>
    );
  }

  const tree: CategoryDTO[] = result.data;

  return (
    <div className="space-y-8 pb-10">
      {/* Хлебные крошки и заголовок */}
      <div className="space-y-4">
        <nav className="flex items-center text-sm font-medium text-muted-foreground">
          <Link href="/" className="hover:text-foreground transition-colors">
            Главная
          </Link>
          <ChevronRight className="mx-2 h-4 w-4" />
          <span className="text-foreground">Каталог</span>
        </nav>

        <h1 className="text-3xl font-extrabold tracking-tight md:text-4xl">Каталог направлений</h1>
        <p className="text-lg text-muted-foreground">
          Выберите интересующий вас раздел для просмотра доступных тестов.
        </p>
      </div>

      {/* Сетка категорий (2 колонки отлично впишутся в ваш Grid 700px) */}
      <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
        {tree.map((category) => (
          <Card
            key={category.id}
            // Обязательно relative для корректной работы ссылки-оверлея
            className="relative flex flex-col transition-all duration-200 hover:border-primary/50 hover:shadow-sm"
          >
            <CardHeader className="pb-4">
              <CardTitle className="flex items-center gap-3 text-xl">
                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-muted/50 transition-colors group-hover:bg-primary/10">
                  {getCategoryIcon(category.slug)}
                </div>
                <Link
                  href={`/catalog/${category.slug}`}
                  className="before:absolute before:inset-0 hover:text-primary focus:outline-none"
                >
                  {category.name}
                </Link>
              </CardTitle>
            </CardHeader>

            <CardContent className="relative z-10 flex flex-1 flex-col gap-2">
              {category.children && category.children.length > 0 ? (
                <div className="flex flex-col gap-1">
                  {category.children.slice(0, 3).map((sub) => (
                    <Link
                      key={sub.id}
                      href={`/catalog/${category.slug}/${sub.slug}`}
                      className="group flex items-center justify-between rounded-md p-2 -mx-2 text-sm transition-colors hover:bg-muted"
                    >
                      <span className="font-medium text-muted-foreground transition-colors group-hover:text-foreground">
                        {sub.name}
                      </span>
                      <ArrowRight className="h-4 w-4 text-primary opacity-0 transition-all group-hover:translate-x-1 group-hover:opacity-100" />
                    </Link>
                  ))}
                  {category.children.length > 3 && (
                    <Link
                      href={`/catalog/${category.slug}`}
                      className="mt-2 text-sm font-medium text-primary hover:underline"
                    >
                      Показать все направления ({category.children.length})
                    </Link>
                  )}
                </div>
              ) : (
                <div className="mt-auto pt-4">
                  <span className="text-sm italic text-muted-foreground">
                    Перейти к списку тестов...
                  </span>
                </div>
              )}
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  );
}
