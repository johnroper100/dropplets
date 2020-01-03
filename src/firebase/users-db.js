import GenericDB from './generic-db'

export default class UsersDB extends GenericDB {
  constructor(firestore) {
    super('users', firestore)
  }

  // Here you can extend UserDB with custom methods

  createUser(firebaseAuthUser) {
    const providerData = firebaseAuthUser.providerData[0]
    const { displayName, photoURL, email } = providerData
    const user = {
      displayName,
      photoURL,
      email
    }
    return super.create(user, firebaseAuthUser.uid)
  }
}
