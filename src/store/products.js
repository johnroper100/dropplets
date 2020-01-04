import UserProductsDB from '@/firebase/user-products-db'

function initialState() {
  return {
    products: null,
    productNameToCreate: '',
    productDeletionPending: [],
    productCreationPending: false
  }
}

export const state = initialState

export const mutations = {
  /* Product input name */
  setProductNameToCreate: (state, productNameToCreate) =>
    (state.productNameToCreate = productNameToCreate),

  /* Products */
  setProducts: (state, products) => (state.products = products),
  addProduct: (state, product) => state.products.push(product),
  removeProductById: (state, productId) => {
    const index = state.products.findIndex(
      (product) => product.id === productId
    )
    state.products.splice(index, 1)
  },

  /* Products deletion */
  addProductDeletionPending: (state, productId) =>
    state.productDeletionPending.push(productId),
  removeProductDeletionPending: (state, productId) => {
    const index = state.products.findIndex(
      (product) => product.id === productId
    )
    state.productDeletionPending.splice(index, 1)
  },

  /* Product creation */
  setProductCreationPending: (state, value) =>
    (state.productCreationPending = value)
}

export const getters = {
  /**
   * Check if a product has deletion pending
   */
  isProductDeletionPending: (state) => (productId) =>
    state.productDeletionPending.includes(productId),

  /**
   * Get product by id
   */
  getProductById: (state) => (productId) =>
    find(state.products, (product) => product.id === productId)
}

export const actions = {
  /**
   * Fetch products of current loggedin user
   */
  getUserProducts: async ({ rootState, commit }) => {
    const userProductDb = new UserProductsDB(rootState.authentication.user.id)

    const products = await userProductDb.readAll()
    commit('setProducts', products)
  },

  /**
   * Create a product for current loggedin user
   */
  createUserProduct: async ({ commit, rootState }, product) => {
    const userProductDb = new UserProductsDB(rootState.authentication.user.id)

    commit('setProductCreationPending', true)
    const createdProduct = await userProductDb.create(product)
    commit('addProduct', createdProduct)
    commit('setProductCreationPending', false)
  },

  /**
   * Create a new product for current loggedin user and reset product name input
   */
  triggerAddProductAction: ({ dispatch, state, commit }) => {
    if (state.productNameToCreate === '') return

    const product = { name: state.productNameToCreate }
    commit('setProductNameToCreate', '')
    dispatch('createUserProduct', product)
  },

  /**
   * Delete a user product from its id
   */
  deleteUserProduct: async ({ rootState, commit, getters }, productId) => {
    if (getters.isProductDeletionPending(productId)) return

    const userProductsDb = new UserProductsDB(rootState.authentication.user.id)

    commit('addProductDeletionPending', productId)
    await userProductsDb.delete(productId)
    commit('removeProductById', productId)
    commit('removeProductDeletionPending', productId)
  }
}
