import { Twitter } from "lucide-react";
import { useEffect, useRef } from "react";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

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
    image: "/template.jpeg",
  },
  {
    name: "Lynx Hernandez",
    job: "Right End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: "/template.jpeg",
  },
  {
    name: "Kotaro Hernandez",
    job: "Left End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: "/template.jpeg",
  },
  {
    name: "Pudding Hernandez",
    job: "Above End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: "/template.jpeg",
  },
  {
    name: "Mango Hernandez",
    job: "Below End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: "/template.jpeg",
  },
  {
    name: "Evelynn Hernandez",
    job: "Void End Developer",
    quotes:
      "Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis, dicta nisi? Fuga ratione velit ab numquam suscipit, ad officia totam.",
    image: "/template.jpeg",
  },
];

export const IndexIntroduction = () => {
  const containerRef = useRef(null);
  const adminRef = useRef(null);
  const reviewRef = useRef(null);
  const reviewCardsRef = useRef([]);

  const adminImgRef = useRef(null);
  const adminNameRef = useRef(null);
  const adminJobRef = useRef(null);
  const adminQuoteRef = useRef(null);

  useEffect(() => {
    gsap.set(
      [
        adminImgRef.current,
        adminNameRef.current,
        adminJobRef.current,
        adminQuoteRef.current,
      ],
      {
        autoAlpha: 0,
        y: 30,
      }
    );

    gsap.set(reviewCardsRef.current, {
      autoAlpha: 0,
      x: 40,
    });

    const adminTl = gsap.timeline({
      scrollTrigger: {
        trigger: adminRef.current,
        start: "top 80%",
        end: "center center",
        toggleActions: "play none none reverse",
      },
    });

    adminTl
      .to(adminImgRef.current, {
        autoAlpha: 1,
        y: 0,
        duration: 0.6,
        ease: "power2.out",
      })
      .to(
        adminNameRef.current,
        {
          autoAlpha: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
        },
        "-=0.4"
      )
      .to(
        adminJobRef.current,
        {
          autoAlpha: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
        },
        "-=0.4"
      )
      .to(
        adminQuoteRef.current,
        {
          autoAlpha: 1,
          y: 0,
          duration: 0.6,
          ease: "power2.out",
        },
        "-=0.4"
      );

    const reviewTl = gsap.timeline({
      scrollTrigger: {
        trigger: reviewRef.current,
        start: "top 70%",
        end: "bottom 20%",
        toggleActions: "play none none reverse",
      },
    });

    reviewCardsRef.current.forEach((card, index) => {
      reviewTl.to(
        card,
        {
          autoAlpha: 1,
          x: 0,
          duration: 0.5,
          ease: "power2.out",
          delay: index * 0.1,
        },
        index * 0.1
      );
    });

    return () => {
      ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
    };
  }, []);

  return (
    <div
      ref={containerRef}
      className="min-h-screen bg-slate-950 flex flex-col items-center justify-center"
    >
      <div
        ref={adminRef}
        className="flex flex-col items-center justify-center flex-grow"
      >
        {adminIntro.map((user, index) => (
          <div key={index} className="text-center">
            <div className="flex items-center justify-center py-10">
              <img
                ref={adminImgRef}
                src="template.jpeg"
                alt="Gmbr Luna"
                className="w-16 h-16 mr-4 rounded-full object-cover opacity-0"
              />
              <div>
                <h1
                  ref={adminNameRef}
                  className="text-white font-bold text-3xl opacity-0"
                >
                  {user.name}
                </h1>
                <h2
                  ref={adminJobRef}
                  className="font-semibold text-2xl text-gray-200 opacity-0"
                >
                  {user.job}
                </h2>
              </div>
            </div>
            <p
              ref={adminQuoteRef}
              className="text-2xl max-w-[30rem] text-center mt-4 text-gray-300 opacity-0"
            >
              {user.quotes}
            </p>
          </div>
        ))}
      </div>
      <div className="w-full overflow-x-auto">
        <div ref={reviewRef} className="flex gap-4 py-4">
          {reviewList.map((user, index) => (
            <div
              key={index}
              ref={(el) => (reviewCardsRef.current[index] = el)}
              className="flex-shrink-0 w-[40rem] h-[20rem] bg-slate-900 rounded-lg flex flex-col justify-center items-center space-y-5 opacity-0"
            >
              <div className="flex justify-between w-full px-8">
                <div className="flex">
                  <img
                    src={user.image}
                    alt={user.title}
                    className="w-12 h-12 object-cover rounded-full"
                  />
                  <div className="ml-4">
                    <h1 className="text-white font-bold text-2xl">
                      {user.name}
                    </h1>
                    <h1 className="text-white font-semibold text-xl">
                      {user.job}
                    </h1>
                  </div>
                </div>
                <figure>
                  <Twitter
                    size={42}
                    className="text-white outline-2 outline px-2 py-2 rounded-full"
                  />
                </figure>
              </div>
              <p className="text-slate-300 w-3/4 text-[1.1rem]">
                {user.quotes}
              </p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};
