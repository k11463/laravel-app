<template>
  <v-app>
    <Header />
    <v-container fluid>
      <router-view></router-view>
    </v-container>
  </v-app>
</template>

<script>
import Header from "@/components/Header";
export default {
  name: "App",
  components: {
    Header,
  },
};
</script>

<style lang="scss" scoped>
</style>
