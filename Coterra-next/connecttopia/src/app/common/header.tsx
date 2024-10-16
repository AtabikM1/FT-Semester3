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
  return (
    <header className="fixed top-0 left-0 right-0 z-50  backdrop-blur-sm shadow-2xl text-white">
      <div className="h-16 px-8 mx-auto max-w-7xl">
        <div className="flex items-center justify-between h-full">
          <h1 className="font-bold text-xl hover:scale-105 transition-all duration-500">
            Connect<span className="text-gray-600">Topia</span>
          </h1>

          <nav className="flex items-center gap-8">
            {listMenu.map((menuItem) => (
              <a
                key={menuItem.name}
                className="text-white cursor-pointer hover:scale-110 hover:text-gray-500 transition duration-300"
              >
                {menuItem.name}
              </a>
            ))}
          </nav>

          <div className="flex items-center gap-7">
            <a className="cursor-pointer text-white hover:scale-110 transition duration-300">
              Sign Up
            </a>
            <a className="cursor-pointer border-2 px-5 py-1 rounded-lg text-black bg-white hover:scale-110 hover:bg-transparent hover:text-white transition duration-300">
              Login
            </a>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
