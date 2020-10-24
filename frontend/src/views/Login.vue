<template>
  <div class="box">
    <v-row style="height: 100%" class="center">
      <v-col lg="4"></v-col>
      <v-col lg="4" md="6" sm="12" cols="12" class="box_mid">
        <v-card>
          <v-card-title style="font-weight: bold"> 會員登入 </v-card-title>
          <v-divider></v-divider>
          <v-container>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0"
                ><v-text-field
                  v-model="email"
                  label="E-mail"
                  placeholder="請輸入信箱"
                  @keypress.enter="Login"
                  outlined
                ></v-text-field
              ></v-col>
            </v-row>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0"
                ><v-text-field
                  v-model="password"
                  label="密碼"
                  type="password"
                  placeholder="請輸入密碼"
                  @keypress.enter="Login"
                  outlined
                ></v-text-field
              ></v-col>
            </v-row>
            <v-row>
              <v-col
                lg="4"
                style="padding-top: 0; display: flex; align-items: center"
              >
                <span>還沒有會員? 哈哈哈 笑你</span>
              </v-col>
              <v-col lg="6" style="padding-top: 0">
                <v-btn class="btn" style="float: left" to="/signup"
                  >點我加入會員</v-btn
                >
              </v-col>
              <v-col lg="2" style="padding-top: 0">
                <v-btn
                  width="96"
                  class="btn"
                  color="blue darken-1"
                  dark
                  style="float: right"
                  @click="Login"
                >
                  登入
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
      <v-col lg="4"></v-col>
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
export default {
  created() {
    if (window.localStorage.getItem("token") != null) {
      this.$router.push("/");
    }
  },
  data() {
    return {
      email: "",
      password: "",
      hint: {
        text: "",
        open: false,
        timer: 2,
        interval: null,
      },
    };
  },
  methods: {
    Login() {
      if (this.email != "" && this.password != "") {
        this.axios
          .post("/login", {
            email: this.email,
            password: this.password,
          })
          .then((res) => {
            window.localStorage.setItem("token", res.data.token);
            this.Hint("登入成功", true);
          })
          .catch((err) => {
            console.log(err);
            this.Hint(err.response.data.msg);
          });
      } else {
        this.Hint("帳號或密碼不可為空");
      }
    },
    Hint(text, loginSuccess) {
      this.hint.text = text;
      this.hint.open = true;
      this.hint.interval = window.setInterval(() => {
        this.hint.timer--;
        if (this.hint.timer <= 0) {
          window.clearInterval(this.hint.interval);
          this.hint.timer = 0;
          this.hint.open = false;
          if (loginSuccess) {
            this.$router.back();
          }
        }
      }, 1000);
    },
  },
};
</script>

<style lang="scss" scoped>
.box {
  height: 90vh;
  overflow-y: scroll;
}
::-webkit-scrollbar {
  display: none;
}

.hint_box {
  border: 1px solid;
  height: 100%;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.473);
  z-index: 15;
}
.center {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>