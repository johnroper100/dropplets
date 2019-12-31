import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import Index from '@/pages/index.vue'

Vue.use(Vuetify)

const factory = (isLoggedIn) => {
  const localVue = createLocalVue()
  localVue.use(Vuex)
  localVue.use(Vuetify)

  const getters = {
    isLoggedIn: () => isLoggedIn
  }
  const actions = {
    signOut: jest.fn()
  }
  const store = new Vuex.Store({
    modules: {
      auth: {
        namespaced: true,
        actions,
        getters
      }
    }
  })

  return shallowMount(Index, {
    localVue,
    store
  })
}

describe('Connection / deconnection button', () => {
  test('connection', () => {
    const wrapper = factory(true)
    expect(wrapper.find('.btnLoginOut').text()).toEqual('Déconnexion')
  })

  test('logout', () => {
    const wrapper = factory(false)
    expect(wrapper.find('.btnLoginOut').text()).toEqual('Connexion')
  })
})
