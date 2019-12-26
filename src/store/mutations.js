import initialState from './state'

export default {
  /**
   * Permet de réinitialiser le state a sa version d'origine
   * Utile quand on déconnecte un utilisateur
   */
  RESET_STORE: (state) => {
    Object.assign(state, initialState())
  },

  /**
   * Permet de set les infos du user dans le state
   */
  SET_AUTH_USER(state, { authUser }) {
    console.log('SET_AUTH_USER')
    state.authUser = {
      uid: authUser.uid,
      email: authUser.email
    }
  }
}
