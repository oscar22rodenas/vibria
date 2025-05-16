import { apiURL } from "./config.js";
import { getImageInfo } from "./getImageInfo.js";
import { getPageById } from "./getPageById.js";

export const getIntercanvisJuvenilInfo = async (lang) => {
  try {
    const response = await fetch(`${apiURL}/experiencies_erasmus?order=asc&_fields=acf,slug`);
    if (!response.ok) {
      throw new Error("Error al obtener las experiencies");
    }
    const data = await response.json();
    const responsePage = await fetch(`${apiURL}/pages?slug=intercanvis-juvenil-${lang}&_fields=content`);
    if (!responsePage.ok) {
      throw new Error("Error al obtener la página");
    }
    const [pageDataInfo] = await responsePage.json();

    // Filtrar los ofertes según el idioma (usando el slug)
    const experienciaFiltrados = data.filter(experiencia => experiencia.slug.includes(`-${lang}`)).slice(-2);

    // Obtener las imágenes de cada oferta en paralelo
    const experienciaConDatos = await Promise.all(

      experienciaFiltrados.map(async (experiencia) => {
        const { acf } = experiencia;
        // Validar que `acf` exista antes de acceder a sus propiedades
        if (!acf) {
          console.warn(`Oferta sin datos ACF: ${experiencia.slug}`);
          return null;
        }

        const imageData = acf.experiencia_imagen ? await getImageInfo(acf.experiencia_imagen) : null;

        const pageData = acf.experiencia_link ? await getPageById(acf.experiencia_link) : null;

        return {
          title: acf.experiencia_titulo,
          text: acf.experiencia_texto,
          ubicacion: acf.experiencia_ubicacion,
          fechas: acf.experiencia_fechas,
          dataLimit: acf.experiencia_data_limit,
          link: pageData ? `/${pageData.lang}${pageData.categoriaSlug}/${pageData.baseSlug}` : "#",
          imageUrl: imageData?.source_url || "",
          imageAlt: imageData?.alt_text || "oferta image",
          content: pageDataInfo.content.rendered || "",
        };
      })
    );    

    return experienciaConDatos;
  } catch (error) {
    console.error("Error obteniendo experiencias:", error);
    return [];
  }
};
