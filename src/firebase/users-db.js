import GenericDB from './generic-db'

export default class UsersDB extends GenericDB {
  constructor(firestore) {
    super('users', firestore)
  }

  // Here you can extend UserDB with custom methods
}
