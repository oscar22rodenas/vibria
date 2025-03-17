// astro.config.mjs
import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind'; // Importa el plugin correcto

export default defineConfig({
  integrations: [tailwind()],
});
