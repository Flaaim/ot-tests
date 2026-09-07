import { fetchPublicCategoryTreeAction } from "@/actions/category";
import { notFound } from "next/navigation";
import Link from "next/link";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { ChevronRight, Folder, ArrowRight } from "lucide-react";

interface CategoryPageProps {
  params: Promise<{ categorySlug: string }>;
}

export default async function CategoryPage({ params }: CategoryPageProps) {
  const { categorySlug } = await params;
  const result = await fetchPublicCategoryTreeAction();

  if (!result.ok || !result.data) {
    return (
      <div className="flex h-64 w-full items-center justify-center rounded-lg border border-dashed text-muted-foreground">
        Сервис временно недоступен.
      </div>
    );
  }

  // Ищем нужную родительскую категорию в дереве
  const category = result.data.find((c) => c.slug === categorySlug);

  if (!category) {
    notFound();
  }

  const subcategories = category.children || [];

  return (
    <div className="space-y-8 pb-10">
      {/* Хлебные крошки */}
      <nav className="flex items-center text-sm font-medium text-muted-foreground">
        <Link href="/" className="hover:text-foreground transition-colors">
          Главная
        </Link>
        <ChevronRight className="mx-2 h-4 w-4" />
        <Link href="/catalog" className="hover:text-foreground transition-colors">
          Каталог
        </Link>
        <ChevronRight className="mx-2 h-4 w-4" />
        <span className="text-foreground">{category.name}</span>
      </nav>

      <div className="space-y-4">
        <h1 className="text-3xl font-extrabold tracking-tight md:text-4xl">{category.name}</h1>
        {category.description && (
          <p className="text-lg text-muted-foreground max-w-3xl">{category.description}</p>
        )}
      </div>

      {subcategories.length === 0 ? (
        <div className="rounded-lg border border-dashed p-8 text-center text-muted-foreground">
          В этой категории пока нет доступных направлений.
        </div>
      ) : (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {subcategories.map((sub) => (
            <Card
              key={sub.id}
              className="relative flex flex-col transition-colors duration-200 hover:border-primary/50 hover:shadow-sm"
            >
              <CardHeader className="pb-4">
                <CardTitle className="flex items-center gap-3 text-lg">
                  <Folder className="h-5 w-5 text-muted-foreground" />
                  <Link
                    href={`/catalog/${category.slug}/${sub.slug}`}
                    className="before:absolute before:inset-0 hover:text-primary focus:outline-none"
                  >
                    {sub.name}
                  </Link>
                </CardTitle>
              </CardHeader>
              <CardContent className="mt-auto flex items-center justify-between text-sm font-medium text-muted-foreground transition-colors group-hover:text-primary">
                Перейти к тестам
                <ArrowRight className="h-4 w-4" />
              </CardContent>
            </Card>
          ))}
        </div>
      )}
    </div>
  );
}
