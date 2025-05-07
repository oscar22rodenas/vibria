import { apiURL } from "./config.js";

export const getQuiSomInfo = async (lang, slug) => {
  try {
    const response = await fetch(`${apiURL}/qui-som?order=asc&_fields=content,slug&per_page=100`);
    
    if (!response.ok) throw new Error("Error al obtener los contenidos de 'qui-som'");
    
    const data = await response.json();

    const responsePage = await fetch(`${apiURL}/pages?slug=${slug}&_fields=content`);
    
    if (!responsePage.ok) throw new Error("Error al obtener la página");
    
    const [pageDataInfo] = await responsePage.json();

    const quiSomFiltrados = data.filter(item => item.slug?.includes(`-${lang}`));

    const quiSomConDatos = await Promise.all(
      quiSomFiltrados.map(async (item) => {
        const { content } = item;
        if (!content?.rendered) {
          console.warn(`Contenido sin datos en: ${item.slug}`);
          return null;
        }

        return {
          content: content.rendered,
          pageContent: pageDataInfo.content.rendered || "",
        };
      })
    );
  return quiSomConDatos;
  } 
  catch (error) {
    console.error("Error en getQuiSomInfo:", error);
  }
};