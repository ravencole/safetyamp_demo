<template>
  <div class="mx-auto mb-20">
    <div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Name
        </div> 
        <div class="jhaRowVal">
          <input class="defaultInput" type="text" v-model="name" />
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Department
        </div> 
        <div class="jhaRowVal">
          <select class="w-full" v-model="departmentSelected">
            <option v-for="d in departments" v-bind:value="d.id">
            {{ d.name }}
            </option>
          </select>
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Supervisor
        </div> 
        <div class="jhaRowVal">
          <select class="w-full" v-model="supervisorSelected">
            <option v-for="sup in supervisors" v-bind:value="sup.id">
            {{ sup.name }}
            </option>
          </select>
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          PPE
        </div> 
        <div class="jhaRowVal">
          <textarea class="defaultInput h-40" v-model="ppe">
          </textarea>
        </div> 
      </div>
      <div class="jhaRow">
        <div class="jhaRowKey">
          Training
        </div> 
        <div class="jhaRowVal">
          <textarea class="defaultInput h-40" v-model="training">
          </textarea>
        </div> 
      </div>
      <h1 class="text-2xl text-center font-bold">Steps</h1>
      <div
        class="text-white bg-blue-600 rounded cursor-pointer font-bold text-center py-4 mt-4 px-20"
        @click="addStep"
      >
        Add Step
      </div>
      <div :key="i" v-for="(step, i) in steps" class="mt-2 flex border-t border-1 border-gray-500">
        <div class="flex items-start justify-center">
          <div 
            class="py-1 px-2 bg-red-600 text-white mr-2 cursor-pointer"
            @click="() => removeStep(i)"
          >
            X
          </div>
        </div>
        <div class="w-16 border-r border-1 border-gray-500 mr-2">
          Step: {{ i }}
        </div>
        <div class="flex-1 border-r border-1 border-gray-500 mr-2">
          Task: <input class="defaultInput" type="text" v-model="step.task" />
        </div>
        <div class="flex-1 flex border-r border-1 border-gray-500 mr-2">
          <div class="mr-2">
            Hazards:
          </div>
          <textarea class="defaultInput h-40" v-model="step.hazards">
          </textarea>
        </div>
        <div class="flex-1 flex">
          <div class="mr-2">
            Controls:
          </div>
          <textarea class="defaultInput h-40" v-model="step.controls">
          </textarea>
        </div>
      </div>
      <div
        class="text-white bg-blue-600 rounded cursor-pointer font-bold text-center py-4 mt-4 px-20"
        @click="submit"
      >
        Submit
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
  name: 'jhaCreate',
  created() {
    this.getDepartments()
  },
  data() {
    return {
      name: '',
      ppe: '',
      training: '',
      steps: [],
      departmentSelected: null,
      supervisorSelected: null,
    }
  },
  computed: {
    ...mapState('jhasModule', [
      'departments'
    ]),
    ...mapState('usersModule', [
      'user'
    ]),
    ...mapGetters('usersModule', [
      'userIsSupervisor',
      'userIsEmployee',
      'supervisors'
    ])
  },
  methods: {
    ...mapActions('jhasModule', {
      getDepartments: 'getDepartments',
      submitJHA: 'submitJHA'
    }),
    removeStep(i) {
      this.steps = this.steps.reduce((a,b,k) => {
        if(i != k)
          a.push(b)
        return a
      }, [])
    },
    addStep() {
      const steps = this.steps

      steps.push({
        'task': '',
        'hazards': '',
        'controls': '',
      })
    },
    submit() {
      const steps = this.steps.reduce((a,b) => {
        a.push({
          task: b.task,
          hazards: b.hazards.split("\n"),
          controls: b.controls.split("\n")
        })

        return a
      }, [])

      const payload = {
        task_name: this.name,
        department_id: this.departmentSelected,
        supervisor_id: this.supervisorSelected,
        ppe: this.ppe.split("\n"),
        training: this.training.split("\n"),
        steps: steps
      }

      this.submitJHA(payload)
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
.defaultInput {
  @apply border border-blue-500 w-full;
}
</style>
