<template>
  <v-app>
    <div class="d-flex text-center flex-column flex-md-row flex-grow-1">
      <v-sheet class="layout-side  mx-auto mx-md-1 d-md-flex flex-md-column justify-space-between px-2">
        <div class="pa-2" style="margin:auto;">
          <div class="display-1 font-weight-bold primary--text mb-4">
           <h2>Welcome To</h2>
          </div>
          <div >
            <a href="#" target="_blank">
                <img style="height: 150px;" class="img-fluid logo" :src="'/logo.png'" alt="">
              </a>
          </div>
          <div class="mt-3"><span class="display-1 primary--text">Task Management - </span> <span class="headline primary--text"> slogan here</span></div>

        </div>
        <!-- <img  class="w-full" src="~static/favicon.png" alt="prep-excellence"/> -->
      </v-sheet>

      <div class="pa-2 pa-md-0 flex-grow-1 align-center justify-center d-flex flex-column">
        <div class="layout-content ma-auto w-full">
          <nuxt />
        </div>
      </div>
    </div>
  </v-app>
</template>

<script>
import {mapGetters, mapMutations, mapState} from 'vuex'
export default {

  data(){
    return{
      institute:{},
    }
  },
  async fetch() {

  },
  fetchOnServer: false,
   computed: {
  },
  created() {

  }
}
</script>

<style scoped>
.layout-side {
  width: 50%;
}

.layout-content {
  max-width: 480px;
}
@media (max-width:930px) {
  .layout-side {
  width: 100%;
}
}
</style>

