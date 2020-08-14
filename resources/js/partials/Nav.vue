<template>
  <div class="shadow-md flex justify-between items-center px-12 py-4">
    <div class="flex-1">
      <router-link :to="userIsSignedIn ? '/home' : '/'">
        <h1 class="text-2xl">Safety Amp</h1>
      </router-link>
    </div>
    <div class="flex-1 flex justify-end">
      <router-link to="/login" v-if="! userIsSignedIn" class="navBtn">Login</router-link>
      <router-link to="/jhas" v-if="userIsSignedIn" class="navBtn">All JHAs</router-link>
      <router-link to="/jhas/create" v-if="userIsSignedIn" class="navBtn">Creat a JHA</router-link>
      <div @click="logout" v-if="userIsSignedIn" class="navBtn">Logout</div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  created() {
    this.getJHAs();
  },
  data() {
    return {}
  },
  methods: {
    ...mapActions('usersModule', { 
      logout: 'logout'
    }),
    ...mapActions('jhasModule', { 
      getJHAs: 'getJHAs'
    })
  },
  computed: {
    ...mapState('usersModule', {
      userIsSignedIn: 'userIsSignedIn',
      user: 'user'
    }),
    ...mapState('jhasModule', {
      jhas: 'jhas'
    })
  }
}
</script>

<style lang="scss" scoped>
.navBtn {
  @apply mx-4 cursor-pointer;
}
</style>
