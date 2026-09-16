import JoinByEmail from "@/components/Auth/JoinByEmail/Request/JoinByEmail";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Регистрация на сайте",
  description: "Страница регистрации на сайте",
};
export default function Page() {
  return <JoinByEmail />;
}
