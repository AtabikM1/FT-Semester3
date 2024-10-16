import React, { useRef } from "react";
import { Twitter } from "lucide-react";
import { useSpring, animated, config } from "@react-spring/web";
import { useInView } from "react-intersection-observer";
import imageTemplate from "@/public/templatePost.png";
import Image from "next/image";

const adminIntro = [
  {
    name: "Luna Hernandez",
    job: "Front End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
  },
];

const reviewList = [
  {
    name: "Diego Hernandez",
    job: "Back End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: imageTemplate,
  },
  {
    name: "Lynx Hernandez",
    job: "Right End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: imageTemplate,
  },
  {
    name: "Kotaro Hernandez",
    job: "Left End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: imageTemplate,
  },
  {
    name: "Pudding Hernandez",
    job: "Above End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: imageTemplate,
  },
  {
    name: "Mango Hernandez",
    job: "Below End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: imageTemplate,
  },
  {
    name: "Evelynn Hernandez",
    job: "Void End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: imageTemplate,
  },
];
const IndexIntroduction: React.FC = () => {
  const [adminRef, adminInView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  });

  const [reviewRef, reviewInView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  });

  const adminSpring = useSpring({
    opacity: adminInView ? 1 : 0,
    transform: adminInView ? "translateY(0px)" : "translateY(30px)",
    config: config.gentle,
  });

  const AdminContent = () => (
    <animated.div style={adminSpring}>
      {adminIntro.map((user, index) => (
        <div key={index} className="text-center">
          <div className="flex items-center justify-center py-10">
            <Image
              src={imageTemplate}
              alt="Gmbr Luna"
              className="w-16 h-16 mr-4 rounded-full object-cover"
            />
            <div>
              <h1 className="text-white font-bold text-3xl">{user.name}</h1>
              <h2 className="font-semibold text-2xl text-gray-200">
                {user.job}
              </h2>
            </div>
          </div>
          <p className="text-2xl max-w-[30rem] text-center mt-4 text-gray-300">
            {user.quotes}
          </p>
        </div>
      ))}
    </animated.div>
  );

  const ReviewCard = ({
    user,
    index,
  }: {
    user: (typeof reviewList)[0];
    index: number;
  }) => {
    const [cardRef, cardInView] = useInView({
      triggerOnce: true,
      threshold: 0.1,
    });

    const cardSpring = useSpring({
      opacity: cardInView ? 1 : 0,
      transform: cardInView ? "translateX(0px)" : "translateX(40px)",
      delay: index * 100,
      config: config.gentle,
    });

    return (
      <animated.div
        ref={cardRef}
        style={cardSpring}
        className="flex-shrink-0 w-[40rem] h-[20rem] bg-slate-950 rounded-lg flex flex-col justify-center items-center space-y-5"
      >
        <div className="flex justify-between w-full px-8 items-center">
          <div className="flex">
            <Image
              src={user.image}
              alt={user.name}
              className="w-12 h-12 object-cover rounded-full"
            />
            <div className="ml-4">
              <h1 className="text-white font-bold text-2xl">{user.name}</h1>
              <h1 className="text-white font-semibold text-xl">{user.job}</h1>
            </div>
          </div>
          <figure>
            <Twitter
              size={42}
              className="text-white outline-2 outline px-2 py-2 rounded-full"
            />
          </figure>
        </div>
        <p className="text-slate-300 w-3/4 text-[1.1rem]">{user.quotes}</p>
      </animated.div>
    );
  };

  return (
    <div className="min-h-screen bg-black flex flex-col items-center justify-center">
      <div
        ref={adminRef}
        className="flex flex-col items-center justify-center flex-grow"
      >
        <AdminContent />
      </div>
      <div className="w-full overflow-x-auto">
        <div ref={reviewRef} className="flex gap-4 py-4">
          {reviewList.map((user, index) => (
            <ReviewCard key={index} user={user} index={index} />
          ))}
        </div>
      </div>
    </div>
  );
};

export default IndexIntroduction;
