function initialState() {
  return {
    authUser: null,
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
  setAuthUser(state, authUser) {
    state.authUser = {
      uid: authUser.uid,
      email: authUser.email,
      displayName: authUser.displayName,
    }
  },
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
  },
}

export const actions = {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  signIn({ commit, dispatch }, { authUser, claims }) {
    commit('setAuthUser', authUser)
    // await dispatch('user/loadUser', null, { root: true })
  },

  /**
   * Permet de déconnecter l'utilisateur et remettre le state à l'initial
   */
  signOut({ commit }) {
    this.$fire.auth
      .signOut()
      .then(() => {
        commit('resetStore')
      })
      .catch((err) => {
        console.error('Erreur', err)
        alert(err)
      })
  },
}
