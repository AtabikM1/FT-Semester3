import { ArrowRight, Menu, X } from "lucide-react";
import { useState } from "react";

const listMenu = [
  {
    name: "Home",
    a: "/index",
  },
  {
    name: "Contact",
    a: "/contact",
  },
  {
    name: "News",
    a: "/news",
  },
  {
    name: "About",
    a: "/about",
  },
];

const Header = () => {
  const [isActive, setIsActive] = useState(false);

  const handleClick = () => {
    setIsActive(!isActive);
  };

  return (
    <header className="fixed top-0 left-0 right-0 z-50 bg-[#1C2056] shadow-lg text-white backdrop-blur-md">
      <div className="h-24 px-6 md:px-8 mx-auto max-w-7xl">
        <div className="flex items-center justify-between h-full">
          <h1 className="font-bold text-2xl md:text-3xl text-[#F7D13A] hover:scale-105 transition-all duration-500 tracking-tight">
            PolinemaCarrier
          </h1>

          <nav className="hidden md:flex items-center gap-10">
            {listMenu.map((menuItem) => (
              <a
                key={menuItem.name}
                className="text-gray-100 font-medium tracking-wide cursor-pointer hover:text-[#F7D13A] hover:scale-105 transition duration-300"
              >
                {menuItem.name}
              </a>
            ))}
          </nav>

          <div className="hidden md:flex items-center gap-8 font-semibold">
            <a className="cursor-pointer text-gray-100 font-medium hover:text-[#F7D13A] hover:scale-105 transition duration-300">
              Login
            </a>
            <a className="cursor-pointer px-8 py-3 flex items-center gap-2 rounded-full text-gray-900  bg-[#F7D13A] hover:scale-105 hover:bg-[#F7D13A]/90 transition duration-300">
              Register Now <ArrowRight className="w-5 h-5" />
            </a>
          </div>

          <div className="flex md:hidden">
            <button
              onClick={handleClick}
              className="text-gray-100 hover:text-[#F7D13A] transition duration-300"
            >
              <Menu className="w-7 h-7" />
            </button>
          </div>

          <div
            className={`fixed h-screen inset-y-0 right-0 w-full max-w-sm bg-white transform transition-transform duration-300 ease-in-out ${
              isActive ? "translate-x-0" : "translate-x-full"
            }`}
          >
            <div className="h-full flex flex-col p-8">
              <div className="flex justify-between items-center">
                <h2 className="text-xl font-bold text-gray-900">Menu</h2>
                <button
                  onClick={handleClick}
                  className="text-gray-500 hover:text-gray-900 transition duration-300"
                >
                  <X className="w-6 h-6" />
                </button>
              </div>

              <div className="mt-8">
                <div className="h-px bg-gray-200" />
                <nav className="mt-8 flex flex-col space-y-6">
                  {listMenu.map((menuItem) => (
                    <a
                      key={menuItem.name}
                      className="text-lg font-medium text-gray-900 hover:text-[#F7D13A] transition duration-300"
                    >
                      {menuItem.name}
                    </a>
                  ))}
                </nav>
              </div>

              <div className="mt-auto space-y-6">
                <a className="block text-center text-lg font-medium text-gray-900 hover:text-[#F7D13A] transition duration-300">
                  Login
                </a>
                <a className="block text-center px-8 py-3 rounded-full text-gray-900 font-semibold bg-[#F7D13A] hover:bg-[#F7D13A]/90 transition duration-300">
                  Register Now
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
