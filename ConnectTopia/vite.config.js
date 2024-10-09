import { defineConfig } from "vite";
import react from "@vitejs/plugin-react-swc";
import vitejsConfigPaths from "vite-jsconfig-paths";

export default defineConfig({
  plugins: [react(), vitejsConfigPaths()],
  server: {
    port: 5555,
  },
});
