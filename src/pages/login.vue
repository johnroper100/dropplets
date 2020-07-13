<template>
  <v-container>
    <client-only>
      <firebase-ui />
    </client-only>
  </v-container>
</template>

<style>
li {
  list-style-type: none;
}
</style>

<script>
import FirebaseUi from '~/components/FirebaseUi'

export default {
  name: 'Login',
  components: {
    FirebaseUi
  }
}
</script>
