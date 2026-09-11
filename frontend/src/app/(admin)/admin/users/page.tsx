import { fetchUsersPagination } from "@/actions/user";
import AdminBreadcrumbs from "@/components/Admin/AdminBreadcrumbs";
import { AlertCircle } from "lucide-react";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { PaginatedUsers, UserDTO } from "@/interfaces/user.interface";
import Pagination from "@/components/Pagination/Pagination";
import UserStatusBadge from "@/components/Admin/Domain/User/UserStatusBadge";
import UserRoleBadge from "@/components/Admin/Domain/User/UserRoleBadge";

interface AdminUsersPageProps {
  searchParams: Promise<{ page?: string; perPage?: string }>;
}

export default async function AdminUsersPage({ searchParams }: AdminUsersPageProps) {
  const currentPage = Number((await searchParams).page) || 1;
  const perPage = Number((await searchParams).perPage) || 15;
  const result = await fetchUsersPagination(currentPage, perPage);

  if (!result.ok || !result.data) {
    return (
      <div className="space-y-6">
        <AdminBreadcrumbs items={[{ title: "Пользователи" }]} />
        <div className="flex items-center justify-between">
          <h1 className="text-3xl font-bold">Пользователи</h1>
        </div>
        <div className="mx-auto max-w-4xl p-4 md:p-8">
          <div className="flex min-h-[40vh] flex-col items-center justify-center space-y-4 text-center">
            <div className="flex size-16 items-center justify-center rounded-full bg-destructive/10 text-destructive">
              <AlertCircle className="size-8" />
            </div>
            <h2 className="text-xl font-semibold">Не удалось загрузить пользователей</h2>
            <p className="text-sm text-muted-foreground">
              {result.error ?? "Произошла непредвиденная ошибка"}
            </p>
          </div>
        </div>
      </div>
    );
  }
  const paginatedUsers: PaginatedUsers = result.data;

  const users = paginatedUsers.items;

  return (
    <div className="space-y-6">
      <AdminBreadcrumbs items={[{ title: "Пользователи" }]} />
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold">Пользователи</h1>
      </div>
      <div className="rounded-md border bg-white">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Id</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Статус</TableHead>
              <TableHead>Роль</TableHead>
              <TableHead>Дата</TableHead>
              <TableHead>Быстрые действия</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {users.map((user: UserDTO) => (
              <TableRow key={user.id}>
                <TableCell className="font-medium">{user.id}</TableCell>
                <TableCell className="font-medium">{user.email}</TableCell>
                <TableCell className="font-medium"><UserStatusBadge type={user.status}/></TableCell>
                <TableCell className="font-medium"><UserRoleBadge type={user.role} /></TableCell>
                <TableCell className="font-medium">
                  {new Date(user.date).toLocaleDateString("ru-RU")}
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>
      <Pagination
        currentPage={currentPage}
        totalPages={result.data.totalPages}
        baseUrl="/admin/users"
      />
    </div>
  );
}
