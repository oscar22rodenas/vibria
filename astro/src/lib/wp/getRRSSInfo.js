import { apiURL } from "./config.js";
import { getImageInfo } from "./getImageInfo.js";

export const getRRSSInfo = async () => {
  try {
    const response = await fetch(`${apiURL}/redes_sociales?order=asc`);
    if (!response.ok) {
      throw new Error("Error al obtener las redes sociales");
    }

    const data = await response.json();
    
    // Obtener las imágenes de cada slide en paralelo
    const RRSSConImagenes = await Promise.all(
      data.map(async (red_social) => {
        const imageData = red_social.acf.rrss_imagen ? await getImageInfo(red_social.acf.rrss_imagen) : null;
        return {
          link: red_social.acf.rrss_link,
          imageUrl: imageData?.source_url || "",
          imageAlt: imageData?.alt_text || "RRSS image"
        };
      })
    );    

    return RRSSConImagenes;
  } catch (error) {
    console.error("Error obteniendo redes sociales:", error);
    return [];
  }
};
