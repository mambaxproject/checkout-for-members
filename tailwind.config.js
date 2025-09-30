/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");

module.exports = {
    darkMode: "selector",
    content: ["./resources/**/*.blade.php", "./resources/**/*.js", "./node_modules/flowbite/**/*.js"],
    safelist: [

        'bg-[#404040]',
        'bg-[#30a72d]',
        'text-white',
        'rounded-br-md',
        'rounded-bl-md',
        'bg-black/60',
        "bg-danger-200",
        "bg-info-200",
        "bg-neutral-200",
        "bg-success-200",
        "bg-warning-200",
        "text-danger-800",
        "text-info-800",
        "text-neutral-800",
        "text-success-800",
        "text-warning-800",
        "text-neutral-400",
        "text-success-400",
        "text-danger-400",
        "text-warning-400",
        "text-info-400"
    ],

    theme: {
        colors: {
            primary: "#33cc33",
            neutral: colors.neutral,
            success: colors.green,
            danger: colors.red,
            info: colors.blue,
            warning: colors.yellow,
        },
        extend: {},
    },
    plugins: [require("flowbite/plugin"), require("@tailwindcss/typography"), "@thoughtbot/tailwindcss-aria-attributes", "prettier-plugin-tailwindcss"],
};
