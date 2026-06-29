/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  theme: {
    extend: {
      colors: {
        primary: '#4B8D7F',
        secondary: '#C8DDD9',
        tertiary: '#E0EEEB',
        accent: '#C8DDD9', // Used as secondary fallback
        wa: '#25D366',
        textMain: '#1A1A2E',
        textSec: '#555555',
      },
      fontFamily: {
        heading: ['Inter', 'Poppins', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
