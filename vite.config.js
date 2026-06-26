import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path';

const base = process.env.VITE_BASE || '/CSDTTU/assets/dist/';

export default defineConfig({
  base,
  plugins: [tailwindcss()],
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: resolve(__dirname, 'resources/js/app.js'),
    },
  },
  server: {
    cors: true,
    strictPort: true,
    port: 5173,
  },
});
