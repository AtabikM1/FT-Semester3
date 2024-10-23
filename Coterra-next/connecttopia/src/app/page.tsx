"use client";
import Image from "next/image";
import IndexHero from "./components/index/hero";
import Header from "./common/header";
import IndexIntroduction from "./components/index/introduction";
import Footer from "./common/footer";
import FeaturedJobs from "./components/index/job";
import Benefits from "./components/index/features";
import PlatformPerformanceChart from "./components/index/charts";
import IndexCTA from "./components/index/join-now";

export default function Home() {
  return (
    <>
      <Header />
      <IndexHero /> {/*
      <IndexIntroduction />
      <Benefits />
      <PlatformPerformanceChart />
      <FeaturedJobs />
      <IndexCTA />
      <Footer /> */}
    </>
  );
}
