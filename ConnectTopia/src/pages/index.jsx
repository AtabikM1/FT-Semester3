import { useRef } from "react";
import { Footer } from "../commons/footer";
import Header from "../commons/header";
import IndexHero from "../components/index/hero";
import { IndexIntroduction } from "../components/index/introduction";

const IndexPage = () => {
  const reviewRef = useRef(null);
  const scrollToReview = () => {
    reviewRef.current?.scrollToReview({ behavior: "smooth" });
  };
  return (
    <>
      <Header />
      <IndexHero />
      <div ref={reviewRef}>
        <IndexIntroduction />
      </div>
      <Footer />
    </>
  );
};

export default IndexPage;
