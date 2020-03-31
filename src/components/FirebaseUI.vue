<template>
  <div>
    <div id="firebaseui-auth-container"></div>
    <v-progress-circular
      v-if="loading"
      indeterminate
      color="primary"
    ></v-progress-circular>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { desktop as isDesktop } from 'is_js'

export default {
  props: {
    inscription: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      loading: true
    }
  },

  computed: mapState({
    authUser: (state) => state.auth.authUser
  }),

  watch: {
    authUser(slt) {
      console.log('ok', slt)
      if (slt !== null) {
        this.openAppPage()
      }
    }
  },

  mounted() {
    if (process.browser) {
      const firebaseui = require('firebaseui')
      const firebase = require('firebase/app')

      const ui =
        firebaseui.auth.AuthUI.getInstance() ||
        new firebaseui.auth.AuthUI(this.$fireAuth)

      const authProviders = {
        Google: firebase.auth.GoogleAuthProvider.PROVIDER_ID,
        Email: firebase.auth.EmailAuthProvider.PROVIDER_ID
      }

      // Firebase signin with popup is faster than redirect
      // but we can't use it on mobile because it's not well supported
      const method = isDesktop() ? 'popup' : 'redirect'

      const config = {
        credentialHelper: firebaseui.auth.CredentialHelper.NONE,
        signInOptions: [authProviders.Google, authProviders.Email],
        signInFlow: method,
        tosUrl: this.inscription === true ? '/tos' : undefined,
        privacyPolicyUrl:
          this.inscription === true ? '/privacy-policy' : undefined,
        callbacks: {
          signInSuccessWithAuthResult: this.signInResult,
          signInFailure: this.signInError,
          uiShown: this.uiShown
        }
      }
      ui.disableAutoSignIn()

      if (this.authUser) {
        this.openAppPage()
      } else {
        ui.start('#firebaseui-auth-container', config)
      }
    }
  },
  methods: {
    signInResult(authResult, redirectUrl) {
      // this.openAppPage()
      this.loading = true
      console.log('signInResult')
      return false
    },

    signInError(e) {
      // TODO
    },

    uiShown() {
      this.loading = false
      console.log('uiShown')
    },

    openAppPage() {
      this.$router.push({
        path: '/app'
      })
    }
  }
}
</script>

<style src="~~/node_modules/firebaseui/dist/firebaseui.css"></style>
