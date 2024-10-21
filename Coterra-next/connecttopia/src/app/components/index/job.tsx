import React from "react";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
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
    <section className="bg-black py-16">
      <div className="container mx-auto px-4">
        <h2 className="text-4xl font-bold text-white mb-8 text-center">Featured Jobs</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {jobListings.map((job, index) => (
            <Card key={index} className="bg-slate-950 text-white border-gray-800">
              <CardHeader>
                <CardTitle className="text-2xl font-bold">{job.title}</CardTitle>
                <p className="text-gray-400">{job.company}</p>
              </CardHeader>
              <CardContent>
                <div className="flex items-center mb-2">
                  <MapPin className="mr-2 h-4 w-4 text-gray-400" />
                  <span className="text-gray-300">{job.location}</span>
                </div>
                <div className="flex items-center mb-2">
                  <DollarSign className="mr-2 h-4 w-4 text-gray-400" />
                  <span className="text-gray-300">{job.salary}</span>
                </div>
                <div className="flex items-center mb-4">
                  <Briefcase className="mr-2 h-4 w-4 text-gray-400" />
                  <span className="text-gray-300">{job.type}</span>
                </div>
                <div className="flex flex-wrap gap-2">
                  {job.tags.map((tag, tagIndex) => (
                    <Badge key={tagIndex} variant="secondary" className="bg-gray-800 text-gray-300">
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