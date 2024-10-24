import React from "react";
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import templateImage from "@/public/templatePost.png";
import Image from "next/image";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Briefcase, MapPin, DollarSign } from "lucide-react";

const jobListings = [
  {
    title: "Senior Frontend Developer",
    company: "TechCorp Inc.",
    location: "San Francisco, CA",
    salary: "$120,000 - $160,000",
    type: "Full-time",
    tags: ["React", "TypeScript", "Node.js"],
  },
  {
    title: "UX/UI Designer",
    company: "DesignHub",
    location: "New York, NY",
    salary: "$90,000 - $120,000",
    type: "Full-time",
    tags: ["Figma", "Adobe XD", "User Research"],
  },
  {
    title: "Data Scientist",
    company: "DataDriven Co.",
    location: "Remote",
    salary: "$100,000 - $140,000",
    type: "Full-time",
    tags: ["Python", "Machine Learning", "SQL"],
  },
];

const FeaturedJobs = () => {
  return (
    <section className="bg-[#1C2056] py-8">
      <div className="container mx-auto px-4 w-[90vw]">
        <div className="flex items-center justify-between py-8">
          <p className="max-w-xl text-white text-xl italic">
            Temukan peluang karier terbaik yang telah kami pilih khusus untukmu!
            Jangan lewatkan kesempatan untuk melangkah lebih dekat menuju
            impianmu dengan posisi-posisi bergengsi yang sedang menunggu talenta
            hebat seperti dirimu. Dapatkan pekerjaan impianmu hari ini!
          </p>
          <h2 className="text-5xl max-w-xl font-bold text-white mb-8 text-center">
            Must-See{" "}
            <span className="text-yellow-400">Career Opportunities</span>
          </h2>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {jobListings.map((job, index) => (
            <Card
              key={index}
              className="bg-white text-black border-gray-800 shadow-md"
            >
              <CardHeader>
                <div className="relative">
                  <Image
                    src={templateImage}
                    alt=""
                    className="relative rounded-lg"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent rounded-lg"></div>
                  <div className="absolute bottom-4 left-4 text-white">
                    <CardTitle className="text-2xl font-bold">
                      {job.title}
                    </CardTitle>
                    <p className="text-gray-400">{job.company}</p>
                  </div>
                </div>
              </CardHeader>
              <CardContent>
                <div className="flex items-center mb-2">
                  <MapPin className="mr-2 h-4 w-4 text-gray-400" />
                  <span className="text-gray-600">{job.location}</span>
                </div>
                <div className="flex items-center mb-2">
                  <DollarSign className="mr-2 h-4 w-4 text-gray-400" />
                  <span className="text-gray-600">{job.salary}</span>
                </div>
                <div className="flex items-center mb-4">
                  <Briefcase className="mr-2 h-4 w-4 text-gray-400" />
                  <span className="text-gray-600">{job.type}</span>
                </div>
                <div className="flex flex-wrap gap-2">
                  {job.tags.map((tag, tagIndex) => (
                    <Badge
                      key={tagIndex}
                      variant="secondary"
                      className="bg-gray-800 text-gray-300"
                    >
                      {tag}
                    </Badge>
                  ))}
                </div>
              </CardContent>
              <CardFooter>
                <Button className="w-full bg-gray-800 hover:bg-gray-700 text-white">
                  Apply Now
                </Button>
              </CardFooter>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
};

export default FeaturedJobs;
