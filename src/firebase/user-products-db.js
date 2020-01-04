import GenericDB from './generic-db'

export default class UserProductsDB extends GenericDB {
  constructor(userId, firestore) {
    super(`users/${userId}/products`, firestore)
  }

  // Here you can extend UserProductsDB with custom methods
}
