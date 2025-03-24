/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/**/*.{astro,html,js,ts,jsx,tsx}"],
    theme: {
      extend: {
        colors: {
          primary: "#058279",
          secondary: "#67D19F",
          yellow: "#FFC721",
          orange: "#F57E26",
          blue: "#81AEF6",
          red: "#FC8975",
          purple: "#9747FF",
          white: "#FFFFFF",
          black: "#000000",
        },
        fontFamily: {
          poppins: ["Poppins", "sans-serif"],
        },
        fontSize: {
          h1: ["30px", { lineHeight: "30px" }],
          h2: ["25px", { lineHeight: "18px" }], // Cambia a 30px si ocupa dos líneas
          h3: ["20px", { lineHeight: "18px" }], // Cambia a 24px si ocupa dos líneas
          h4: ["17px", { lineHeight: "30px" }], // Algunos tienen 18px
          p: ["15px", { lineHeight: "18px" }],
          copyright: ["8px", { lineHeight: "18px" }],
          button: ["20px", { lineHeight: "30px", textTransform: "uppercase" }],
        },
        fontWeight: {
          400: "400",
          500: "500",
          600: "600",
          700: "700",
          800: "800",
        },
      },
    },
    plugins: [],
  };
  