export const actions = {
  /**
   * Cette méthode est appellée à chaque initialisation de Vuex
   * Elle permet de mettre en place l'utilisateur connecté quand on reload la page
   */
  async nuxtServerInit({ dispatch }, { res }) {
    if (res.locals && res.locals.user && res.locals.user.allClaims) {
      const authUser = res.locals.user
      const claims = res.locals.user.allClaims
      await dispatch('auth/signIn', { authUser, claims })
    }
  },

  /**
   * Est appelé par la librarie nuxt-fire
   * Permet de sauver l'utilisateur dans le state qu'on il vient de se connecter
   *
   * On va récupérer le userClaims pour avoir les claims
   * https://firebase.google.com/docs/auth/admin/custom-claims#propagate_custom_claims_to_the_client
   */
  onAuthStateChangedMutation({ dispatch }, { authUser, claims }) {
    if (authUser && claims) {
      dispatch('auth/signIn', { authUser, claims })
    } else {
      // TODO faire la déconnexion
    }
  },
}
