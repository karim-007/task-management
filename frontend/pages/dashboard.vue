<template>
  <div class="flex-grow-1" style="max-width: 100%">
    <client-only>
      <v-row dense>

      </v-row>
    </client-only>
  </div>
</template>
<script>
export default {
  name: 'Dashboard',
  head: {
    title: 'Dashboard',
    meta: [
      {
        hid: 'description',
        name: 'description',
        content: 'description'
      }
    ],
  },
  meta:{
    action: 'read',
    subject: 'Dashboard'
  },
  data: function () {
    return {
       p_width:'400px',
      e1: 'Daily',
      items: ['Daily', 'Monthly', 'Yearly'],
      options: {
        chart: {
          id: 'vuechart-example',
          toolbar: {
            show: false,
          }
        },
        xaxis: {
          categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
          labels: {
            show: false,
          }
        },
        colors: ["#00FF00"],
        yaxis: {
          labels: {
            show: false,
          }
        },
        dataLabels: {
          enabled: false,
        },
        legend: {
          show: false,
        },
      },
      series: [{
        name: 'series-1',
        data: [30, 40, 45, 50, 49, 60, 70, 91]
      }],
      dashboard:{},
    }
  },
  async mounted() {

  },
  created() {

  },
  methods:{

  },
}
</script>
