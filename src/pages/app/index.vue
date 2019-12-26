<template>
  <div>
    <client-only>
      <p>Bonjour {{ authUser }}</p>
    </client-only>
  </div>
</template>

<script>
export default {
  computed: {
    authUser() {
      return this.$store.state.authUser
    }
  },
  middleware: 'auth'
}
</script>
