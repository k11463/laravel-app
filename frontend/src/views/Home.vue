<template>
  <div class="box">
    <v-row style="height: 100%">
      <v-col lg="2"></v-col>
      <v-col lg="8" md="12">
        <v-card>
          <v-card-title style="font-weight: bold">熱門文章</v-card-title>
          <v-carousel hide-delimiters cycle>
            <v-carousel-item
              v-for="(item, i) in items"
              :key="i"
              :src="item.src"
            ></v-carousel-item>
          </v-carousel>
        </v-card>
      </v-col>
      <v-col lg="2"></v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  created() {
    if (window.localStorage.getItem("token") != null) {
      this.$store.dispatch("CheckLogin");
    }
  },
  data() {
    return {
      items: [
        {
          src: "https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg",
        },
        {
          src: "https://cdn.vuetifyjs.com/images/carousel/sky.jpg",
        },
        {
          src: "https://cdn.vuetifyjs.com/images/carousel/bird.jpg",
        },
        {
          src: "https://cdn.vuetifyjs.com/images/carousel/planet.jpg",
        },
      ],
    };
  },
};
</script>

<style lang="scss" scoped>
.box {
  height: 93vh;
  overflow-y: scroll;
}
::-webkit-scrollbar {
  display: none;
}
.bd {
  border: 1px solid #000;
}
</style>