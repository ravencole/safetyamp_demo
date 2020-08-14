<template>
  <div class="mx-auto mb-20">
    <div v-if="focusedJHA != null">
      <div class="flex justify-end">
        <div 
          v-if="userIsSupervisor && ! jhaHasBeenReviewed()"
          class="btn"
          @click="() => reviewJHA(focusedJHA.id)"
        >
          Review 
        </div>
        <div 
          v-if="userIsSupervisor && ! jhaHasBeenApproved()"
          class="btn"
          @click="() => approveJHA(focusedJHA.id)"
        >
          Approve 
        </div>
        <div 
          v-if="userIsSupervisor || user.id == focusedJHA.preparer.id"
          class="editBtn"
          @click="() => this.$router.push(`/jhas/${this.focusedJHA.id}/edit`)"
        >
          Edit
        </div>
        <div 
          v-if="userIsSupervisor || user.id == focusedJHA.preparer.id"
          class="dangerBtn"
          @click="confirmDeleteJHA"
        >
          Delete
        </div>
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Name
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.task_name }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          ID 
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.id }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Status
        </div> 
        <div class="jhaRowVal">
          {{ this.getJHAStatus() }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Preparer
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.preparer.name }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Supervisor
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.supervisor.name }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Department
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.department.name }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Created On
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.created_at | formatDate }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Last Updated
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.updated_at | formatDate }}
        </div> 
      </div>
      <div v-if="jhaHasBeenReviewed()" class="jhaRow">
        <div class="jhaRowKey">
          Reviewed At
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.reviewed_at | formatDate }}
        </div> 
      </div>
      <div v-if="jhaHasBeenReviewed()" class="jhaRow">
        <div class="jhaRowKey">
          Reviewed By
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.reviewed_by.name }}
        </div> 
      </div>
      <div v-if="jhaHasBeenApproved()" class="jhaRow">
        <div class="jhaRowKey">
          Approved At
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.approved_at | formatDate }}
        </div> 
      </div>
      <div v-if="jhaHasBeenApproved()" class="jhaRow">
        <div class="jhaRowKey">
          Approved By
        </div> 
        <div class="jhaRowVal">
          {{ focusedJHA.approved_by.name }}
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          PPE
        </div> 
        <div class="jhaRowVal">
          <ul>
            <li v-for="ppe in focusedJHA.ppe">
              {{ ppe }}
            </li>
          </ul>
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Training
        </div> 
        <div class="jhaRowVal">
          <ul>
            <li v-for="training in focusedJHA.training">
              {{ training }}
            </li>
          </ul>
        </div> 
      </div>
      <h1 class="text-2xl text-center font-bold">Steps</h1>
      <div v-for="(step, i) in focusedJHA.steps" class="mt-2 flex border-t border-1 border-gray-500">
        <div class="w-16 border-r border-1 border-gray-500 mr-2">
          Step: {{ i }}
        </div>
        <div class="flex-1 border-r border-1 border-gray-500 mr-2">
          Task: {{ step.task }}
        </div>
        <div class="flex-1 flex border-r border-1 border-gray-500 mr-2">
          <div class="mr-2">
            Hazards:
          </div>
          <ul>
            <li v-for="hazard in step.hazards">{{ hazard }}</li>
          </ul>
        </div>
        <div class="flex-1 flex">
          <div class="mr-2">
            Controls:
          </div>
          <ul>
            <li v-for="control in step.controls">{{ control }}</li>
          </ul>
        </div>
      </div>
    </div>
    <div v-else>
      <h1 class="text-2xl">JHA not found</h1> 
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
  name: 'jhasShow',
  created() {
    this.setFocusedJHA(this.$route.params.jha)
  },
  computed: {
    ...mapState('jhasModule', [
      'focusedJHA'
    ]),
    ...mapState('usersModule', [
      'user'
    ]),
    ...mapGetters('usersModule', [
      'userIsSupervisor',
      'userIsEmployee'
    ])
  },
  methods: {
    ...mapActions('jhasModule', {
      setFocusedJHA: 'setFocusedJHA',
      approveJHA: 'approveJHA',
      reviewJHA: 'reviewJHA',
      deleteJHA: 'deleteJHA',
      unfocusJHA: 'unfocusJHA'
    }),
    getJHAStatus() {
      if(this.jhaHasBeenReviewed() && this.jhaHasBeenApproved())
        return 'Completed'
      else if(this.jhaHasBeenReviewed())
        return 'Reviewed'
      else if(this.jhaHasBeenApproved())
        return 'Approved'
      return 'Created'
    },
    jhaHasBeenReviewed() {
      return this.focusedJHA.reviewed_at != null
    },
    jhaHasBeenApproved() {
      return this.focusedJHA.approved_at != null
    },
    confirmDeleteJHA() {
      if(window.confirm('Are you sure you want to delete this JHA?'))
        this.deleteJHA(this.focusedJHA.id)
    }
  }
}
</script>

<style type="scss" scoped>
.jhaRow {
  @apply flex mb-1;
}
.jhaRowKey {
  @apply bg-gray-500 text-black w-1/6 pr-2 text-right mr-2;
}
.jhaRowVal {
  @apply flex-1;
}
.btn {
  @apply px-2 py-1 font-bold text-white bg-green-600 rounded mr-2 cursor-pointer;
}
.dangerBtn {
  @apply btn bg-red-600;
}
.editBtn {
  @apply btn bg-orange-600;
}
</style>
