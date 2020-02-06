function initialState() {
  return {
    authUser: null
  }
}

export const state = initialState

export const mutations = {
  /**
   * Permet de réinitialiser le state a sa version d'origine
   * Utile quand on déconnecte un utilisateur
   */
  resetStore(state) {
    // acquire initial state
    const s = initialState()
    Object.keys(s).forEach((key) => {
      state[key] = s[key]
    })
  },

  /**
   * Permet de set les infos du authUser dans le state
   */
  seAuthUser(state, authUser) {
    state.authUser = {
      uid: authUser.user_id,
      email: authUser.email,
      displayName: authUser.displayName,
      photoURL: authUser.picture,
      admin: authUser.admin
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
  signIn({ commit }, firebaseAuthUser) {
    commit('seAuthUser', firebaseAuthUser)
  },

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
  }
}
