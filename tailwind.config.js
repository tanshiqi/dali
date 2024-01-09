/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "node_modules/preline/dist/*.js",
        "./vendor/wire-elements/modal/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        //
    ],
    options: {
        safelist: [
            "sm:max-w-2xl",
            //
        ],
    },
    theme: {
        extend: {
            colors: {
                "gray-850": "#18212F",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("preline/plugin"),
        //
    ],
};
