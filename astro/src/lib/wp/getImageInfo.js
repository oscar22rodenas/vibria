export const getImageInfo = async (imageId) => {
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