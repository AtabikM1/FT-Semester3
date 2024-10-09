import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import Router from "./router/router.jsx";
import "./style.scss";

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <Router />
  </StrictMode>
);
