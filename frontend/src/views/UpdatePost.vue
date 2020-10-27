<template>
  <div class="box">
    <v-row>
      <v-col lg="3"></v-col>
      <v-col lg="6">
        <v-card>
          <v-card-title style="font-weight: bold">更新文章</v-card-title>
          <v-divider></v-divider>
          <v-container fluid>
            <v-row class="left_row center">
              <v-col lg="8" md="10" sm="12" cols="12">
                <v-row style="height: 88px">
                  <v-col lg="3" class="form_title">上傳封面</v-col>
                  <v-col lg="9" style="position: relative">
                    <input
                      type="file"
                      id="input_file"
                      class="input_file"
                      @change="GetImageFile"
                    />
                    <label for="input_file" class="input_file_label">
                      <p
                        class="file_text"
                        style="
                          border: 1px solid gray;
                          border-radius: 4px;
                          width: 100%;
                          height: 40px;
                        "
                      >
                        {{ imageFile.name }}
                      </p>
                    </label>
                    <v-icon
                      v-if="imageFile.name != '選擇圖片'"
                      class="clear_upload"
                      @click="ClearUpload"
                      >mdi-close</v-icon
                    >
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="3" class="form_title"> 文章標題 </v-col>
                  <v-col lg="9" class="ptpb0">
                    <v-text-field
                      placeholder="請輸入文章標題"
                      v-model="title"
                      outlined
                    >
                    </v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="3" class="form_title"> 選擇分類 </v-col>
                  <v-col lg="9" class="ptpb0">
                    <v-select
                      v-model="select"
                      :items="selects"
                      item-text="name"
                      item-value="value"
                      :label="select.name"
                      solo
                      return-object
                    ></v-select>
                  </v-col>
                </v-row>
              </v-col>
              <v-col lg="4">
                <v-row>
                  <v-col
                    class="ptpb0 center"
                    style="font-size: 20px; font-weight: bold"
                  >
                    封面圖預覽
                  </v-col>
                </v-row>
                <v-row>
                  <v-col class="center">
                    <v-img
                      :src="GetImage"
                      max-width="180"
                      max-height="180"
                      height="180"
                      width="180"
                    ></v-img>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
            <!-- 文章內容 -->
            <v-row>
              <v-col
                class="ptpb0"
                style="font-size: 20px; font-weight: bold; padding-left: 36px"
              >
                文章內容
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-bottom: 0">
                <v-textarea
                  name="input-7-1"
                  outlined
                  placeholder="請輸入文章內容"
                  v-model="content"
                  class="content"
                  auto-grow
                  rows="16"
                ></v-textarea>
              </v-col>
            </v-row>
            <v-row>
              <v-col lg="2" class="center">
                <v-btn @click="$router.back()">取消更新</v-btn>
              </v-col>
              <v-col lg="8"></v-col>
              <v-col lg="2" class="center">
                <v-btn @click="UpdatePost">更新文章</v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
      <v-col lg="3"></v-col>
    </v-row>
    <!-- 提示 -->
    <v-overlay :value="hint.open">
      <v-card width="330" height="48" class="center" style="font-weight: bold">
        {{ hint.text }}
      </v-card>
    </v-overlay>
  </div>
</template>

<script>
import { backend_url } from "../global_variable.js";
export default {
  created() {
    this.$store.dispatch("CheckLogin");
    this.axios.get("/post/" + this.$route.query.post_id).then((res) => {
      this.title = res.data.post.title;
      this.selects.forEach((val) => {
        if (val.value == res.data.post.class) {
          this.select.name = val.name;
          this.select.value = val.value;
        }
      });
      this.content = res.data.post.content;
      this.imageFile.url = res.data.post.cover;
    });
    console.log(this.select);
  },
  data() {
    return {
      imageFile: {
        file: null,
        files: null,
        name: "選擇圖片",
        url: "",
      },
      title: "",
      select: { name: "", value: "" },
      selects: [{ name: "綜合討論", value: "general" }],
      content: "",
      hint: {
        text: "",
        open: false,
        interval: null,
        timer: 2,
      },
    };
  },
  methods: {
    UpdatePost() {
      let check = this.FormCheck();
      let image_check = this.ImageCheck();
      if (check && image_check) {
        let formData = this.CovertToFormData();
        this.axios
          .post("/post/update", formData)
          .then((res) => {
            console.log(res.data);
            this.$router.back();
          })
          .catch((err) => {
            console.log("create post : " + err);
          });
      }
    },
    CovertToFormData() {
      let formData = new FormData();
      if (this.imageFile.files == null) {
        formData.append("cover", "");
      } else {
        formData.append("cover", this.imageFile.files);
      }
      formData.append(
        "storePath",
        "public/covers/" + this.$store.state.user.email
      );
      formData.append("title", this.title);
      formData.append("class", this.select.value);
      formData.append("content", this.content);
      formData.append("token", window.localStorage.getItem("token"));
      formData.append("post_id", this.$route.query.post_id);
      return formData;
    },
    FormCheck() {
      if (this.title != "" && this.select.value != "" && this.content != "") {
        return true;
      } else {
        this.Hint("除了封面以外都必須填寫或選擇!!");
        return false;
      }
    },
    ImageCheck() {
      if (this.imageFile.file != null) {
        if (this.imageFile.files.type.match(/image\//) != null) {
          return true;
        } else {
          this.Hint("只能選擇圖片檔案");
          return false;
        }
      } else {
        return true;
      }
    },
    GetImageFile(e) {
      this.imageFile.files = e.target.files[0];
      this.imageFile.name = this.imageFile.files.name;
      let fileReader = new FileReader();
      fileReader.readAsDataURL(this.imageFile.files);
      fileReader.onload = () => {
        this.imageFile.file = fileReader.result;
      };
    },
    Hint(text, createPostSuccess) {
      this.hint.text = text;
      this.hint.open = true;
      this.hint.interval = window.setInterval(() => {
        this.hint.timer--;
        if (this.hint.timer <= 0) {
          window.clearInterval(this.hint.interval);
          this.hint.timer = 0;
          this.hint.open = false;
          if (createPostSuccess) {
            this.$router.back();
          }
        }
      }, 1000);
    },
    ClearUpload() {
      this.imageFile.name = "選擇圖片";
      this.imageFile.files = null;
      this.imageFile.file = null;
      document.getElementById("input_file").value = "";
    },
  },
  computed: {
    GetImage() {
      if (this.imageFile.file != null) {
        return this.imageFile.file;
      } else {
        return (
          backend_url + "/storage/" + this.imageFile.url.replace("public/", "")
        );
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.box {
  height: 90vh;
  overflow-y: scroll;
  .left_row {
    .form_title {
      display: flex;
      justify-content: center;
      font-size: 20px;
      padding-top: 14px;
      font-weight: bold;
    }
    .clear_upload {
      position: absolute;
      right: -12px;
      top: 20px;
      cursor: pointer;
    }
  }
}
::-webkit-scrollbar {
  display: none;
}
.content {
  height: 400px;
  overflow-y: scroll;
}

.bd {
  border: 1px solid #000;
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