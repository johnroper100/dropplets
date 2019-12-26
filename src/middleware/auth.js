export default function({ store, error }) {
  if (!store.state.authUser) {
    // redirect('/')
    error({
      message: 'You are not connected',
      statusCode: 403
    })
  }
  console.log('auth.js fait ✅', store.state)
}
