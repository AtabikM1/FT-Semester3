import React from "react";
import { Search } from "lucide-react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import Image from "next/image";
import templateImage from "@/public/template1.jpg";

export default function IndexHero(): JSX.Element {
  return (
    <div className="relative h-screen flex items-center justify-center">
      <Image
        src={templateImage}
        alt="Background"
        layout="fill"
        objectFit="cover"
        quality={100}
      />
      <div className="absolute inset-0 bg-black opacity-70"></div>
      <div className="relative z-10 max-w-4xl mx-auto text-center text-white p-6">
        <h1 className="text-5xl font-bold mb-4">
          Find Your <span className="text-gray-500">Dream Career</span>
        </h1>
        <p className="text-xl mb-8 text-gray-300">
          Connect with professionals, discover opportunities, and take the next
          step <br />
          in your career journey.
        </p>
        <div className="flex items-center justify-center space-x-2">
          <Input
            type="text"
            placeholder="Search for jobs, skills, or companies"
            className="w-full max-w-lg bg-white text-gray-800"
          />
          <Button variant="secondary" size="icon">
            <Search className="h-4 w-4" />
          </Button>
        </div>
        <div className="mt-12 flex justify-center space-x-4 text-black">
          <Button variant="secondary" size="lg">
            Find Jobs
          </Button>
          <Button variant="outline" size="lg">
            Build Your Network
          </Button>
        </div>
      </div>
    </div>
  );
}
