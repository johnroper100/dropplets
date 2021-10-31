<template>
  <div>
    <p>Hello {{ authUser.displayName }} 🎈</p>
    <p>Authorization: Bearer {{ idToken }}</p>
    <ul>
      <li v-for="message in messages" :key="message.id">
        {{ message.data().name }}
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import { getAuth } from 'firebase/auth'

export default Vue.extend({
  middleware: 'securePage',

  data() {
    return {
      messages: [],
      idToken: '',
    }
  },

  async fetch() {
    this.idToken = (await getAuth().currentUser?.getIdToken()) || ''
  },

  computed: {
    authUser(): string {
      return this.$store.state.auth.authUser
    },
  },

  mounted(): void {
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
})
</script>
