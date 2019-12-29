export default function({ store, redirect }) {
  if (!store.state.authUser) {
    redirect('/connexion')
    /* error({
      message: 'You are not connected',
      statusCode: 403
    }) */
  }
}
