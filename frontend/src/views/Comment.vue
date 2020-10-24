<template>
  <div class="box">
    <v-row>
      <v-col class="col-lg-3"></v-col>
      <v-col class="col-lg-6"> 123 </v-col>
      <v-col class="col-lg-3"></v-col>
    </v-row>
  </div>
</template>

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