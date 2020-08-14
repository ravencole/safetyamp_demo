<template>
  <div id="app">
    <Nav /> 
    <div class="container mx-auto pt-16">
      <RouterView />
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import Nav from './partials/Nav.vue'

export default {
  components: {
    Nav
  }
}
</script>
