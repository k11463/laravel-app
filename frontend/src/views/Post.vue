<template>
  <div class="box">
    <v-row>
      <v-col lg="3"></v-col>
      <v-col lg="6" md="8" sm="10" cols="12">
        <v-container>
          <v-card>
            <v-row>
              <v-col lg="6" class="ptpb0">
                <v-card-title style="padding-bottom: 0">{{
                  post.title
                }}</v-card-title>
              </v-col>
              <v-col
                lg="6"
                class="ptpb0"
                v-if="$store.state.user.email == post.user_email"
              >
                <v-btn
                  class="edit_button"
                  @click="
                    $router.push('/update_post?post_id=' + $route.query.post_id)
                  "
                  >編輯文章</v-btn
                >
              </v-col>
            </v-row>
            <v-container>
              <!-- 文章資訊 -->
              <v-row>
                <v-col class="post_infos">
                  <v-row style="height: 100%; overflow: hidden">
                    <v-col lg="3" cols="12" class="ptpb0">
                      <v-row style="height: 100%">
                        <v-col
                          lg="4"
                          cols="4"
                          class="center"
                          style="padding-right: 0"
                        >
                          <v-avatar>
                            <v-img :src="post.user_avatar"></v-img>
                          </v-avatar>
                        </v-col>
                        <v-col
                          lg="8"
                          cols="4"
                          class="center"
                          style="
                            display: flex;
                            align-items: center;
                            padding-right: 0;
                            font-size: 14px;
                          "
                          >{{ post.user_name }}</v-col
                        >
                      </v-row>
                    </v-col>
                    <v-col lg="9" class="ptpb0">
                      <v-row style="height: 100%">
                        <v-col lg="6" style="padding: 0" class="center">
                          <v-row style="width: 100%; height: 100%">
                            <v-col
                              class="ptpb0 center"
                              style="width: 100%; font-size: 14px"
                            >
                              創建時間
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col style="font-size: 14px">
                              {{ post.created }}
                            </v-col>
                          </v-row>
                        </v-col>
                        <v-col lg="6" style="padding: 0" class="center">
                          <v-row style="width: 100%; height: 100%">
                            <v-col
                              class="ptpb0 center"
                              style="width: 100%; font-size: 14px"
                            >
                              更新時間
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col style="font-size: 14px">
                              {{ post.updated }}
                            </v-col>
                          </v-row>
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
              <!-- 文章內容 -->
              <v-row class="content_area">
                <v-col> {{ post.content }} </v-col>
              </v-row>
              <v-row class="content_footer" v-if="$store.state.user.login">
                <v-col
                  lg="5"
                  class="center"
                  style="height: 100%; overflow: hidden"
                  >覺得文章怎麼樣? 給個評分吧</v-col
                >
                <v-col lg="7" class="center">
                  <v-rating v-model="post.score" hover></v-rating>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-container>
      </v-col>
      <v-col lg="3"></v-col>
    </v-row>
  </div>
</template>

<script>
import { backend_url } from "../global_variable.js";
export default {
  created() {
    if (window.localStorage.getItem("token") != null) {
      this.$store.dispatch("CheckLogin");
    }
    this.GetPost();
  },
  data() {
    return {
      post: {
        user_avatar: "",
        user_email: "",
        user_name: "",
        created: "",
        updated: "",
        title: "",
        content: "",
        score: 0,
      },
    };
  },
  methods: {
    DateController(time) {
      return time
        .replace("-", "/")
        .replace(".000000Z", "")
        .replace("T", " ")
        .replace("-", "/");
    },
    GetPost() {
      this.axios
        .post("/post/" + this.$route.query.post_id, {
          token:
            window.localStorage.getItem("token") == null
              ? "logout"
              : window.localStorage.getItem("token"),
        })
        .then((res) => {
          this.post.user_avatar =
            backend_url +
            "/storage/" +
            res.data.user.avatar.replace("public/", "");
          this.post.user_email = res.data.user.email;
          this.post.user_name = res.data.user.user_name;
          this.post.created = this.DateController(res.data.post.created_at);
          this.post.updated = this.DateController(res.data.post.updated_at);
          this.post.title = res.data.post.title;
          this.post.content = res.data.post.content;
          this.post.score = res.data.score;
        });
    },
  },
  watch: {
    score() {
      this.axios
        .post("/post/score", {
          token: window.localStorage.getItem("token"),
          post_id: this.$route.query.post_id,
          score: this.score,
        })
        .then((res) => {
          console.log(res.data);
        })
        .catch((err) => {
          if (err.response.data.msg == "找不到使用者，請重新登入") {
            this.$router.push("/login");
          }
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.box {
  height: 90vh;
  overflow-y: scroll;
  .edit_button {
    position: absolute;
    top: 12px;
    right: 16px;
  }
  .post_infos {
    padding-top: 0;
    padding-bottom: 0;
    height: 76px;
    border-top: 1px solid rgba(128, 128, 128, 0.3);
    border-bottom: 1px solid rgba(128, 128, 128, 0.3);
  }
  .content_area {
    height: 600px;
    overflow: auto;
  }
  .content_footer {
    border-top: 1px solid rgba(128, 128, 128, 0.3);
    border-bottom: 1px solid rgba(128, 128, 128, 0.3);
    height: 68px;
  }
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
  justify-content: center;
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