<template>
  <div>
    <div id="firebaseui-auth-container"></div>
  </div>
</template>
<script>
export default {
  name: 'Login',

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

      const config = {
        credentialHelper: firebaseui.auth.CredentialHelper.NONE,
        signInOptions: [authProviders.Google, authProviders.Email],
        signInFlow: 'popup',
        tosUrl: '/tos',
        privacyPolicyUrl: '/privacy-policy',
        callbacks: {
          signInSuccessWithAuthResult: this.signInResult
        }
      }
      ui.disableAutoSignIn()
      if (this.$store.state.authUser) {
        this.openAppPage()
      } else {
        ui.start('#firebaseui-auth-container', config)
      }
    }
  },
  methods: {
    signInResult() {
      this.openAppPage()
      return false
    },
    openAppPage() {
      this.$router.push({
        path: '/app'
      })
    }
  }
}
</script>
<style src="~/node_modules/firebaseui/dist/firebaseui.css"></style>
