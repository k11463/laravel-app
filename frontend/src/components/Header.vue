<template>
  <v-app class="box">
    <v-app-bar
      absolute
      color="#fcb69f"
      dark
      src="https://i.pximg.net/img-original/img/2020/10/12/04/37/02/84963288_p0.jpg"
    >
      <template v-slot:img="{ props }">
        <v-img
          v-bind="props"
          gradient="to top right, rgba(19,84,122,.5), rgba(128,208,199,.8)"
        ></v-img>
      </template>

      <v-app-bar-nav-icon></v-app-bar-nav-icon>

      <v-toolbar-title>智善的家</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-menu offset-y>
        <template v-slot:activator="{ attrs, on }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-account</v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-icon class="list_icon">mdi-cash-usd</v-icon>
            999999
          </v-list-item>
          <v-list-item link v-for="(menu, index) in account_menus" :key="index" @click="ClickMenu(index)">
            <v-icon class="list_icon">{{ menu.icon }}</v-icon>
            <v-list-item-title v-text="menu.title"></v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

      <div v-if="login">會員名稱</div>
      
    </v-app-bar>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      account_menus: [
        { title: "會員登入", icon: "mdi-account" },
        { title: "修改會員資料", icon: "mdi-account-edit" },
        { title: "個人資料", icon: "mdi-account-search" },
        { title: "會員階級", icon: "mdi-account-star" },
        { title: "會員錢包", icon: "mdi-wallet" },
      ],
      login: true,
      clickMenuValue: 0
    }
  },
  methods: {
    ClickMenu(index) {
      this.clickMenuValue = index;
      this.$emit('clickMenuValue', this.clickMenuValue);
      // switch(index) {
      //   case 0:
      //     console.log("0");
      //     break;
      //   case 1:
      //     console.log("01");
      //     break;
      //   case 2:
      //     console.log("02");
      //     break;
      //   case 3:
      //     console.log("03");
      //     break;
      //   case 4:
      //     console.log("04");
      //     break;
      // }
    }
  },
}
</script>

<style lang="scss" scoped>
.box {
  height: 7vh;
  position: fixed;
  left: 0%;
  right: 0%;
  z-index: 8;
}
.list_icon {
  margin-right: 12px;
}
</style>