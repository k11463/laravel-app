<template>
  <div class="box">
    <div class="mask"></div>
    <v-row>
      <v-col lg="4"></v-col>
      <v-col lg="4" md="7" sm="9" cols="12">
        <v-row>
          <v-col
            class="center"
            style="font-size: 72px; font-weight: bold; margin-top: 6%"
            >就是要404啦 怎樣</v-col
          >
        </v-row>
        <v-row>
          <v-col style="margin-top: 6%">
            <v-img
              src="https://scontent.ftpe8-4.fna.fbcdn.net/v/t31.0-0/c0.20.206.206a/p206x206/1292960_632308443458868_142271370_o.jpg?_nc_cat=104&ccb=2&_nc_sid=da31f3&_nc_ohc=LaaOc3BRLdcAX-ujrAZ&_nc_ht=scontent.ftpe8-4.fna&tp=27&oh=2bf6214496ea5b47d0812424fe02deda&oe=5FB79233"
            ></v-img>
          </v-col>
        </v-row>
      </v-col>
      <v-col lg="4"></v-col>
    </v-row>
  </div>
</template>

<style lang="scss" scoped>
.box {
  background-color: white;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 998;
  .mask {
    background-color: rgba(0, 0, 0, 0.322);
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 999;
  }
}

.center {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>