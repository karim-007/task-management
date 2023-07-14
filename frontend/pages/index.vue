<template>

</template>

<script>
    export default {
      head: {
        title: 'Dashboard',
        meta: [
          {
            hid: 'description',
            name: 'description',
            content: 'Badhan description'
          }
        ],
      },
      meta:{
        action: 'read',
        subject: 'Dashboard'
      },
        mounted()
        {
          this.$router.push('/dashboard')
        }
    }
</script>

<style scoped>
</style>
