import { fetchCategoryTreeAction } from "@/actions/category";
import { CategoryDTO } from "@/interfaces/category.interface";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import Link from "next/link";
import { ArrowRight, Flame, FolderOpen, ShieldCheck, Zap } from "lucide-react";

const getCategoryIcon = (slug: string) => {
  if (slug.includes("pozharnaya")) return <Flame className="w-8 h-8 text-orange-500" />;
  if (slug.includes("elektro")) return <Zap className="w-8 h-8 text-yellow-500" />;
  if (slug.includes("ohrana-truda")) return <ShieldCheck className="w-8 h-8 text-blue-500" />;
  return <FolderOpen className="w-8 h-8 text-primary" />;
};
export default async function HomePage() {
  const result = await fetchCategoryTreeAction();

  if (!result.ok || !result.data) {
    return <div className="p-8 text-center">Сервис временно недоступен.</div>;
  }

  const rootCategories: CategoryDTO[] = result.data;

  return (
    <div className="max-w-6xl mx-auto py-16 px-4 space-y-12">
      <div className="text-center space-y-4">
        <h1 className="text-4xl md:text-5xl font-extrabold tracking-tight">
          Платформа проверки знаний
        </h1>
        <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
          Выберите направление для подготовки и прохождения квалификационных тестов.
        </p>
      </div>

      {/* Сетка главных категорий */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {rootCategories.map((category) => (
          <Link href={`/catalog/${category.slug}`} key={category.id} className="group outline-none">
            <Card className="h-full hover:border-primary/50 hover:shadow-lg transition-all duration-300 relative overflow-hidden">
              <CardHeader className="flex flex-row items-center gap-4 pb-2">
                <div className="p-3 bg-muted rounded-xl group-hover:bg-primary/10 transition-colors">
                  {getCategoryIcon(category.slug)}
                </div>
                <CardTitle className="text-2xl group-hover:text-primary transition-colors">
                  {category.name}
                </CardTitle>
              </CardHeader>
              <CardContent>
                <p className="text-muted-foreground text-sm mb-4">
                  Подкатегорий: {category.children?.length || 0}
                </p>
                <div className="flex items-center text-sm font-medium text-primary opacity-80 group-hover:opacity-100 transition-opacity">
                  Перейти в раздел{" "}
                  <ArrowRight className="ml-1 w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </div>
              </CardContent>
            </Card>
          </Link>
        ))}
      </div>
    </div>
  );
}
