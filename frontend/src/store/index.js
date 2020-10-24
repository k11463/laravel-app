import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex)

// let BACKEND_URL = 'http://localhost:8000';
// let BACKEND_URL = 'http://tsushansite.ddns.net:81';
import { backend_url } from '../global_variable.js';

const db = axios.create({
  baseURL: backend_url + '/api',
});

export default new Vuex.Store({
  state: {
    user: {
      login: false,
      name: '',
      email: '',
      rank: '',
      money: 0,
      avatar: '',
      phone: '未設定'
    },
  },
  mutations: {
    setLogin(state, payload) {
      state.user.login = payload;
    },
    setUserName(state, payload) {
      state.user.name = payload;
    },
    setEmail(state, payload) {
      state.user.email = payload;
    },
    setRank(state, payload) {
      state.user.rank = payload;
    },
    setMoney(state, payload) {
      state.user.money = payload;
    },
    setAvatar(state, payload) {
      state.user.avatar = backend_url + "/storage/" + payload;
    },
    setPhone(state, payload) {
      state.user.avatar = payload;
    }
  },
  actions: {
    CheckLogin(context, payload) {
      return new Promise((resolve, reject) => {
        db.post('/user/check', {
          token: payload
        }).then((res) => {
          context.commit('setLogin', true);
          context.commit('setUserName', res.data.user_name);
          context.commit('setEmail', res.data.email);
          context.commit('setRank', res.data.rank);
          context.commit('setMoney', res.data.money);
          context.commit('setAvatar', res.data.avatar.replace('public/', ''));
          if (res.data.phone != null) {
            context.commit('setPhone', res.data.phone);
          }
          resolve();
        }).catch((err) => {
          if (err.response.data.msg == '找不到使用者') {
            window.localStorage.removeItem('token');
          }
          reject();
        })
      })
    },
    SendEmailVerifyMail(context, payload) {
      console.log('vuex -> actions : ' + context);
      return new Promise((resolve, reject) => {
        db.post('/email', {
          email: payload
        }).
          then((res) => {
            resolve(res.data.msg)
          }).catch((err) => {
            if (err.response.status == 402) {
              reject(err.response.data.msg)
            }
          });
      })
    },
  },
  modules: {
  }
})
