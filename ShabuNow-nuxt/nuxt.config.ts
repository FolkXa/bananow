// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  css: ["~/assets/css/main.css"],
  modules: [
    // ...
    "@pinia/nuxt",
    "@pinia-plugin-persistedstate/nuxt",
    "nuxt-permissions",
  ],
  pinia: {
    storesDirs: ["./stores/**", "./custom-folder/stores/**"],
  },
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  runtimeConfig :{
    public: {
      apiBaseURL : "http://localhost/api/"
    }
  },
});
