import { apiURL } from "./config.js";
import { extractLang } from "./extractLang.js";

export const getPageById = async (id) => {
  try {
    const response = await fetch(`${apiURL}/pages/${id}`);
    if (!response.ok) {
      throw new Error("Failed to fetch page info");
    }
    const data = await response.json();

    const extracted = extractLang(data.slug, data.acf.categoria); 
    
    return extracted;

  } catch (error) {
    console.error("Error fetching page info:", error);
    return null; // Retornar null en caso de error
  }
};
