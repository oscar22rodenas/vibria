import { apiURL } from "./config.js";

export const getAcollidaInfo = async (slug) => {
  try {    
    const responsePage = await fetch(`${apiURL}/pages?slug=${slug}&_fields=content`);
    if (!responsePage.ok) {
      throw new Error("Error al obtener la página");
    }
    const [pageData] = await responsePage.json();
        return {
          content: pageData.content.rendered || "",
        };
  } catch (error) {
    console.error("Error obteniendo acollida:", error);
    return [];
  }
};
