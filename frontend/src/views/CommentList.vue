<template>
  <v-row class="box">
    <v-col class="col-lg-3"></v-col>
    <v-col class="col-lg-6 col-sm-12">
      <v-card class="comment_box">
        <!-- top_area   -->
        <v-row>
          <v-col class="col-lg-12 ptpb0">
            <v-row>
              <v-col class="col-lg-9 ptpb0">
                <v-card-title style="margin-top: 1.2%; font-weight: bold"
                  >討論區列表</v-card-title
                >
              </v-col>
              <v-col class="col-lg-3">
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      fab
                      style="
                        width: 40px;
                        height: 40px;
                        float: right;
                        margin-right: 16%;
                      "
                      v-bind="attrs"
                      v-on="on"
                    >
                      <v-icon style="margin-left: 12%; margin-top: 8%"
                        >mdi-playlist-plus</v-icon
                      >
                    </v-btn>
                  </template>
                  <span>新增文章</span>
                </v-tooltip>
              </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-tabs
              fixed-tabs
              background-color="teal darken-1"
              dark
              style="margin-top: 2%"
            >
              <v-tab>綜合討論</v-tab>
              <v-tab>幹話專區</v-tab>
              <v-tab>公幹專區</v-tab>
            </v-tabs>
          </v-col>
        </v-row>
        <!-- comment area -->
        <v-row>
          <v-col class="col-lg-12" style="padding-top: 0">
            <v-card v-for="i in 7" :key="i" link class="comments">
              <v-row style="height: 100%">
                <v-col class="col-lg-2 cover"> 封面圖 </v-col>
                <v-col class="col-lg-7 ptpb0" style="height: 100%">
                  <v-row style="height: 40%">
                    <v-col
                      class="col-lg-12 ptpb0"
                      style="
                        font-size: 18px;
                        font-weight: bold;
                        display: flex;
                        align-items: center;
                      "
                    >
                      文章標題
                    </v-col>
                  </v-row>
                  <v-row style="height: 60%">
                    <v-col class="col-lg-12 ptpb0"> 文章部分內容 </v-col>
                  </v-row>
                </v-col>
                <v-col class="col-lg-3 ptpb0" style="padding-left: 0">
                  <v-row style="height: 100%">
                    <v-col class="col-lg-6 ptpb0">
                      <v-row>
                        <v-col class="col-lg-12 center">
                          <v-icon>mdi-star</v-icon>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col class="col-lg-12 center"> 0.0 / 5.0 </v-col>
                      </v-row>
                    </v-col>
                    <v-col class="col-lg-6 ptpb0">
                      <v-row>
                        <v-col
                          class="col-lg-12 center"
                          style="padding-top: 7%; padding-bottom: 7%"
                        >
                          <v-avatar>
                            <img
                              src="https://cdn.vuetifyjs.com/images/john.jpg"
                              alt="John"
                            />
                          </v-avatar>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col
                          class="col-lg-12"
                          style="
                            display: flex;
                            justify-content: center;
                            padding: 0;
                          "
                        >
                          PO文者
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
        <!-- footer area -->
        <v-row>
          <v-col class="col-lg-12" style="padding-top: 0">
            <div class="page_navigation_area">
              <div class="text-center">
                <v-pagination
                  v-model="page"
                  :length="9"
                  :total-visible="5"
                  color="teal darken-1"
                ></v-pagination>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-col>
    <v-col class="col-lg-3"></v-col>
  </v-row>
</template>

<script>
export default {
  created() {
    if (window.localStorage.getItem("token") != null) {
      this.$store.dispatch("checkLogin", window.localStorage.getItem("token"));
    }
  },
  data() {
    return {
      page: 1,
    };
  },
};
</script>

// <style lang="scss" scoped>
.box {
  height: 93vh;
  overflow-y: scroll;
  .comment_box {
    height: 100%;
    .comments {
      height: 10vh;
      padding-left: 1.6%;
      padding-right: 1.6%;
    }
  }
}
::-webkit-scrollbar {
  display: hidden;
}
.center {
  display: flex;
  align-items: center;
  justify-content: center;
}
.ptpb0 {
  padding-top: 0;
  padding-bottom: 0;
}
</style>