"use client";
import Image from "next/image";
import IndexHero from "./components/index/hero";
import Header from "./common/header";
import IndexIntroduction from "./components/index/introduction";
import Footer from "./common/footer";

export default function Home() {
  return (
    <>
      <Header />
      <IndexHero />
      <IndexIntroduction />
      <Footer />
    </>
  );
}
