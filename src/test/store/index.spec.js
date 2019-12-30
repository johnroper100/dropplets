import { actions } from '@/store'

describe('actions', () => {
  test('nuxtServerInit with verified auth user', () => {
    // mocking
    const commit = jest.fn((path, payload) => {})
    const context = {
      res: {
        verifiedFireAuthUser: 'bonjour'
      }
    }

    // run
    actions.nuxtServerInit({ commit }, context)

    // must be call once
    expect(commit.mock.calls.length).toBe(1)
    // the first argument of the first call
    expect(commit.mock.calls[0][0]).toBe('auth/setUser')
    // the second argument of the first call
    expect(commit.mock.calls[0][1]).toBe('bonjour')
  })

  test('nuxtServerInit without verified auth user', () => {
    // mocking
    const commit = jest.fn((path, payload) => {})
    const context = {
      res: {}
    }

    // run
    actions.nuxtServerInit({ commit }, context)

    // assert result
    expect(commit.mock.calls.length).toBe(0)
  })

  test('handleSuccessfulAuthentication', () => {
    // mocking
    const commit = jest.fn((path, payload) => {})
    const user = 'salut'

    // run
    actions.handleSuccessfulAuthentication({ commit }, { authUser: user })

    // assert result
    expect(commit.mock.calls.length).toBe(1)
    // the first argument of the first call
    expect(commit.mock.calls[0][0]).toBe('auth/setUser')
    // the second argument of the first call
    expect(commit.mock.calls[0][1]).toBe('salut')
  })
})
