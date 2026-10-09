import { AdminPopularTests } from "@/interfaces/admin.interface";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { TrendingUp } from "lucide-react";

interface PopularTestsCardProps {
  tests: AdminPopularTests[];
}

export default function PopularTestsCard({ tests }: PopularTestsCardProps) {
  return (
    <Card className="shadow-sm">
      <CardHeader className="flex flex-row items-center justify-between pb-2">
        <div className="space-y-1">
          <CardTitle className="text-base font-semibold">Популярные тесты</CardTitle>
          <p className="text-sm text-muted-foreground">Топ тестов по количеству прохождений</p>
        </div>
        <TrendingUp className="size-4 text-muted-foreground" />
      </CardHeader>
      <CardContent>
        {tests.length === 0 ? (
          <p className="text-sm text-muted-foreground text-center py-4">
            Нет данных для отображения
          </p>
        ) : (
          <div className="space-y-4 mt-4">
            {tests.map((test, index) => (
              <div
                key={`${test.cipher}-${index}`}
                className="flex items-center justify-between gap-4"
              >
                <div className="flex items-center gap-4 overflow-hidden">
                  <div className="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary font-semibold text-sm">
                    {index + 1}
                  </div>
                  <div className="overflow-hidden">
                    <p className="text-sm font-medium leading-none truncate">{test.name}</p>
                    <p className="text-xs text-muted-foreground mt-1 truncate">
                      Шифр: {test.cipher}
                    </p>
                  </div>
                </div>
                <div className="text-sm font-semibold shrink-0">
                  {test.totalAttempts.toLocaleString("ru-RU")}
                  <span className="text-xs text-muted-foreground font-normal ml-1">попыток</span>
                </div>
              </div>
            ))}
          </div>
        )}
      </CardContent>
    </Card>
  );
}
