import { mount } from '@vue/test-utils'
import Logo from '@/components/Logo.vue'

describe('Logo', () => {
  const deux = 3

  test('is a Vue instance', () => {
    const wrapper = mount(Logo)
    expect(wrapper.isVueInstance()).toBeTruthy()
  })
  test('yo', () => {
    expect(deux).toEqual(3)
  })
})

describe('salut', () => {
  test('bonjour', () => {
    const deux = 2
    expect(deux).toEqual(2)
  })
})
