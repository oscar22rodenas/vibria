import { apiURL } from "./config.js";
import { getImageInfo } from "./getImageInfo.js";

export const getSlidesInfo = async (lang) => {
  try {
    const response = await fetch(`${apiURL}/slides?order=asc&_fields=acf, slug`);
    if (!response.ok) {
      throw new Error("Error al obtener los slides");
    }
    const data = await response.json();
    
    // Filtrar los slides según el idioma (usando el slug)
    const slidesFiltrados = data.filter(slide => slide.slug.includes(`-${lang}`));
    
    // Obtener las imágenes de cada slide en paralelo
    const slidesConImagenes = await Promise.all(
      slidesFiltrados.map(async (slide) => {
        const imageData = slide.acf.slide_imagen ? await getImageInfo(slide.acf.slide_imagen) : null;
        return {
          title: slide.acf.slide_titulo,
          subtitle: slide.acf.slide_subtitulo,
          text: slide.acf.slide_texto,
          buttonText: slide.acf.slide_boton_texto,
          buttonLink: slide.acf.slide_boton_link,
          imageUrl: imageData?.source_url || "",
          imageAlt: imageData?.alt_text || "Slide image"
        };
      })
    );    

    return slidesConImagenes;
  } catch (error) {
    console.error("Error obteniendo slides:", error);
    return [];
  }
};
