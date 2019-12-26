import colors from 'vuetify/es5/util/colors'

export default {
  mode: 'universal',
  srcDir: 'src',
  buildDir: 'functions/.nuxt',

  /*
   ** Permet de définir l'adresse du serveur en local
   */
  server: {
    // port: 8000, // par défaut: 3000
    // host: '192.168.1.49' // host: '0.0.0.0' // par défaut: localhost
  },

  /*
   ** Headers of the page
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
        content: process.env.npm_package_description || ''
      }
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }]
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
   ** Transitions par défaut entre les pages
   */
  layoutTransition: 'layout',

  /*
   ** Plugins to load before mounting the App
   */
  plugins: [],

  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    '@nuxtjs/eslint-module',
    '@nuxtjs/vuetify'
  ],

  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    [
      'nuxt-fire',
      {
        config: {
          apiKey: 'AIzaSyC3qpcRaVJVT63YMfIMgNJKRsmtPmEB6VM',
          authDomain: 'bento-vince.firebaseapp.com',
          databaseURL: 'https://bento-vince.firebaseio.com',
          projectId: 'bento-vince',
          storageBucket: 'bento-vince.appspot.com',
          messagingSenderId: '419042376123',
          appId: '1:419042376123:web:f2a4223fcbff6f078b6c9e',
          measurementId: '<measurementId>'
        },
        services: {
          auth: {
            // Experimental Feature, use with caution.
            initialize: {
              onSuccessAction: 'handleSuccessfulAuthentication',
              ssr: true
            }
          }
        }
      }
    ]
  ],

  /*
   ** Axios module configuration
   ** See https://axios.nuxtjs.org/options
   */
  axios: {},

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
          success: colors.green.accent3
        }
      }
    }
  },

  /*
   ** Build configuration
   */
  build: {
    /*
     ** You can extend webpack config here
     */
    extractCSS: true,
    extend(config, ctx) {}
  }
}
