import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '@/views/Home'
import Signup from '@/views/Signup'
import Login from '@/views/Login'
import CommentList from '@/views/CommentList'
import MemberData from '@/views/MemberData'
import CreateComment from '@/views/CreateComment'
import Comment from '@/views/Comment'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { requiresAuth: false },
  },
  // {
  //   path: '/test',
  //   component: () => import('@/views/test')
  // },
  {
    path: '/signup',
    name: 'signup',
    component: Signup,
    meta: { requiresAuth: false }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false }
  },
  {
    path: '/commentList',
    name: 'commentList',
    component: CommentList,
    meta: { requiresAuth: false }
  },
  {
    path: '/memberData',
    name: 'memberData',
    component: MemberData,
    meta: { requiresAuth: true }
  },
  {
    path: '/createComment',
    name: 'createComment',
    component: CreateComment,
    meta: { requiresAuth: true }
  },
  {
    path: '/comment',
    name: 'comment',
    component: Comment,
    meta: { requiresAuth: false }
  },
  {
    path: '*',
    component: () => import('@/views/404/NotFound')
  }
]

const router = new VueRouter({
  mode: 'history',
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => {
    return record.meta.requiresAuth;
  })) {
    if (window.localStorage.getItem('token') === null) {
      next({ path: '/login' });
    }
    next();
  }
  next();
});

export default router
