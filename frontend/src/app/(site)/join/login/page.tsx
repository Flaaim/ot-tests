import Login from "@/components/Auth/Login/Login";

import { Metadata } from "next";
export const metadata: Metadata = {
  title: "Вход на сайт",
  description: "Страница входа на сайт",
};
export default function Page() {
  return <Login />;
}
