/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./public/**/*.{html,js}",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./Modules/**/resources/**/*.{html,js}",
        "./Modules/**/resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                roboto: ["Roboto", "sans-serif"],
            },
            colors: {
                primary: "#fd3d57",
            },
        },
        container: {
            center: true,
            padding: "1rem",
        },
        screens: {
            sm: "576px",
            md: "768px",
            lg: "992px",
            xl: "1200px",
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
    ],
};
