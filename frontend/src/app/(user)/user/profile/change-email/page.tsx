import { fetchProfile } from "@/actions/profile";
import { redirect } from "next/navigation";
import RequestChangeEmail from "@/components/Auth/Email/ChangeEmailForm";

export default async function changeEmailPage() {
  const result = await fetchProfile();

  if (!result.ok || !result.data) {
    redirect("/join/login");
  }

  return <RequestChangeEmail />;
}
