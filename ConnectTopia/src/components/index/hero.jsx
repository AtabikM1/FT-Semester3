import { useEffect, useRef } from "react";
import gsap from "gsap";

const IndexHero = ( {onGetStarted} ) => {
  const MainRef = useRef(null);
  const textRef = useRef(null);
  const imageRef = useRef(null);

  useEffect(() => {
    const tl = gsap.timeline({
      defaults: {
        ease: "power2.out",
      },
    });

    gsap.set(textRef.current.children, {
      autoAlpha: 0,
      x: -20,
    });
    gsap.set(imageRef.current, {
      autoAlpha: 0,
      x: 20,
    });

    tl.to(textRef.current.children, {
      autoAlpha: 1,
      duration: 1,
      x: 0,
      stagger: 0.8,
      ease: "power2.out",
    }).to(imageRef.current, {
      autAlpha: 1,
      duration: 1,
      x: 0,
      ease: "power2.out",
    });
  });
  return (
    <div className="min-h-screen bg-slate-950 flex justify-around items-center">
      <div ref={textRef} className="">
        <h1 className="mb-2 text-6xl font-bold text-white">
          Polinema<span className="text-neutral-500">Carrier</span>
        </h1>
        <p className="text-3xl font-thin text-gray-300">
          Connecting Talent, Shaping Futures.
        </p>
        <button 
        onClick={onGetStarted}
        className="w-40 text-white mt-6 px-4 py-2 shadow-2xl outline outline-2 outline-white rounded-xl hover:bg-white hover:text-black">
          Learn more
        </button>
      </div>
      <div className="">
        <img
          src="template.jpeg"
  
          className="h-[30rem] w-[30rem] object-cover shadow-2xl rounded-md"
        />
      </div>
    </div>
  );
};

export default IndexHero;
