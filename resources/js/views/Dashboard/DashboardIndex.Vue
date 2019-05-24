<template>
  <VContainer>
    <VLayout
      row
      wrap
      justify-center
    >
      <v-flex xs12>
        <MainReport/>
      </v-flex>
    </VLayout>
  </VContainer>
</template>

<script>
  import MainReport from '../Report/MainReport'

  export default {
    name: 'DashboardIndex',
    components: {
      MainReport
    },
    
    data () {
      return {
        
      }
    }
  }
</script>

<style scoped>
  .canvas {
    background-color: #e8eaed;
    border-radius: 3px;
    width: 100%;
    height: 400px;
  }
</style>


