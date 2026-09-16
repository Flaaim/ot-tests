import RequestResetPassword from "@/components/Auth/Password/ResetPasswordForm";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Сброс пароля",
  description: "Страница сброса пароля",
};
export default function Page() {
  return <RequestResetPassword />;
}
