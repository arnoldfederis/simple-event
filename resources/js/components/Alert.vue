<template>
  <b-alert
      class="default-alert"
      fade
      :show="alert.show"
      dismissible
      :variant="alert.variant"
      @dismissed="!alert.show"
  >
    {{ alert.message }}
  </b-alert>
</template>

<script>
export default {
  data() {
    return {
      alert: {
        message: 'Event successfully saved.',
        variant: 'success',
        show: false
      }
    }
  },

  mounted() {
    this.$root.$on('alert', (object) => {
      this.alert = Object.assign(this.alert, object)
      setTimeout(() => this.alert.show = false, 2000)
    })
  }
}
</script>

<style scoped>
  .default-alert {
    position: absolute;
    right: 15px;
    top: 30px;
    z-index: 100;
  }
</style>
