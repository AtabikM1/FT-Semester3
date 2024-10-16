import { Heart } from "lucide-react";
import backgroundImage from "@/public/template1.jpg";
import Image from "next/image";

export default function Footer(): JSX.Element {
  return (
    <div className="relative min-h-40 flex items-center justify-around">
      <Image
        src={backgroundImage}
        alt="Background"
        layout="fill"
        objectFit="cover"
        quality={100}
      />
      <div className="absolute bg-black inset-0 opacity-80 backdrop-blur-sm "></div>
      <div className="relative inline-0 text-white">
        <h2>ConnectTopia</h2>
      </div>
      <div className="relative text-white">
        <ul className="flex gap-4">
          <li>Home</li>
          <li>Blog</li>
          <li>News</li>
          <li>Contact</li>
        </ul>
      </div>
      <div className="flex gap-2 relative text-white">
        <Heart size={20} className="mt-1" />
        <h2>Made with Love</h2>
      </div>
    </div>
  );
}
