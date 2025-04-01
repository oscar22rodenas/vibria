import { apiURL } from "./config.js";

export const getCategoriesInfo = async (lang) => {
  const response = await fetch(`${apiURL}/menu?page=1&per_page=20&orderby=date&order=asc&_fields=title,acf,slug,id`);
  const menus = await response.json();

  const filteredMenus = menus.filter(menu => menu.slug.includes(`-${lang}`));

  const categoriesMap = {
    parent: [],
    children: {}
  };

  for (const menu of filteredMenus) {
    const categoryTitle = menu.acf?.categoria_titulo;
    const subcategoryIds = menu.acf?.subcategorias || [];

    categoriesMap.parent.push({
      id: menu.id,
      title: categoryTitle,
      slug: menu.slug
    });

    // Realizar todas las solicitudes de subcategorías en paralelo usando Promise.allSettled
    const subcategoriesResults = await Promise.allSettled(
      subcategoryIds.map(async (subId) => {
        try {
          const subResponse = await fetch(`${apiURL}/subcategorias_menu/${subId}?_fields=acf,slug`);
          if (!subResponse.ok) {
            console.warn(`Subcategoría con ID ${subId} no encontrada (status: ${subResponse.status})`);
            return null; // Retornar null si no se encuentra la subcategoría
          }
          const subcategory = await subResponse.json();
          return {
            title: subcategory.acf?.subcategoria_titol || "Sin título",
            slug: subcategory.slug
          };
        } catch (error) {
          console.error(`Error al obtener la subcategoría con ID ${subId}:`, error);
          return null; // Retornar null en caso de error
        }
      })
    );

    // Filtrar subcategorías válidas (que no sean null y que hayan sido resueltas correctamente)
    categoriesMap.children[menu.id] = subcategoriesResults
      .filter(result => result.status === "fulfilled" && result.value !== null)
      .map(result => result.value);
  }

  return categoriesMap;
};