export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#018441',
        'secondary': '#F6BA0B',
        'tertiary': '#EF7623',
        'primary-light': '#46B57C',
        'primary-dark': '#014F27',
        'tertiary-light': '#F9C8A7',
        'cream': '#FFFDF1',
        'blue': '#006CD4',
        'border': '#ECECEC'
      },
      fontFamily: {
        cubao: ['Cubao', 'sans-serif'],
      },
      maxWidth: {
        '8xl': '1312px',
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
