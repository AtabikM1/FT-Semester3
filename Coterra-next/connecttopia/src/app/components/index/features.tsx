import React from "react";
import { useSpring, animated, config } from "@react-spring/web";
import { useInView } from "react-intersection-observer";
import { Search, Users, TrendingUp, Award } from "lucide-react";
import backgroundTemplate from "@/public/template2.jpg";
import Image from "next/image";

const benefits = [
  {
    title: "Extensive Job Listings",
    description:
      "Access thousands of job opportunities across various industries and locations.",
    icon: Search,
    color: "#FF6B6B",
  },
  {
    title: "Networking Opportunities",
    description: "Connect with professionals and expand your career network.",
    icon: Users,
    color: "#4ECDC4",
  },
  {
    title: "Career Growth Tools",
    description:
      "Utilize our resources to enhance your skills and advance your career.",
    icon: TrendingUp,
    color: "#45B7D1",
  },
  {
    title: "Premium Job Matches",
    description:
      "Get personalized job recommendations based on your skills and preferences.",
    icon: Award,
    color: "#FFA07A",
  },
];

const BenefitCard = ({ benefit, index }) => {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  });

  const cardSpring = useSpring({
    opacity: inView ? 1 : 0,
    transform: inView ? "translateY(0px)" : "translateY(50px)",
    delay: index * 150,
    config: config.gentle,
  });

  const iconSpring = useSpring({
    from: { transform: "scale(0.5) rotate(-45deg)" },
    to: { transform: "scale(1) rotate(0deg)" },
    delay: index * 150 + 300,
    config: config.wobbly,
  });

  return (
    <animated.div ref={ref} style={cardSpring} className="mb-8">
      <div className="flex items-start space-x-4">
        <animated.div
          style={iconSpring}
          className={`p-3 rounded-full`}
          style={{ backgroundColor: benefit.color }}
        >
          <benefit.icon size={24} className="text-white" />
        </animated.div>
        <div>
          <h3 className="text-2xl font-bold text-white mb-2">
            {benefit.title}
          </h3>
          <p className="text-gray-300">{benefit.description}</p>
        </div>
      </div>
    </animated.div>
  );
};

const Benefits = () => {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  });

  const headerSpring = useSpring({
    opacity: inView ? 1 : 0,
    transform: inView ? "translateY(0px)" : "translateY(30px)",
    config: config.gentle,
  });

  return (
    <section className="relative bg-gradient-to-b from-black to-slate-900 py-20">
      <Image
        src={backgroundTemplate}
        alt="background"
        layout="fill"
        objectFit="cover"
        quality={100}
      />
      <div className="absolute inset-0 bg-black opacity-70"></div>
      <div className="relative container mx-auto px-4">
        <animated.div ref={ref} style={headerSpring} className={"relative"}>
          <h2 className="text-5xl font-bold text-white mb-12 text-center">
            Unlock Your Career Potential
          </h2>
        </animated.div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 max-w-5xl mx-auto">
          {benefits.map((benefit, index) => (
            <BenefitCard key={index} benefit={benefit} index={index} />
          ))}
        </div>
      </div>
    </section>
  );
};

export default Benefits;
