import { apiURL } from "./config.js";

export const getPageInfo = async (slug) => {
  const response = await fetch(`${apiURL}/pages?slug=${slug}`);
  if (!response.ok) {
    throw new Error("Failed to fetch page info");
  }
  const [data] = await response.json();

  return data.acf;
};