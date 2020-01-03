import { actions } from '@/store'

describe('actions', () => {
  test('nuxtServerInit with verified auth user', () => {
    // mocking
    const dispatch = jest.fn((path, payload) => {
      expect(path).toBe('auth/signIn')
      expect(payload).toBe('bonjour')
    })
    const context = {
      res: {
        verifiedFireAuthUser: 'bonjour'
      }
    }

    // run
    actions.nuxtServerInit({ dispatch }, context)

    // must be call once
    expect(dispatch.mock.calls.length).toBe(1)
    // the first argument of the first call
    expect(dispatch.mock.calls[0][0]).toBe('auth/signIn')
    // the second argument of the first call
    expect(dispatch.mock.calls[0][1]).toBe('bonjour')
  })

  test('nuxtServerInit without verified auth user', () => {
    // mocking
    const dispatch = jest.fn((path, payload) => {})
    const context = {
      res: {}
    }

    // run
    actions.nuxtServerInit({ dispatch }, context)

    // assert result
    expect(dispatch.mock.calls.length).toBe(0)
  })

  test('handleSuccessfulAuthentication', () => {
    // mocking
    const dispatch = jest.fn((path, payload) => {
      expect(path).toBe('auth/signIn')
      expect(payload).toBe('salut')
    })
    const user = 'salut'
    // run
    actions.handleSuccessfulAuthentication({ dispatch }, { authUser: user })
    // assert result
    expect(dispatch.mock.calls.length).toBe(1)
  })
})
