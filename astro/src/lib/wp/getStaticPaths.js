export async function getStaticPaths() {
  const languages = ["ca", "es", "en"]; // Idiomas soportados

  // Fetch de páginas de WordPress
  const pagesRes = await fetch("http://localhost:8000/wp-json/wp/v2/pages");
  const pages = await pagesRes.json();

  // Fetch de categorías del menú
  const categoriesRes = await fetch(
    "http://localhost:8000/wp-json/wp/v2/menu_categories"
  );
  const categories = await categoriesRes.json();

  // Fetch de subcategorías del menú
  const subcategoriesRes = await fetch(
    "http://localhost:8000/wp-json/wp/v2/menu_subcategories"
  );
  const subcategories = await subcategoriesRes.json();

  let paths = [];

  // Función para extraer idioma del slug (Ej: "home-es" → { lang: "es", baseSlug: "home" })
  function extractLang(slug) {
    const match = slug.match(/-(ca|es|en)$/); // Buscar sufijo de idioma
    if (match) {
      const lang = match[1];
      const baseSlug = slug.replace(/-(ca|es|en)$/, ""); // Quitar sufijo de idioma
      return { lang, baseSlug };
    }
    return null;
  }

  // 🔹 Procesar páginas
  pages.forEach((page) => {
    const extracted = extractLang(page.slug);
    if (extracted && languages.includes(extracted.lang)) {
      paths.push({
        params: { lang: extracted.lang, slug: extracted.baseSlug },
        props: { id: page.id, acf: page.acf, slug: extracted.baseSlug },
      });
    }
  });

  // 🔹 Procesar categorías del menú
  categories.forEach((category) => {
    const extracted = extractLang(category.slug);
    if (extracted && languages.includes(extracted.lang)) {
      paths.push({
        params: { lang: extracted.lang, slug: extracted.baseSlug },
        props: { id: category.id, acf: category.acf, slug: extracted.baseSlug },
      });
    }
  });

  // 🔹 Procesar subcategorías (solo si tienen enlace a una página)
  subcategories.forEach((subcategory) => {
    if (subcategory.acf.enlace_a_pagina) {
      const extracted = extractLang(subcategory.acf.enlace_a_pagina.post_name);
      if (extracted && languages.includes(extracted.lang)) {
        paths.push({
          params: { lang: extracted.lang, slug: extracted.baseSlug },
          props: {
            id: subcategory.id,
            acf: subcategory.acf,
            slug: extracted.baseSlug,
          },
        });
      }
    }
  });

  return { paths };
}
