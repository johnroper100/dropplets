<template>
  <div>
    <client-only>
      <p>Bonjour {{ authUser }}</p>
      <ul>
        <li v-for="message in messages" v-bind:key="message.id">
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
      return this.$store.state.authUser
    }
  },
  mounted() {
    this.$fireStore
      .collection('users')
      .doc(this.authUser.uid)
      .collection('products')
      .get()
      .then((snap) => {
        this.messages = []
        snap.forEach((doc) => {
          this.messages.push(doc)
        })
      })
  },
  middleware: 'auth'
}
</script>
