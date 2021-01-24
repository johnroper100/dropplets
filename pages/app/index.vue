<template>
  <div>
    <p>Hello {{ authUser.displayName }} 🎈</p>
    <ul>
      <li v-for="message in messages" :key="message.id">
        {{ message.data().name }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  middleware: 'securePage',
  data() {
    return {
      messages: [],
    }
  },
  computed: {
    authUser() {
      return this.$store.state.auth.authUser
    },
  },
  mounted() {
    /* this.$fire.firestore
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
}
</script>
