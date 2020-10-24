<template>
  <div class="box">
    <v-row>
      <v-col></v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cover: null,
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
.center {
  display: flex;
  align-items: center;
}
.title {
  font-size: 20px;
  font-weight: bold;
}
.ptpb0 {
  padding-top: 0;
  padding-bottom: 0;
}
</style>