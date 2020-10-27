<template>
  <v-row class="box">
    <v-col lg="3"></v-col>
    <v-col lg="6" md="10" sm="12" cols="12">
      <v-card>
        <!-- top_area   -->
        <v-row>
          <v-col class="ptpb0">
            <v-row>
              <v-col lg="9" class="ptpb0">
                <v-card-title style="margin-top: 1.2%; font-weight: bold"
                  >討論區列表</v-card-title
                >
              </v-col>
              <v-col lg="3">
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
                      @click="$router.push('/create_post')"
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
            <v-row>
              <v-col class="ptpb0">
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
          </v-col>
        </v-row>
        <!-- comment area -->
        <v-row v-if="posts.length > 0">
          <v-col lg="12" style="padding-top: 0; overflow: hidden">
            <div v-for="(post, index) in posts" :key="index">
              <v-card
                link
                class="comments"
                v-if="
                  index >= (page.now - 1) * 6 && index <= (page.now - 1) * 6 + 6
                "
                @click="$router.push('/post?post_id=' + post.post_id)"
              >
                <v-row>
                  <v-col lg="2" md="2" sm="2" cols="3" class="cover">
                    <v-img
                      :src="post.cover"
                      max-height="60"
                      max-width="100"
                    ></v-img>
                  </v-col>
                  <v-col lg="7" md="8" sm="6" cols="5" style="padding-top: 0">
                    <v-row>
                      <v-col
                        lg="12"
                        class="ptpb0"
                        style="
                          font-size: 18px;
                          font-weight: bold;
                          display: flex;
                          align-items: center;
                          height: 38px;
                          overflow: hidden;
                        "
                      >
                        {{ post.title }}
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col
                        lg="12"
                        class="ptpb0"
                        style="height: 48px; overflow: hidden"
                      >
                        {{ post.content }}
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col
                    lg="3"
                    md="2"
                    sm="4"
                    cols="4"
                    class="ptpb0"
                    style="padding-left: 0"
                  >
                    <v-row>
                      <v-col lg="6" class="ptpb0">
                        <v-row style="height: 100%">
                          <v-col lg="12" class="center" style="font-size: 14px">
                            評分 {{ post.score }} / 5
                          </v-col>
                        </v-row>
                      </v-col>
                      <v-col lg="6" class="ptpb0">
                        <v-row>
                          <v-col
                            lg="12"
                            class="center"
                            style="padding-top: 7%; padding-bottom: 7%"
                          >
                            <v-avatar>
                              <img :src="post.user_avatar" />
                            </v-avatar>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col
                            style="
                              display: flex;
                              justify-content: center;
                              padding: 0;
                              font-size: 14px;
                              font-weight: bold;
                            "
                          >
                            {{ post.user }}
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
              </v-card>
            </div>
          </v-col>
        </v-row>
        <v-row v-if="posts.length == 0">
          <v-col lg="12" class="center"> 沒有文章 </v-col>
        </v-row>
        <!-- footer area -->
        <v-row>
          <v-col class="col-lg-12" style="padding-top: 0">
            <div class="page_navigation_area">
              <div class="text-center">
                <v-pagination
                  v-model="page.now"
                  :length="page.total"
                  :total-visible="5"
                  color="teal darken-1"
                ></v-pagination>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-col>
    <v-col lg="3"></v-col>
  </v-row>
</template>

<script>
import { backend_url } from "../global_variable.js";
export default {
  created() {
    if (window.localStorage.getItem("token") != null) {
      this.$store.dispatch("CheckLogin");
    }
    this.GetPosts();
    this.ChangePage();
    console.log(this.posts);
  },
  data() {
    return {
      page: {
        now: 1,
        total: 1,
      },
      posts: [],
    };
  },
  methods: {
    GetPosts() {
      this.axios.post("/posts", { page: this.page.now - 1 }).then((res) => {
        // console.log(res.data);
        if (res.data.totalCounts % 6 > 0) {
          this.page.total = Math.floor(res.data.totalCounts / 6 + 1);
        } else {
          this.page.total = Math.floor(res.data.totalCounts / 6);
        }
        //塞文章資料
        for (let i = 0; i < res.data.posts.length; i++) {
          this.posts.push({
            post_id: res.data.posts[i].pid,
            cover:
              backend_url +
              "/storage/" +
              res.data.posts[i].cover.replace("public/", ""),
            title: res.data.posts[i].title,
            content: res.data.posts[i].content,
            score: res.data.scores[i] == null ? 0 : res.data.scores[i],
            user: res.data.users[i].user_name,
            user_avatar:
              backend_url +
              "/storage/" +
              res.data.users[i].avatar.replace("public/", ""),
          });
        }
      });
    },
    ChangePage() {},
  },
  computed: {
    change_page() {
      return this.page.now;
    },
  },
  watch: {
    change_page() {
      this.ChangePage();
    },
  },
};
</script>

// <style lang="scss" scoped>
.box {
  height: 90vh;
  overflow-y: scroll;
  .comments {
    height: 88px;
    padding-left: 1.6%;
    padding-right: 1.6%;
  }
}
::-webkit-scrollbar {
  display: none;
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