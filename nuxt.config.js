import colors from 'vuetify/es5/util/colors'

export default {
  /*
   ** Nuxt rendering mode
   ** See https://nuxtjs.org/api/configuration-mode
   */
  mode: 'universal',

  /*
   ** Nuxt target
   ** See https://nuxtjs.org/api/configuration-target
   */
  target: 'server',

  /*
   ** Headers of the page
   ** See https://nuxtjs.org/api/configuration-head
   */
  head: {
    titleTemplate: '%s - ' + process.env.npm_package_name,
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || '',
      },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },

  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#fff' },

  /*
   ** Global CSS
   */
  css: ['~/assets/main.css'],

  /*
   ** Plugins to load before mounting the App
   ** https://nuxtjs.org/guide/plugins
   */
  plugins: [],

  /*
   ** Auto import components
   ** See https://nuxtjs.org/api/configuration-components
   */
  components: true,

  /*
   ** Nuxt.js dev-modules
   */
  buildModules: ['@nuxt/typescript-build', '@nuxtjs/vuetify'],

  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    '@nuxtjs/firebase',
  ],

  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {},

  /**
   * Permet de configurer la librairie PWA
   * Workbox crée automatiquement un service worker (sw.js)
   * Pour ajouter le service worker du plugin nuxt-fire, il faut l'ajouter ici
   */
  pwa: {
    workbox: {
      /* workbox options */
      dev: true,
      importScripts: ['/firebase-auth-sw.js'],
    },
  },

  /*
   ** vuetify module configuration
   ** https://github.com/nuxt-community/vuetify-module
   */
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: true,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3,
        },
      },
    },
  },

  /*
   ** Nuxt Fire configuration
   ** https://github.com/lupas/nuxt-fire
   */
  firebase: {
    config: {
      apiKey: 'AIzaSyC3qpcRaVJVT63YMfIMgNJKRsmtPmEB6VM',
      authDomain: 'bento-vince.firebaseapp.com',
      databaseURL: 'https://bento-vince.firebaseio.com',
      projectId: 'bento-vince',
      storageBucket: 'bento-vince.appspot.com',
      messagingSenderId: '419042376123',
      appId: '1:419042376123:web:f2a4223fcbff6f078b6c9e',
      measurementId: 'G-VL64YPQL57',
    },
    services: {
      auth: {
        // Experimental Feature, use with caution.
        initialize: {
          onAuthStateChangedAction: 'onAuthStateChangedMutation',
        },
        ssr: true,
      },
      firestore: true,
    },
  },

  /*
   ** Build configuration
   ** See https://nuxtjs.org/api/configuration-build/
   */
  build: {},
}
