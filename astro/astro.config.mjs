// astro.config.mjs
import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind'; // Importa el plugin correcto
import node from '@astrojs/node';

export default defineConfig({
  adapter: node({mode: 'standalone'}),
  integrations: [tailwind()],
  image: {
    domains: ["localhost"], // Si usas un dominio real, cámbialo aquí
    remotePatterns: [{ protocol: "http" }, { protocol: "https" }]
  }
});
