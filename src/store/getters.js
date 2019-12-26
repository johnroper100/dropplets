export default {
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
