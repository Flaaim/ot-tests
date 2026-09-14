import { redirect } from "next/navigation";
import ChangePasswordForm from "@/components/Auth/Password/Change/ChangePasswordForm";
import { fetchProfile } from "@/actions/profile";
import { ProfileDTO } from "@/interfaces/user.interface";

export default async function ChangePasswordPage() {
  const result = await fetchProfile();

  if (!result.ok || !result.data) {
    redirect("/join/login");
  }

  const profile: ProfileDTO = result.data;

  return <ChangePasswordForm email={profile.email} />;
}
