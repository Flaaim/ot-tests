import { fetchProfile } from "@/actions/profile";
import { redirect } from "next/navigation";
import ChangePasswordForm from "@/components/Auth/Password/Change/ChangePasswordForm";

export default async function ChangePasswordPage() {
  let profile;
  try {
    profile = await fetchProfile();
  } catch (error) {
    console.error("Ошибка авторизации в лейауте, перенаправление...", error);
    redirect("/join/login");
  }
  return <ChangePasswordForm profile={profile} />;
}
