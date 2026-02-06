/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php", // Quét tất cả các file Blade
    "./resources/**/*.js",       // Quét các file JavaScript trong resources
    "./resources/**/*.vue",      // Nếu bạn dùng Vue
    "./app/View/Components/**/*.php", // Quét các Blade Components của bạn
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php", // Cho pagination styling
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}