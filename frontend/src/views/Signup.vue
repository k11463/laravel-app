<template>
  <div class="box">
    <v-row style="height: 100%" class="center">
      <v-col lg="4"></v-col>
      <v-col lg="4" md="8" sm="12" cols="12">
        <v-card>
          <v-card-title style="padding-bottom: 0; font-weight: bold"
            >會員註冊</v-card-title
          >
          <v-divider></v-divider>
          <v-container>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0">
                <v-text-field
                  label="會員名稱"
                  placeholder="會員名稱最大不可超過10個字元，此欄位可不必填寫"
                  v-model="form.user_name"
                  @keypress.enter="Signup"
                  outlined
                ></v-text-field>
                <div class="error_log">
                  {{ errors.user_name }}
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0" lg="9" md="9">
                <v-text-field
                  label="Email"
                  placeholder="請依照信箱格式填寫"
                  v-model="form.email"
                  @keypress.enter="Signup"
                  outlined
                ></v-text-field>
                <div class="error_log">
                  {{ errors.email }}
                </div>
              </v-col>
              <v-col
                style="display: flex; justify-content: center"
                lg="3"
                md="3"
              >
                <v-btn @click="SendEmailVerify" :disabled="emailLoading.loading"
                  ><div v-if="!emailLoading.loading">發送驗證信</div>
                  <div v-if="emailLoading.loading">
                    {{ emailLoading.loadingTimer }}
                  </div>
                </v-btn>
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0">
                <v-text-field
                  label="Email驗證碼"
                  placeholder="請填寫信箱收到的驗證碼"
                  v-model="form.verify_code"
                  @keypress.enter="Signup"
                  outlined
                ></v-text-field>
                <div class="error_log">
                  {{ errors.verify_code }}
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0">
                <v-text-field
                  :type="passwordType"
                  label="會員密碼"
                  placeholder="密碼至少要有一個大寫英文，且必須介於6-16字元之間"
                  v-model="form.password"
                  @keypress.enter="Signup"
                  outlined
                  :append-icon="readPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  @click:append="ReadPassword"
                ></v-text-field>
                <div class="error_log">
                  {{ errors.password }}
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-top: 0; padding-bottom: 0">
                <v-text-field
                  label="會員電話(手機)"
                  placeholder="請依照手機格式填寫，格式：0800-092000。此欄位可不必填寫"
                  v-model="form.phone"
                  @keypress.enter="Signup"
                  outlined
                ></v-text-field>
                <div class="error_log">
                  {{ errors.phone }}
                </div>
              </v-col>
            </v-row>
            <v-row>
              <v-col style="padding-top: 0">
                <v-btn
                  color="blue darken-1"
                  dark
                  style="float: right"
                  @click="Signup"
                  >註冊會員</v-btn
                >
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
      this.$router.push("/home");
    }
  },
  data() {
    return {
      form: {
        user_name: "",
        email: "",
        verify_code: "",
        password: "",
        phone: "",
      },
      readPassword: false,
      passwordType: "password",
      emailLoading: {
        loading: false,
        loadingTimer: 60,
        loadingInterval: null,
      },
      loading: false,
      errors: {
        user_name: "",
        email: "",
        password: "",
        phone: "",
        verify_code: "",
      },
      hint: {
        text: "",
        open: false,
        timer: 5,
        interval: null,
      },
    };
  },
  watch: {
    user_name() {
      this.errors.user_name = "";
    },
    email() {
      this.errors.email = "";
    },
    verify_code() {
      this.errors.verify_code = "";
    },
    password() {
      this.errors.password = "";
    },
    phone() {
      this.errors.phone = "";
    },
  },
  computed: {
    // deep watch
    user_name() {
      return this.form.user_name;
    },
    email() {
      return this.form.email;
    },
    verify_code() {
      return this.form.verify_code;
    },
    password() {
      return this.form.password;
    },
    phone() {
      return this.form.phone;
    },
  },
  methods: {
    Signup() {
      let name_check = this.NameCheck();
      let email_check = this.EmailCheck();
      let password_check = this.PasswordCheck();
      let phone_check = this.PhoneCheck();
      if (name_check && email_check && password_check && phone_check) {
        this.axios
          .post("/user", this.form)
          .then((res) => {
            this.loading = true;
            this.Hint(res.data.msg, true);
          })
          .catch((err) => {
            if (err.response.status == 422) {
              if (err.response.data.errors.user_name != undefined) {
                this.errors.user_name = err.response.data.errors.user_name[0];
              }
              if (err.response.data.errors.email != undefined) {
                this.errors.email = err.response.data.errors.email[0];
              }
              if (err.response.data.errors.password != undefined) {
                this.errors.password = err.response.data.errors.password[0];
              }
            } else if (err.response.status == 403) {
              this.errors.email = err.response.data.msg;
            } else {
              this.errors.verify_code = err.response.data.msg;
            }
          });
      }
    },
    SendEmailVerify() {
      let check = this.EmailCheck();
      if (check) {
        this.VerifyButtonReload();
        this.axios
          .post("/email", {
            email: this.form.email,
          })
          .then((res) => {
            this.Hint(res.data.msg);
          })
          .catch((err) => {
            this.errors.email = err.response.data.msg;
            this.emailLoading.loading = false;
          });
      }
    },
    VerifyButtonReload() {
      this.emailLoading.loading = true;
      this.emailLoading.loadingInterval = window.setInterval(() => {
        this.emailLoading.loadingTimer--;
        if (this.emailLoading.loadingTimer <= 0) {
          window.clearInterval(this.email.loadingInterval);
          this.emailLoading.loadingTimer = 60;
          this.emailLoading.loading = false;
        }
      }, 1000);
    },
    Hint(text, SignupSuccess) {
      this.hint.text = text;
      this.hint.open = true;
      this.hint.interval = window.setInterval(() => {
        this.hint.timer--;
        if (this.hint.timer <= 0) {
          window.clearInterval(this.hint.interval);
          this.hint.timer = 0;
          this.hint.open = false;
          if (SignupSuccess) {
            this.$router.back();
          }
        }
      }, 1000);
    },
    NameCheck() {
      if (this.form.user_name == "") {
        this.form.user_name == "智善的奴隸";
        return true;
      } else {
        if (this.form.user_name.length > 10) {
          this.errors.user_name = "會員名稱不可超過10個字元";
          return false;
        } else {
          return true;
        }
      }
    },
    EmailCheck() {
      if (this.form.email == "") {
        this.errors.email = "請填寫Email";
        return false;
      } else {
        if (
          this.form.email.match(
            /^([A-Za-z0-9_.-]+)@([da-z.-]+).([a-z.]{2,6})$/
          ) == null
        ) {
          this.errors.email = "信箱格式錯誤";
          return false;
        } else {
          return true;
        }
      }
    },
    PasswordCheck() {
      if (this.form.password == "") {
        this.errors.password = "請填寫密碼";
        return false;
      } else {
        if (this.form.password.length >= 6 && this.form.password.length <= 16) {
          if (this.form.password.match(/(?=.*[A-Z])(^[A-Za-z0-9]*$)/) != null) {
            return true;
          } else {
            this.errors.password =
              "密碼至少要有一個大寫英文，並且不能有特殊符號";
            return false;
          }
        } else {
          this.errors.password = "密碼必須介於6-16字元之間";
          return false;
        }
      }
    },
    PhoneCheck() {
      if (this.form.phone != "") {
        if (this.form.phone.match(/\d{4}-\d{6}/) == null) {
          this.errors.phone = "電話號碼格式錯誤，格式請參照0800-092000";
          return false;
        } else {
          return true;
        }
      } else {
        return true;
      }
    },
    ReadPassword() {
      this.readPassword = !this.readPassword;
      if (this.readPassword) {
        this.passwordType = "text";
      } else {
        this.passwordType = "password";
      }
    },
  },
};
</script>

<style lang="scss" scoped>
* {
  @import "~vuetify/dist/vuetify.min";
}
.box {
  height: 90vh;
  overflow-y: scroll;
  .error_log {
    position: absolute;
    top: 66%;
    color: red;
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
</style>