<template>
  <div class="modInput">
    <div class="input">
      <md-input-container>
          <label>Module Code</label>
          <md-input v-model="code"></md-input>
      </md-input-container>
      <md-input-container>
          <label>Module Name</label>
          <md-input v-model="name"></md-input>
      </md-input-container>
      <md-input-container>
          <label>Module Ref</label>
          <md-input v-model="ref"></md-input>
      </md-input-container>
      <md-input-container>
        <label>Module Outline</label>
        <md-textarea v-model="outline"></md-textarea>
      </md-input-container>
      <md-button class="md-raised" @click.native="addModule">Add Module</md-button>
    </div>
    <br>
    <div class="output">
      <h2>HTML module outline</h2>
      <br>
      <div v-html="outputHtml"></div>
    </div>
    <br>
  </div>

</template>


<script>
  import marked from 'marked'
  import api from '@/js/api'

  export default {
    data () {
      return {
        name: '',
        code: '',
        ref: '',
        outline: ''
      }
    },
    computed: {
      outputHtml () {
        return marked(this.outline)
      }
    },
    methods: {
      addModule () {
        api.post('add_module', {
          mod_ref: this.ref,
          mod_code: this.code,
          mod_name: this.name,
          mod_outline: this.outline
        })
        .then(response => {
          this.name = this.code = this.ref = this.outline = ''
        })
      }
    }
  }
</script>

<style lang="scss">
  .modInput {
    display: flex;
    width: 100%;
  }

  .input, .output {
    flex: 1;
    padding: 15px;
  }
</style>
