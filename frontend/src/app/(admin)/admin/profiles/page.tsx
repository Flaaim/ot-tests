import { fetchUsersPagination } from "@/actions/profile";
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
import { PaginatedProfiles, ProfileDTO } from "@/interfaces/user.interface";
import Pagination from "@/components/Pagination/Pagination";
import UserStatusBadge from "@/components/Admin/Domain/User/UserStatusBadge";
import UserRoleBadge from "@/components/Admin/Domain/User/UserRoleBadge";
import RemoveProfileDialog from "@/components/Admin/Profile/RemoveProfileDialog";

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
  const paginatedProfiles: PaginatedProfiles = result.data;

  const profiles = paginatedProfiles.items;

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
            {profiles.map((profile: ProfileDTO) => (
              <TableRow key={profile.id}>
                <TableCell className="font-medium">{profile.id}</TableCell>
                <TableCell className="font-medium">{profile.email}</TableCell>
                <TableCell className="font-medium">
                  <UserStatusBadge type={profile.status} />
                </TableCell>
                <TableCell className="font-medium">
                  <UserRoleBadge type={profile.role} />
                </TableCell>
                <TableCell className="font-medium">
                  {new Date(profile.date).toLocaleDateString("ru-RU")}
                </TableCell>
                <TableCell>
                  <RemoveProfileDialog id={profile.id} />
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>
      <Pagination
        currentPage={currentPage}
        totalPages={result.data.totalPages}
        baseUrl="/admin/profiles"
      />
    </div>
  );
}
