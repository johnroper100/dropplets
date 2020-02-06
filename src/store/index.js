export const actions = {
  /**
   * Cette méthode est appellée à chaque initialisation de Vuex
   * Elle permet de mettre en place l'utilisateur connecté quand on reload la page
   */
  nuxtServerInit({ dispatch }, ctx) {
    const ssrVerifiedAuthUserClaims = ctx.res.verifiedFireAuthUserClaims
    if (ssrVerifiedAuthUserClaims) {
      dispatch('auth/signIn', ssrVerifiedAuthUserClaims)
    }
  },

  /**
   * Est appelé par la librarie nuxt-fire
   * Permet de sauver l'utilisateur dans le state qu'on il vient de se connecter
   *
   * On va récupérer le userClaims pour avoir les claims
   * https://firebase.google.com/docs/auth/admin/custom-claims#propagate_custom_claims_to_the_client
   */
  handleSuccessfulAuthentication({ dispatch }, { authUser }) {
    this.$fireAuth.currentUser.getIdTokenResult().then((result) => {
      dispatch('auth/signIn', result.claims)
    })
  }
}
