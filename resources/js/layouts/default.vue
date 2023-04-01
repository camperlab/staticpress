<template>
  <div>
    <nav-bar></nav-bar>
  </div>
  <div class="container">
    <router-view />
  </div>
</template>
<script setup>
import NavBar from "@/components/NavBar.vue";
</script>