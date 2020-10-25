export default function ({ store, redirect }) {
  if (!store.getters['auth/isLoggedIn']) {
    redirect('/login')
    /* error({
      message: 'You are not connected',
      statusCode: 403,
    }) */
  }
}
