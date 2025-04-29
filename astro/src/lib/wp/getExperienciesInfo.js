import { apiURL } from "./config.js";
import { getImageInfo } from "./getImageInfo.js";
import { getPageById } from "./getPageById.js";

export const getExperienciesInfo = async (lang) => {
  try {
    const response = await fetch(`${apiURL}/experiencies-ve?order=asc&_fields=acf,slug`);
    if (!response.ok) {
      throw new Error("Error al obtener los experiencies");
    }
    const data = await response.json();
    
    // Filtrar los experiencies según el idioma (usando el slug)
    const experienciesFiltrados = data.filter(experiencia => experiencia.slug.includes(`-${lang}`));
    
    // Obtener las imágenes de cada experiencia en paralelo
    const experienciesConDatos = await Promise.all(

      experienciesFiltrados.map(async (experiencia) => {
        const { acf } = experiencia;
        // Validar que `acf` exista antes de acceder a sus propiedades
        if (!acf) {
          console.warn(`experiencia sin datos ACF: ${experiencia.slug}`);
          return null;
        }

        const imageData = acf.experiencia_imagen ? await getImageInfo(acf.experiencia_imagen) : null;

        const pageData = acf.experiencia_link ? await getPageById(acf.experiencia_link) : null;

        return {
          title: acf.experiencia_titulo,
          text: acf.experiencia_texto,
          link: pageData ? `/${pageData.lang}${pageData.categoriaSlug}/${pageData.baseSlug}` : "#",
          imageUrl: imageData?.source_url || "",
          imageAlt: imageData?.alt_text || "experiencia image",
          pinVoluntariat: acf.experiencia_pin_voluntariat,
          pinPais: acf.experiencia_pin_pais
        };
      })
    );    

    return experienciesConDatos;
  } catch (error) {
    console.error("Error obteniendo experiencies:", error);
    return [];
  }
};
