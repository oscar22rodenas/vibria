const domain = import.meta.env.WP_DOMAIN;
const apiURL = `${domain}/wp-json/wp/v2`;

export const getPageInfo = async (slug) => {
  const response = await fetch(`${apiURL}/pages?slug=${slug}`);
  if (!response.ok) {
    throw new Error("Failed to fetch page info");
  }
  const [data] = await response.json();
  console.log([data]);

  return data.acf;
};

export const getImageUrl = async (imageId) => {
  if (!imageId) return "";

  try {
    const response = await fetch(`${apiURL}/media/${imageId}`);
    if (!response.ok) {
      throw new Error(`No se encontró la imagen con ID ${imageId}`);
    }
    const data = await response.json();
    return { source_url: data.source_url, alt_text: data.alt_text };
    
  } catch (error) {
    console.error("Error obteniendo imagen:", error);
    return "";
  }
};