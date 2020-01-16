<template>
  <div>
    <client-only>
      <p>Bonjour {{ authUser }}</p>
      <ul>
        <li v-for="message in messages" :key="message.id">
          {{ message.data().name }}
        </li>
      </ul>
    </client-only>
  </div>
</template>

<script>
export default {
  data() {
    return {
      messages: []
    }
  },
  computed: {
    authUser() {
      return this.$store.state.auth.user
    }
  },
  mounted() {
    /* this.$fireStore
      .collection('users')
      .doc(this.authUser.id)
      .collection('products')
      .get()
      .then((snap) => {
        this.messages = []
        snap.forEach((doc) => {
          this.messages.push(doc)
        })
      }) */
  },
  middleware: 'securePage'
}
</script>
