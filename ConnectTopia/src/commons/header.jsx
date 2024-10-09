import { Link } from "react-router-dom";

const listMenu = [
  {
    name: "Home",
    link: "/index"
  }, 
  {
    name: "Contact",
    link: "/contact"
  }, 
  {
    name: "News",
    link: "/news"
  }, 
  {
    name: "About",
    link: "/about"
  }
];

const Header = () => {
  return (
    <div className="fixed top-0 bg-white w-[100vw] h-12 flex items-center justify-between">
      <h1 className="font-semibold ml-8">ConnectTopia</h1>
      <div className="flex gap-8 mr-8">
        {listMenu.map((menuItem) => {
          return (
            <Link 
              key={menuItem.name} 
              to={menuItem.link} 
              className="text-gray-600 hover:text-gray-900 transition duration-300"
            >
              {menuItem.name}
            </Link>
          );
        })}
      </div>
    </div>
  );
};

export default Header;
