import { fetchProfile } from "@/actions/profile";
import { redirect } from "next/navigation";
import RequestChangeEmail from "@/components/Auth/Email/ChangeEmailForm";

export default async function changeEmailPage() {
  try {
    await fetchProfile();
  } catch (error) {
    console.error("Ошибка авторизации в лейауте, перенаправление...", error);
    redirect("/join/login");
  }
  return <RequestChangeEmail />;
}
