import { Sparkles } from "lucide-react";
import { useEffect, useRef } from "react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Button } from "@/components/ui/button";

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
      className="flex min-h-screen items-center justify-center bg-gradient-to-br from-black to-black py-20"
    >
      <section className="mx-auto w-full max-w-3xl px-4">
        <article
          ref={textRef}
          className="relative flex flex-col items-center text-center"
        >
          <h1 className="mb-6 text-4xl font-black leading-tight text-white sm:text-5xl">
            Ready to Advance Your <span className="text-slate-500">Career</span>
            ?
          </h1>
          <p className="mb-8 max-w-2xl text-lg text-white/90">
            Join <span className="font-semibold text-slate-500">thousands</span>{" "}
            of professionals who've already taken the next step in their career
            journey. Your dream job is waiting.
          </p>
          <div ref={buttonRef} className="flex flex-wrap justify-center gap-4">
            <Button
              variant="secondary"
              size="lg"
              className="transform transition duration-300 ease-in-out hover:scale-105"
            >
              Create Profile
            </Button>
            <Button
              variant="outline"
              size="lg"
              className="transform border-2 border-white bg-transparent text-white transition duration-300 ease-in-out hover:scale-105 hover:bg-white hover:text-blue-500"
            >
              Browse Jobs
            </Button>
          </div>
          <Sparkles className="absolute -left-4 top-0 h-8 w-8 text-white opacity-75" />
          <Sparkles className="absolute -right-4 bottom-0 h-8 w-8 text-white opacity-75" />
        </article>
      </section>
    </main>
  );
};

export default IndexCTA;
