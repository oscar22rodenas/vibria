const domain = import.meta.env.WP_DOMAIN;
const apiURL = `${domain}/wp-json/wp/v2`;

export const getPageInfo = async (slug) => {
  const response = await fetch(`${apiURL}/pages?slug=${slug}`);
  if (!response.ok) {
    throw new Error("Failed to fetch page info");
  }
  const [data] = await response.json();

  const { title: { rendered: title }, content: { rendered: content} } = data;
  return { title, content };
};