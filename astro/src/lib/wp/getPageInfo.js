import { apiURL } from "./config.js";

export const getPageInfo = async (slug) => {
  try {
    const response = await fetch(`${apiURL}/pages?slug=${slug}&_fields=acf`);
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
