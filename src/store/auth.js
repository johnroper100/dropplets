export const state = () => ({
  authUser: null
})

export const mutations = {
  /**
   * Permet de réinitialiser le state a sa version d'origine
   * Utile quand on déconnecte un utilisateur
   */
  resetStore(sta) {
    Object.assign(sta, this.state)
  },

  /**
   * Permet de set les infos du user dans le state
   */
  setAuthUser(state, { authUser }) {
    state.authUser = {
      uid: authUser.uid,
      email: authUser.email
    }
  }
}

export const getters = {
  /**
   * Permet de savoir si un utilisateur est connecté ou pas.
   */
  isLoggedIn: (state) => {
    try {
      return state.authUser.uid !== null
    } catch (err) {
      return false
    }
  }
}

export const actions = {
  /**
   * Permet de déconnecter l'utilisateur et remettre le state à l'initial
   */
  signOut({ commit }) {
    this.$fireAuth
      .signOut()
      .then(() => {
        commit('resetStore')
      })
      .catch((err) => {
        console.error('Erreur', err)
        alert(err)
      })

    console.log('signOut fait ✅')
  }
}
