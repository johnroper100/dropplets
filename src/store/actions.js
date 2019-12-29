export default {
  /**
   * Cette méthode est appellée à chaque initialisation de Vuex
   * Elle permet de mettre en place l'utilisateur connecté quand on reload la page
   */
  nuxtServerInit({ commit }, ctx) {
    const ssrVerifiedAuthUser = ctx.res.verifiedFireAuthUser
    if (ssrVerifiedAuthUser) {
      commit('SET_AUTH_USER', {
        authUser: ssrVerifiedAuthUser
      })
    }
    console.log('nuxtServerInit fait ✅')
  },

  /**
   * Est appelé par la librarie nuxt-fire
   * Permet de sauver l'utilisateur dans le state qu'on il vient de se connecter
   */
  handleSuccessfulAuthentication({ commit }, { authUser }) {
    commit('SET_AUTH_USER', { authUser })
    console.log('handleSuccessfulAuthentication fait ✅')
  },

  /**
   * Permet de déconnecter l'utilisateur et remettre le state à l'initial
   */
  signOut({ commit }) {
    this.$fireAuth
      .signOut()
      .then(() => {
        commit('RESET_STORE')
      })
      .catch((err) => {
        console.error('Erreur', err)
        alert(err)
      })

    console.log('signOut fait ✅')
  }
}
