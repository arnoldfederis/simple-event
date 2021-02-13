<template>
  <div>
    <alert ref="alert"></alert>
    <Navbar></Navbar>
    <b-container>
      <router-view></router-view>
    </b-container>
  </div>
</template>

<script>
import Navbar from "./Navbar";

export default {
  components: { Navbar }
}
</script>
