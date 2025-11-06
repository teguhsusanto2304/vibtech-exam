/** @type {import('tailwindcss').Config} */
export default {
  darkMode: "class", // enables dark mode using the 'class' strategy
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#003366",
        "background-light": "#f1f3f4",
        "background-dark": "#101922",
        success: "#28a745",
        danger: "#dc3545",
      },
      fontFamily: {
        display: ["Inter", "sans-serif"],
      },
      borderRadius: {
        DEFAULT: "0.5rem",
        lg: "0.75rem",
        xl: "1rem",
        full: "9999px",
      },
    },
  },
  plugins: [
    require("@tailwindcss/forms"),
    require("@tailwindcss/container-queries"),
  ],
};
