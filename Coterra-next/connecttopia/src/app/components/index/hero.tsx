import React from "react";
import templateImage from "@/public/templatePost.png";
import Image from "next/image";
import { Search, Briefcase, Users, Building2, Trophy } from "lucide-react";

const SUGGESTED_JOBS = [
  "Management",
  "Sales",
  "Digital Marketing",
  "Programming",
  "Design",
];

const STATS = [
  { icon: Users, label: "Active Users", value: "10K+" },
  { icon: Building2, label: "Companies", value: "500+" },
  { icon: Trophy, label: "Success Stories", value: "2K+" },
];

export default function IndexHero() {
  return (
    <section className="relative h-screen bg-gradient-to-br from-sky-950 to-sky-950 overflow-hidden">
      <div className="absolute top-20 left-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl" />
      <div className="absolute bottom-20 right-20 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl" />

      <div className="container mx-auto h-full w-4/5 flex items-center justify-between px-4 md:gap-4">
        <div className="max-w-xl space-y-8 text-white">
          <div className="flex items-center gap-4">
            <div className="p-3 bg-white/10 rounded-xl">
              <Briefcase className="w-8 h-8 text-[#F7D13A]" />
            </div>
            <h1 className="text-5xl font-bold text-[#F7D13A] tracking-tight">
              ConnectTopia
            </h1>
          </div>

          <span className="inline-flex px-3 py-1 text-sm bg-blue-500/20 text-blue-200 rounded-full">
            #1 Job Search Platform
          </span>

          <div className="space-y-6">
            <p className="text-base font-normal leading-relaxed text-gray-200">
              With a user-friendly interface and a vast network of
              professionals, we make job searching and hiring efficient and
              effective. Join us and take the next step towards a brighter
              future!
            </p>

            <div className="flex gap-6">
              {STATS.map(({ icon: Icon, label, value }) => (
                <div key={label} className="space-y-1">
                  <div className="flex items-center gap-2">
                    <Icon className="w-4 h-4 text-[#F7D13A]" />
                    <span className="font-semibold text-lg">{value}</span>
                  </div>
                  <p className="text-xs text-gray-400">{label}</p>
                </div>
              ))}
            </div>

            <div className="space-y-4">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                <input
                  type="text"
                  placeholder="Search job titles..."
                  className="h-12 w-full bg-white rounded-lg pl-10 text-base text-gray-800 placeholder:text-gray-400 outline-none focus:ring-2 focus:ring-blue-500 transition-shadow"
                />
              </div>

              <button className="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                Find Job
              </button>

              <div className="flex flex-wrap items-center gap-2">
                <span className="text-sm font-medium text-gray-300">
                  Suggestions:
                </span>
                <div className="flex flex-wrap gap-2">
                  {SUGGESTED_JOBS.map((job) => (
                    <button
                      key={job}
                      className="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 text-xs text-gray-200 transition-colors"
                    >
                      {job}
                    </button>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="hidden lg:block relative">
          <div className="absolute inset-0 bg-gradient-to-tr from-blue-500/20 to-yellow-500/20 rounded-lg blur" />
          <div className="relative h-[28rem] w-[28rem] overflow-hidden rounded-lg shadow-xl">
            <Image
              src={templateImage}
              alt="Platform preview"
              className="h-full w-full object-cover"
            />
          </div>

          <div className="absolute -top-4 -left-4 p-4 bg-white/5 backdrop-blur-lg rounded-lg">
            <div className="flex items-center gap-3">
              <div className="w-2 h-2 rounded-full bg-green-400" />
              <span className="text-sm text-white">1,234 Users Online</span>
            </div>
          </div>

          <div className="absolute -bottom-4 -right-4 p-4 bg-white/5 backdrop-blur-lg rounded-lg">
            <div className="flex items-center gap-3">
              <div className="w-2 h-2 rounded-full bg-blue-400" />
              <span className="text-sm text-white">500+ New Jobs Today</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
