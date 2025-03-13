import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import svgr from 'vite-plugin-svgr';
import react from '@vitejs/plugin-react';
import eslint from 'vite-plugin-eslint';
import path from 'path';

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.jsx',
      ssr: 'resources/js/ssr.tsx',
      refresh: true
    }),
    react(),
    svgr(),
    eslint({ include: ['resources/js/**/*.jsx', 'resources/js/**/*.tsx'] })
  ],
  resolve: {
    alias: {
      'ziggy-js': path.resolve('vendor/tightenco/ziggy')
    }
  }
});
