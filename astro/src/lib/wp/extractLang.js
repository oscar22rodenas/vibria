export function extractLang(slug) {
    const match = slug.match(/-(ca|es|en)$/); // Buscar sufijo de idioma
    if (match) {
        const lang = match[1];
        const baseSlug = slug.replace(/-(ca|es|en)$/, ""); // Quitar sufijo de idioma
        return { lang, baseSlug };
    }
    return null;
}