import ConfirmEmail from "@/components/Auth/JoinByEmail/Confirm/ConfirmEmail";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Подтверждение email",
  description: "Страница подтверждения email",
};
export default function Page() {
  return <ConfirmEmail />;
}
