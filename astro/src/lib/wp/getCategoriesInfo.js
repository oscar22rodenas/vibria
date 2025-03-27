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
    
    categoriesMap.children[menu.id] = [];
    
    for (const subId of subcategoryIds) {
      const subResponse = await fetch(`${apiURL}/subcategorias_menu/${subId}?_fields=acf,slug`);
      const subcategory = await subResponse.json();
      
      categoriesMap.children[menu.id].push({
        title: subcategory.acf.subcategoria_titol,
        slug: subcategory.slug
      });
    }
  }

  return categoriesMap;
};
