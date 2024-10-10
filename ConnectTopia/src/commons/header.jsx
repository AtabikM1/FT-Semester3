import { useEffect, useRef } from "react";
import { Link } from "react-router-dom";
import gsap from "gsap";

const listMenu = [
  {
    name: "Home",
    link: "/index",
  },
  {
    name: "Contact",
    link: "/contact",
  },
  {
    name: "News",
    link: "/news",
  },
  {
    name: "About",
    link: "/about",
  },
];

const Header = () => {
  const mainRef = useRef(null);
  const textRef = useRef(null);
  const listRef = useRef(null);

  useEffect(() => {
    gsap.set(mainRef.current, {
      y: -40,
      autoAlpha: 0,
    });
    gsap.set(textRef.current.children, {
      y: -40,
      autoAlpha: 0,
    });
    gsap.set(listRef.current.children, {
      y: -40,
      autoAlpha: 0,
    });

    const tl = gsap.timeline({
      defaults: {
        ease: "power2.out",
      },
    });

    tl.to(mainRef.current, {
      duration: 0.8,
      autoAlpha: 1,
      y: 0,
      ease: "power2.out",
    })
      .to(textRef.current.children, {
        duration: 0.4,
        autoAlpha: 1,
        y: 0,
        stagger: 0.2,
        ease: "power2.out",
      })
      .to(listRef.current.children, {
        duration: 0.4,
        autoAlpha: 1,
        y: 0,
        stagger: 0.2,
        ease: "power2.out",
      });
  }, []);

  return (
    <header
      ref={mainRef}
      className="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm"
    >
      <div className="h-16 px-8 mx-auto max-w-7xl">
        <div 
          ref={textRef}
          className="flex items-center justify-between h-full"
        >
          <h1 className="font-semibold">ConnectTopia</h1>
          
          <nav ref={listRef} className="flex items-center gap-8">
            {listMenu.map((menuItem) => (
              <Link
                key={menuItem.name}
                to={menuItem.link}
                className="text-gray-600 hover:text-gray-900 transition duration-300"
              >
                {menuItem.name}
              </Link>
            ))}
          </nav>
          
          <div className="flex items-center gap-7">
            <Link to="/login" className="text-gray-600 hover:text-gray-900 transition duration-300">
              Login
            </Link>
            <Link to="/signup" className="text-gray-600 hover:text-gray-900 transition duration-300">
              Sign Up
            </Link>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;