<template>
  <div class="box">
    <v-app-bar
      absolute
      color="#fcb69f"
      dark
      src="https://www.94ish.me/usr/uploads/1970/01/2678585583.jpg"
    >
      <template v-slot:img="{ props }">
        <v-img
          v-bind="props"
          gradient="to top right, rgba(19,84,122,.5), rgba(128,208,199,.8)"
        ></v-img>
      </template>

      <v-btn icon @click="leftNavigation = !leftNavigation">
        <v-icon>mdi-format-list-bulleted</v-icon>
      </v-btn>

      <v-toolbar-title class="title_box" style="width: 90px">
        <v-btn icon class="title_btn" to="/" style="font-weight: bold">
          智善之家
        </v-btn>
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-menu offset-y>
        <template v-slot:activator="{ attrs, on }">
          <v-btn
            icon
            v-bind="attrs"
            v-on="on"
            :style="$store.state.user.login ? 'width:100px' : null"
          >
            <v-icon>mdi-account</v-icon>
            <div v-if="$store.state.user.login">
              {{ $store.state.user.name }}
            </div>
          </v-btn>
        </template>

        <v-list v-if="!$store.state.user.login">
          <v-list-item
            link
            v-for="(menu, index) in account_menus[0]"
            :key="index"
            @click="ClickMenu(menu.directTo)"
          >
            <v-icon class="list_icon">{{ menu.icon }}</v-icon>
            <v-list-item-title v-text="menu.title"></v-list-item-title>
          </v-list-item>
        </v-list>

        <v-list v-if="$store.state.user.login">
          <v-list-item>
            <v-icon class="list_icon">mdi-cash-usd</v-icon>
            999999
          </v-list-item>

          <v-list-item
            link
            v-for="(menu, index) in account_menus[1]"
            :key="index"
            @click="ClickMenu(menu.directTo)"
          >
            <v-icon class="list_icon">{{ menu.icon }}</v-icon>
            <v-list-item-title v-text="menu.title"></v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer v-model="leftNavigation" absolute temporary>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title style="font-weight: bold"
            >智善之家</v-list-item-title
          >
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list nav dense class="list">
        <v-list-item-group v-model="navBarItemIndex" color="orange darken-4">
          <v-list-item
            v-for="infos in navigationInfos"
            :key="infos.title"
            link
            @click="ClickMenu(infos.directTo)"
          >
            <v-list-item-icon>
              <v-icon>{{ infos.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ infos.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
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
    this.navigationInfos.forEach((val, index) => {
      if ("http://localhost:8080" + val.directTo == window.location.href) {
        this.navBarItemIndex = index;
      }
    });
  },
  data() {
    return {
      account_menus: [
        [{ title: "會員登入", icon: "mdi-account", directTo: "/login" }],
        [
          { title: "登出", icon: "mdi-account", directTo: "/logout" },
          {
            title: "會員資料",
            icon: "mdi-account-edit",
            directTo: "/memberData",
          },
          { title: "個人資料", icon: "mdi-account-search", directTo: "/" },
          { title: "會員階級", icon: "mdi-account-star", directTo: "/" },
          { title: "會員錢包", icon: "mdi-wallet", directTo: "/" },
        ],
      ],
      leftNavigation: null,
      navigationInfos: [
        { icon: "mdi-message-text", title: "討論區", directTo: "/commentList" },
      ],
      navBarItemIndex: -1,
      hint: {
        text: "",
        open: false,
        interval: null,
        timer: 2,
      },
    };
  },
  methods: {
    ClickMenu(url) {
      if (url == "/logout") {
        this.axios
          .post("/logout", {
            token: window.localStorage.getItem("token"),
          })
          .then((res) => {
            this.Hint(res.data.msg);
            window.localStorage.removeItem("token");
            window.location.reload();
          })
          .catch((err) => {
            console.log(err);
          });
      } else {
        this.$router.push(url);
      }
    },
    Hint(text) {
      this.hint.text = text;
      this.hint.open = true;
      this.hint.interval = window.setInterval(() => {
        this.hint.timer--;
        if (this.hint.timer <= 0) {
          window.clearInterval(this.hint.interval);
          this.hint.timer = 0;
          this.hint.open = false;
        }
      }, 1000);
    },
  },
};
</script>

<style lang="scss" scoped>
* {
  user-select: none;
}
.box {
  height: 7vh;
  .title_box {
    width: 10%;
    height: 100%;
    display: flex;
    align-items: center;
  }
  .title_btn {
    font-size: 20px;
    font-weight: bold;
  }
}
.list_icon {
  margin-right: 12px;
}
.center {
  display: flex;
  align-items: center;
  justify-content: center;
}
.bd {
  border: 1px solid #000;
}
</style>