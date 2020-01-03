import { state, mutations, getters, actions } from '@/store/auth'

describe('state', () => {
  test('initial state', () => {
    // expected initial state
    const s = {
      authUser: null,
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
      id: 'uid',
      email: 'email',
      displayName: 'Jean Bon',
      photoURL: 'url',
      autre: 'autre'
    }
    mutations.setUser(state, u)
    // assert result
    const expected = {
      id: 'uid',
      email: 'email',
      displayName: 'Jean Bon',
      photoURL: 'url'
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
      authUser: {
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
  test('signOut no error', async () => {
    // mocking
    const commit = jest.fn((path) => {
      expect(path).toBe('resetStore')
    })
    actions.$fireAuth = {
      signOut() {
        return new Promise((resolve) => {
          resolve('resolved')
        })
      }
    }
    // run
    await actions.signOut({ commit })
    // assert result
    expect.assertions(2)
    expect(commit.mock.calls.length).toBe(1)
  })

  test('signOut error', async () => {
    // mocking
    const commit = jest.fn()
    const error = { message: 'Test bro', code: 42 }
    actions.$fireAuth = {
      signOut() {
        return new Promise((resolve) => {
          // eslint-disable-next-line no-throw-literal
          throw error
        })
      }
    }

    global.console.error = (p1, p2) => {
      expect(p1).toBe('Erreur')
    }
    global.alert = (yo) => {
      expect(yo).toEqual(error)
    }

    // run
    await actions.signOut({ commit })

    // assert result
    expect.assertions(3)
    expect(commit.mock.calls.length).toBe(0)
  })
})
