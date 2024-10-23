import React from "react";
import templateImage from "@/public/templatePost.png";
import Image from "next/image";
import { Search } from "lucide-react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";

const SUGGESTED_JOBS = [
  "Management",
  "Sales",
  "Digital Marketing",
  "Programming",
  "Design",
];

export default function IndexHero() {
  return (
    <section className="h-screen bg-gradient-to-br from-sky-950 to-sky-900">
      <div className="container mx-auto h-full w-4/5 flex items-center justify-between px-4">
        <div className="max-w-xl space-y-8 text-white">
          <h1 className="text-5xl font-bold text-[#F7D13A] tracking-tight">
            ConnectTopia
          </h1>
          <div className="space-y-6">
            <p className="text-base font-normal leading-relaxed text-gray-200">
              With a user-friendly interface and a vast network of
              professionals, we make job searching and hiring efficient and
              effective. Join us and take the next step towards a brighter
              future!
            </p>
            <div className="space-y-4">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                <Input
                  type="text"
                  placeholder="Search job titles..."
                  className="h-12 w-full bg-white rounded-lg pl-10 text-base text-gray-800 placeholder:text-gray-400"
                />
              </div>
              <Button
                size="lg"
                className="w-full bg-blue-600 text-base font-medium hover:bg-blue-700"
              >
                Find Job
              </Button>
              <div className="flex flex-wrap items-center gap-2">
                <span className="text-sm font-medium text-gray-300">
                  Suggestions:
                </span>
                <div className="flex flex-wrap gap-2">
                  {SUGGESTED_JOBS.map((job) => (
                    <Button
                      key={job}
                      variant="ghost"
                      size="sm"
                      className="rounded-full bg-white/10 text-xs text-gray-200 hover:bg-white/20"
                    >
                      {job}
                    </Button>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="hidden lg:block">
          <div className="relative h-96 w-96 overflow-hidden rounded-lg shadow-xl">
            <Image
              src={templateImage}
              alt="Platform preview"
              className="h-full w-full object-cover"
            />
          </div>
        </div>
      </div>
    </section>
  );
}