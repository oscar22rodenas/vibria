export async function getStaticPaths() {
    const languages = ["ca", "es", "en"]; // Idiomas soportados

    const pagesRes = await fetch("http://localhost:8000/wp-json/wp/v2/pages?_fields=acf,slug,id&per_page=100");
    const pages = await pagesRes.json();
    
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
    
    pages.forEach(page => {
        const extracted = extractLang(page.slug);
        if (extracted && languages.includes(extracted.lang)) {
            paths.push({
                params: { lang: extracted.lang, slug: extracted.baseSlug },
                props: { id: page.id, acf: page.acf, slug: page.slug },
            });
            console.log(`Path added: ${extracted.lang}/${extracted.baseSlug}`);
            console.log(page.id, page.acf, page.slug);
            
        }
    });
    console.log(paths);
    
    return paths;

}
