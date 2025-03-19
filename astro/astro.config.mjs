// astro.config.mjs
import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind'; // Importa el plugin correcto
import node from '@astrojs/node';

export default defineConfig({
  output: 'server',
  adapter: node({mode: 'standalone'}),
  integrations: [tailwind()],
});
