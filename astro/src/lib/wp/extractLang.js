export function extractLang(slug, categoria = "") {
    const match = slug.match(/-(ca|es|en)$/); // Buscar sufijo de idioma
    if (match) {
        const lang = match[1];
        const categoriaSlug = categoria ? `/${categoria}` : ""; // Agregar sufijo de categoría si existe
        const baseSlug = slug.replace(/-(ca|es|en)$/, ""); // Quitar sufijo de idioma
        return { lang, categoriaSlug, baseSlug };
    }
    return null;
}