<template>
  <div class="box">
    <v-row>
      <v-col lg="3"></v-col>
      <v-col lg="6" md="10" sm="12" cols="12">
        <v-card>
          <v-card-title style="font-weight: bold"> 會員資料 </v-card-title>
          <v-divider></v-divider>
          <v-container>
            <v-row>
              <v-col lg="6" cols="12">
                <!-- 會員資料_左邊 -->
                <v-row>
                  <v-col class="center avatar_title" style="padding: 0">
                    會員頭像
                  </v-col>
                </v-row>
                <v-row>
                  <v-col class="center">
                    <v-img
                      :src="GetImage"
                      max-height="240"
                      max-width="240"
                    ></v-img>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="1"></v-col>
                  <v-col
                    class="center"
                    lg="10"
                    md="10"
                    cols="12"
                    style="padding-top: 0; position: relative"
                  >
                    <input
                      type="file"
                      id="input_file"
                      class="input_file"
                      @change="GetImageFile"
                    />
                    <label for="input_file" class="input_file_label">
                      <span class="hint_text">上傳圖片</span>
                      <p class="file_text">{{ imageFile.name }}</p>
                    </label>
                    <v-icon
                      v-if="imageFile.name != '選擇圖片'"
                      class="clear_upload"
                      @click="ClearUpload"
                      >mdi-close</v-icon
                    >
                  </v-col>
                  <v-col lg="1"></v-col>
                </v-row>
                <v-row>
                  <v-col class="center" style="padding: 0">
                    <v-btn @click="UpdateData(2)" width="120">更改頭像</v-btn>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col lg="2"></v-col>
                  <v-col lg="8">
                    <span class="uploadHint"
                      >註：上傳圖片請選擇長寬比例一樣的圖片，如長寬比不相符會截取部分圖片</span
                    >
                  </v-col>
                  <v-col lg="2"></v-col>
                </v-row>
              </v-col>
              <!-- 會員資料_右邊 -->
              <v-col
                lg="6"
                cols="12"
                style="padding-top: 0; padding-bottom: 0; padding-left: 0"
              >
                <div
                  v-for="(info, index) in infos"
                  :key="index"
                  class="right_row"
                >
                  <v-row :style="!info.hasEdit ? 'height: 60px' : null">
                    <v-col lg="3" class="row_title center">{{
                      info.title
                    }}</v-col>
                    <v-col lg="5" class="center">{{ info.text }}</v-col>
                    <v-col lg="4" class="edit_btn center">
                      <div v-if="info.hasEdit" style="float: right">
                        <v-btn icon @click="EditData(index)">
                          <v-icon> mdi-file-restore</v-icon>變更</v-btn
                        >
                      </div>
                    </v-col>
                  </v-row>
                  <v-divider></v-divider>
                </div>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
      <v-col lg="3"></v-col>
    </v-row>
    <!-- 修改視窗 -->
    <v-overlay :value="overlay.open">
      <v-card>
        <v-card-title>修改{{ infos[overlay.editId].title }}</v-card-title>
        <v-divider></v-divider>
        <v-container>
          <!-- 更改會員名稱 -->
          <v-row v-if="overlay.editId == 0">
            <v-col style="padding-bottom: 0px">
              <v-text-field
                v-model="user_name"
                label="新會員名稱"
                placeholder="請輸入新的會員名稱"
                outlined
                @keypress.enter="UpdateData(overlay.editId)"
              ></v-text-field>
            </v-col>
          </v-row>
          <!-- 更改會員信箱 -->
          <div v-if="overlay.editId == 1">
            <v-row>
              <v-col style="padding-bottom: 0px">
                <v-btn
                  style="float: right; margin-top: 8px; margin-left: 12px"
                  @click="SendEmailVerifyMail"
                  >寄送驗證信</v-btn
                >
                <v-text-field
                  v-model="email.text"
                  label="新Email"
                  placeholder="請輸入新的Email"
                  outlined
                  style="float: right"
                  @keypress.enter="UpdateData(overlay.editId)"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-bottom: 0px; padding-top: 0px">
                <v-text-field
                  v-model="email.verify"
                  label="新Email驗證碼"
                  placeholder="請輸入新Email的驗證碼"
                  outlined
                  @keypress.enter="UpdateData(overlay.editId)"
                ></v-text-field>
              </v-col>
            </v-row>
          </div>
          <!-- 更改按鈕 -->
          <v-row>
            <v-col
              class="center"
              style="padding-bottom: 20px; padding-top: 0px"
            >
              <v-btn @click="overlay.open = false">取消變更</v-btn>
            </v-col>
            <v-col
              class="center"
              style="padding-bottom: 20px; padding-top: 0px"
            >
              <v-btn @click="UpdateData(overlay.editId)">確定更改</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-overlay>
    <!-- 提示 -->
    <v-overlay :value="hint.open">
      <v-card width="330" height="48" class="center" style="font-weight: bold">
        {{ hint.text }}
      </v-card>
    </v-overlay>
  </div>
</template>

