<template>
  <div class="w-1/2 mx-auto mb-20">
    <h1 class="text-2xl text-center mb-4">Who would you like to be today?</h1>
    <div class="flex justify-center mb-4">
      <span 
        class="loginTab" 
        :class="{ active: loginTabActive == 'employee' }"
        @click="() => loginRoleChange('employee')"
      >
        Employee
      </span>
      <span 
        class="loginTab" 
        :class="{ active: loginTabActive == 'supervisor' }"
        @click="() => loginRoleChange('supervisor')"
      >
        Supervisor
      </span>
    </div>
    <hr />
    <div class="mt-4">
      <div 
        class="flex border-b-2 border-transparent hover:text-white hover:bg-blue-500 cursor-pointer"
        >
        <div class="flex-1 text-right mr-2 font-bold">
          Name
        </div>
        <div class="flex-1 ml-2 font-bold">
          Department
        </div>
      </div> 
      <div 
        v-for="user in (loginTabActive == 'supervisor' ? supervisors : employees)"
        class="flex border-b-2 border-transparent hover:text-white hover:bg-blue-500 cursor-pointer"
        @click="() => login(user)"
        >
        <div class="flex-1 text-right mr-2">
          {{ user.name }} 
        </div>
        <div class="flex-1 ml-2">
          {{ user.department.name.replace(/^\w/, c => c.toUpperCase()) }} 
        </div>
      </div> 
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
  name: 'login',
  created() {
    this.getAllUsers()
  },
  data() {
    return {
      loginTabActive: 'employee'
    }
  },
  computed: {
    ...mapState('usersModule', {
      userIsSignedIn: 'userIsSignedIn',
      employeeList: 'employeeList',
      supervisorList: 'supervisorList',
    }),
    ...mapGetters('usersModule', [ 
      'employees',
      'supervisors'
    ])
  },
  updated() {
    if (this.userIsSignedIn) {
      this.$router.push({ name: 'home' })
    }
  },
  methods: {
    ...mapActions('usersModule', [ 
      'loginUser',
      'getAllUsers'
    ]),
    loginRoleChange(val) {
      this.loginTabActive = val
    },
    login(user) {
      this.loginUser(user)
    }
  }
}
</script>

<style type="scss" scoped>
.loginTab {
  @apply cursor-pointer px-4;
}

.active {
  @apply border-0 border-b-2 border-blue-500;
}
</style>
