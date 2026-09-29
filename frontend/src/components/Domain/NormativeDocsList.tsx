"use client";

import { useState } from "react";
import { ChevronDown, ChevronUp, FileText } from "lucide-react";
import { Button } from "@/components/ui/button";

interface NormativeDocsListProps {
  items: string[];
  visibleCount: number;
}

export default function NormativeDocsList({ items, visibleCount }: NormativeDocsListProps) {
  const [isExpanded, setIsExpanded] = useState(false);

  if (!items || items.length === 0) {
    return (
      <p className="text-xs text-muted-foreground">Не указаны. Будут добавлены в ближайшее время</p>
    );
  }

  const hasMore = items.length > visibleCount;
  const visibleItems = isExpanded ? items : items.slice(0, visibleCount);

  return (
    <div className="space-y-3">
      <ul className="space-y-2">
        {visibleItems.map((item, idx) => (
          <li
            key={idx}
            className="flex items-start gap-2 p-2 rounded-md border bg-slate-50 text-xs text-slate-700"
          >
            <FileText className="w-5 h-5 text-slate-400 shrink-0 mt-0.5" />
            <span className="leading-relaxed">{item}</span>
          </li>
        ))}
      </ul>

      {hasMore && (
        <Button
          variant="ghost"
          size="sm"
          onClick={() => setIsExpanded(!isExpanded)}
          className="w-full text-muted-foreground hover:bg-slate-100"
        >
          {isExpanded ? (
            <>
              Скрыть <ChevronUp className="ml-2 h-4 w-4" />
            </>
          ) : (
            <>
              Показать все ({items.length}) <ChevronDown className="ml-2 h-4 w-4" />
            </>
          )}
        </Button>
      )}
    </div>
  );
}