<script>
export default {
  mounted() {
    if (window.localStorage.getItem("token") != null) {
      this.$store.dispatch("CheckLogin").then((res) => {
        this.infos[0].text = this.$store.state.user.name;
        this.infos[1].text = this.$store.state.user.email;
        this.infos[2].text = this.$store.state.user.phone;
        this.infos[3].text = this.$store.state.user.rank;
        this.infos[4].text = this.$store.state.user.money;
        if (res != undefined) {
          console.log("MemberData -> mounted : " + res);
        }
      });
    }
  },
  data() {
    return {
      imageFile: {
        file: null,
        files: null,
        type: "",
        name: "選擇圖片",
      },
      infos: [
        { title: "會員名稱", text: "", hasEdit: true },
        { title: "Email", text: "", hasEdit: true },
        { title: "會員電話", text: "", hasEdit: false },
        { title: "會員階級", text: "", hasEdit: false },
        { title: "會員錢包", text: "", hasEdit: false },
      ],
      overlay: {
        open: false,
        editId: 1,
      },
      email: {
        text: "",
        verify: "",
      },
      user_name: "",
      hint: {
        text: "",
        open: false,
        interval: null,
        timer: 2,
      },
    };
  },
  methods: {
    EditData(index) {
      this.overlay.editId = index;
      this.overlay.open = true;
    },
    UpdateData(type) {
      // type = 0 名稱, type = 1 信箱, type = 2 圖片
      switch (type) {
        case 0:
          {
            let check = this.NameCheck();
            if (check) {
              let form_data = this.ConvertToFormData("name");
              this.UpdateAxios(form_data).then(() => {
                this.$store.commit("setUserName", this.user_name);
                this.infos[0].text = this.user_name;
                this.user_name = "";
              });
            }
          }
          break;
        case 1:
          {
            let check = this.EmailCheck();
            if (check) {
              let form_data = this.ConvertToFormData("email");
              this.UpdateAxios(form_data).then(() => {
                this.infos[1].text = this.email.text;
                this.email.text = "";
                this.email.verify = "";
              });
            }
          }
          break;
        case 2:
          {
            let check = this.ImageCheck();
            if (check) {
              let form_data = this.ConvertToFormData("img");
              this.UpdateAxios(form_data).then((res) => {
                this.$store.commit(
                  "setAvatar",
                  res.data.path.replace("public/", "")
                );
                this.imageFile.file = null;
                this.imageFile.name = "選擇圖片";
              });
            }
          }
          break;
      }
    },
    UpdateAxios(data) {
      return new Promise((resolve) => {
        this.axios
          .post("/user/update", data)
          .then((res) => {
            this.Hint(res.data.msg);
            this.overlay.open = false;
            resolve(res);
          })
          .catch((err) => {
            if (err.response.status == 402) {
              this.Hint(err.response.data.msg);
            }
            if (err.response.data.msg == "找不到使用者") {
              window.localStorage.removeItem("token");
              this.Hint("此帳號已從別地方登入");
            }
          });
      });
    },
    ConvertToFormData(type) {
      const form_data = new FormData();
      form_data.append("type", type);
      form_data.append("token", window.localStorage.getItem("token"));
      if (type == "name") {
        form_data.append("user_name", this.user_name);
      } else if (type == "email") {
        form_data.append("email", this.email.text);
        form_data.append("verify", this.email.verify);
      } else if (type == "img") {
        form_data.append("avatar", this.imageFile.files);
        form_data.append(
          "storePath",
          "public/avatars/" + this.$store.state.user.email
        );
      }
      return form_data;
    },
    SendEmailVerifyMail() {
      let check = this.EmailCheck();
      if (check) {
        this.$store
          .dispatch("SendEmailVerifyMail", this.email.text)
          .then((res) => {
            this.hint.timer = 4;
            this.Hint(res);
          })
          .catch((err) => {
            this.Hint(err);
          });
      }
    },
    NameCheck() {
      if (this.user_name == "") {
        this.user_name = "智善的奴隸";
        return true;
      } else {
        if (this.user_name.length > 10) {
          this.Hint("名稱不可超過10個字元");
          return false;
        } else {
          return true;
        }
      }
    },
    EmailCheck() {
      if (this.email.text == "") {
        this.Hint("Email不可為空");
        return false;
      } else {
        let match = this.email.text.match(
          /^([A-Za-z0-9_.-]+)@([da-z.-]+).([a-z.]{2,6})$/
        );
        if (match != null) {
          return true;
        } else {
          this.Hint("Email格式錯誤");
          return false;
        }
      }
    },
    ImageCheck() {
      if (this.imageFile.file == null) {
        this.Hint("沒有選擇圖片");
        return false;
      } else {
        if (this.imageFile.files.type.match(/image\//) != null) {
          return true;
        } else {
          this.Hint("只能選擇圖片檔案");
          return false;
        }
      }
    },
    Hint(text, logout) {
      this.hint.text = text;
      this.hint.open = true;
      this.hint.interval = window.setInterval(() => {
        this.hint.timer--;
        if (this.hint.timer <= 0) {
          window.clearInterval(this.hint.interval);
          this.hint.timer = 0;
          this.hint.open = false;
          if (logout) {
            window.location.reload();
          }
        }
      }, 1000);
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
        return this.$store.state.user.avatar;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.box {
  height: 90vh;
  overflow-y: scroll;
  .avatar_title {
    font-size: 20px;
    font-weight: bold;
  }
  .uploadHint {
    width: 34%;
    font-size: 14px;
    font-weight: bold;
    color: red;
  }
  .right_row {
    margin-top: 26px;
    .row_title {
      font-weight: bold;
    }
  }
  .clear_upload {
    position: absolute;
    right: 0px;
    top: 6px;
    cursor: pointer;
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
</style>