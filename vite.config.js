import laravel from "vite-plugin-laravel";
import reactJsx from "vite-react-jsx";
import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import svgrPlugin from "vite-plugin-svgr";

export default defineConfig({
  plugins: [
    react(),
    reactJsx(),
    laravel(),
    svgrPlugin({
      svgrOptions: {
        icon: true,
      },
    }),
  ],
});
