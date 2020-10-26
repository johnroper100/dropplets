export default function ({ store, redirect }) {
  if (!store.getters['auth/isLoggedIn']) {
    console.log('Not connected')
    redirect('/login')
    /* ssr:true error({
      message: 'You are not connected',
      statusCode: 403,
    }) */
  }
}
