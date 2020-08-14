<template>
  <div class="">
    <div v-if="userIsSignedIn">
      <h1 class="text-2xl text-center">Welcome Back, {{ user.name }}!</h1>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'home',
  computed: {
    ...mapState('usersModule', {
      userIsSignedIn: 'userIsSignedIn',
      user: 'user'
    })
  }
}
</script>
