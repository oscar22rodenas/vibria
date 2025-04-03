import { extractLang } from "./extractLang";

export async function getStaticPaths() {
    const languages = ["ca", "es", "en"]; // Idiomas soportados

    const pagesRes = await fetch("http://localhost:8000/wp-json/wp/v2/pages?_fields=acf,slug&per_page=100");
    const pages = await pagesRes.json();
    
    let paths = [];
    
    pages.forEach(page => {
        const extracted = extractLang(page.slug);
        if (extracted && languages.includes(extracted.lang)) {
            paths.push({
                params: { lang: extracted.lang, slug: extracted.baseSlug },
                props: { acf: page.acf },
            });
        }
    });
    
    return paths;
}
