import ResetPasswordForm from "@/components/Auth/Password/Confirm/ConfirmForm";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Подтверждение сброса пароля",
  description: "Страница подтверждения сброса пароля",
};
export default function Page() {
  return <ResetPasswordForm />;
}
