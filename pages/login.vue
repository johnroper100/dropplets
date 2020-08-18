<template>
  <v-container>
    <client-only>
      <yop-ui />
    </client-only>
  </v-container>
</template>

<style>
li {
  list-style-type: none;
}
</style>

<script>
import YopUI from '~/components/YopUI'

export default {
  name: 'Login',
  components: {
    YopUI,
  },
}
</script>
