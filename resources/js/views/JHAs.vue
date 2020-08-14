<template>
  <div class="mx-auto mb-20">
    <div 
      v-for="jha in jhas" 
      class="cursor-pointer hover:border-grey-800 rounded p-2 mb-4 border-2 border-grey-300"
      @click="() => focusJHA(jha)"
    >
      <div class="flex justify-between">
        <div>
          <span class="font-bold">Name:</span> {{ jha.task_name }} 
        </div>
        <div class="text-right">
          Updated On: {{ jha.updated_at | formatDate }}
        </div>
      </div>
      <hr class="my-2" />
      <div class="flex">
        <div class="flex-1">
          <span class="font-bold">Preparer:</span> {{ jha.preparer.name }} 
        </div>
        <div class="mx-1 flex-1 font-bold text-center" :class="{ 'bg-green-600': jha.reviewed_at != null, 'bg-red-600': jha.reviewed_at == null }">
          Reviewed
        </div>
        <div class="mx-1 flex-1 font-bold text-center" :class="{ 'bg-green-600': jha.approved_at != null, 'bg-red-600': jha.approved_at == null }">
          Approved
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'jhas',
  created() {
    this.getAll()
  },
  data() {
    return {}
  },
  computed: {
    ...mapState('jhasModule', [
      'jhas'
    ])
  },
  methods: {
    ...mapActions('jhasModule', {
      getAll: 'getAll'
    }),
    focusJHA(jha) {
      this.$router.push('/jhas/' + jha.id)
    }
  }
}
</script>
