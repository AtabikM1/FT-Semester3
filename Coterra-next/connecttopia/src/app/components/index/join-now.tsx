import { Sparkles } from "lucide-react";
import { useEffect, useRef } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const IndexCTA = () => {
  const sectionRef = useRef(null);
  const textRef = useRef(null);
  const buttonRef = useRef(null);

  useEffect(() => {
    const section = sectionRef.current;
    const text = textRef.current;
    const button = buttonRef.current;

    gsap.set(section, { opacity: 0 });
    gsap.set(text, { opacity: 0, y: 50 });
    gsap.set(button, { opacity: 0, y: 50 });

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: section,
        start: "top bottom",
        end: "bottom 90%",
        scrub: 1,
      },
    });

    tl.to(section, {
      opacity: 1,
      duration: 1,
      ease: "power2.out",
    })
      .to(text, {
        opacity: 1,
        y: 0,
        ease: "power2.out",
      })
      .to(button, {
        opacity: 1,
        y: 0,
        ease: "power2.out",
      });

    return () => {
      ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
    };
  }, []);

  return (
    <main
      ref={sectionRef}
      className="flex min-h-screen items-center justify-center bg-gradient-to-b from-black to-gray-950 py-20"
    >
      <section className="mx-auto w-full max-w-3xl px-4">
        <article
          ref={textRef}
          className="relative flex flex-col items-center text-center"
        >
          <h1 className="mb-6 text-4xl font-black leading-tight text-white sm:text-5xl">
            What Are You <span className="text-gray-500">Waiting</span> For?
          </h1>

          <p className="mb-8 max-w-2xl text-lg text-gray-600">
            Join{" "}
            <span className="font-semibold text-gray-500">ConnectTopia</span>{" "}
            today and turn your creative ideas into reality. Our community of
            creators is waiting for you.
          </p>

          <div ref={buttonRef} className="flex flex-wrap justify-center gap-4">
            <a className="transform rounded-full bg-white px-8 py-3 font-bold text-black transition duration-300 ease-in-out hover:scale-105 hover:bg-[#d57260]">
              Start Creating
            </a>
            <a className="transform rounded-full border-2 text-black border-gray-400 bg-white px-8 py-3 font-bold transition duration-300 ease-in-out hover:scale-105 hover:bg-[#e68369] hover:text-white">
              About Us
            </a>
          </div>

          <Sparkles className="absolute -left-4 top-0 h-8 w-8 text-gray-700 opacity-75" />
          <Sparkles className="absolute -right-4 bottom-0 h-8 w-8 text-gray-700 opacity-75" />
        </article>
      </section>
    </main>
  );
};

export default IndexCTA;
