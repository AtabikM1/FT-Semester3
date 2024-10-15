import { Heart } from "lucide-react";

export const Footer = () => {
  return (
    <div className="min-h-40 flex items-center justify-around">
      <div>
        <h2>ConnectTopia</h2>
      </div>
      <div>
        <ul className="flex gap-4">
            <li>Home</li>
            <li>Blog</li>
            <li>News</li>
            <li>Contact</li>
        </ul>
      </div>
      <div className="flex gap-2">
        <Heart size={20} className="mt-1"/>
        <h2>Made with Love</h2>
      </div>
    </div>
  );
};
