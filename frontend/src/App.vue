<template>
  <v-app>
    <Header @clickMenuValue="MenuValue"/>
    <Signup :open="openSignup" v-if="openSignup" @close="CloseSignup" />
    <router-view></router-view>
  </v-app>
</template>

<script>
import Header from '@/components/Header';
import Signup from '@/views/Signup';
export default {
  name: 'App',
  components: {
    Header,
    Signup,
  },
  data() {
    return {
      openSignup: false
    }
  },
  methods: {
    MenuValue(value) {
      switch(value) {
        case 0:
          this.openSignup = true;
          break;
      }
    },
    CloseSignup(){
      this.openSignup = false;
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
