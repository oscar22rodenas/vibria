import { apiURL } from "./config.js";

export const getSlideInfo = async (slug) => {
  const response = await fetch(`${apiURL}/slides?slug=${slug}`);
  if (!response.ok) {
    throw new Error("Failed to fetch slide info");
  }
  const [data] = await response.json();
  return data.acf;
};
