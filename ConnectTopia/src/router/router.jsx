import { BrowserRouter, Routes, Route } from "react-router-dom";
import IndexPage from "../pages";

const Router = () => {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" Component={IndexPage} />
      </Routes>
    </BrowserRouter>
  );
};

export default Router;
