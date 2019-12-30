import testAction from '../util/testAction'
import { state, mutations, getters, actions } from '@/store/auth'

describe('state', () => {
  test('initial state', () => {
    // expected initial state
    const s = {
      user: null
    }
    expect(state()).toEqual(s)
  })
})

describe('mutations', () => {
  test('resetStore -> auth user must be null', () => {
    // mock state
    const state = {
      user: {
        uid: 'uid',
        email: 'email'
      }
    }
    // apply mutation
    mutations.resetStore(state)
    // assert result
    expect(state.user).toBeNull()
  })

  test('setUser in the state', () => {
    // mock state
    const state = {
      user: null
    }
    // apply mutation
    const u = {
      uid: 'uid',
      email: 'email',
      autre: 'autre'
    }
    mutations.setUser(state, u)
    // assert result
    const expected = {
      uid: 'uid',
      email: 'email'
    }
    expect(state.user).toEqual(expected)
  })
})

describe('getters', () => {
  test('isLoggedIn user not set', () => {
    // mock state
    const state = {
      user: null
    }
    // mock getter
    const isLog = getters.isLoggedIn(state)
    // assert result
    expect(isLog).toBeFalsy()
  })

  test('isLoggedIn user set', () => {
    // mock state
    const state = {
      user: {
        uid: 'uid',
        email: 'email'
      }
    }
    // mock getter
    const isLog = getters.isLoggedIn(state)
    // assert result
    expect(isLog).toBeTruthy()
  })
})

describe('actions', () => {
  test('signOut', (done) => {
    // done.$fireAuth = jest.fn()
    const context = {
      $fireAuth: {
        signOut() {
          return new Promise((resolve) => {
            resolve('resolved')
          })
        }
      }
    }
    // const state = { user: null }
    testAction(actions.signOut, context, {}, [{ type: 'resetStore' }], done)
  })
})
