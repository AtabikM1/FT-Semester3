import { Footer } from "../commons/footer";
import Header from "../commons/header";
import IndexHero from "../components/index/hero";
import { IndexIntroduction } from "../components/index/introduction";

const IndexPage = () => {
  return (
    <>
      <Header />
      <IndexHero />
      <IndexIntroduction />
      <Footer />
    </>
  );
};

export default IndexPage;
