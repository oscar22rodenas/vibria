import { apiURL } from "./config.js";

export const getPostsInfo = async (slug, lang) => {
  try {    
    const response = await fetch(`${apiURL}/posts?slug=${slug}-${lang}&_fields=acf`);
    if (!response.ok) {
      throw new Error("Failed to fetch page info");
    }
    const [data] = await response.json();

    return data.acf;
  } catch (error) {
    console.error("Error fetching page info:", error);
    return null; // Retornar null en caso de error
  }
};
