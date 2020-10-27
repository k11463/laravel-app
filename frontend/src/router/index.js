import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '@/views/Home'
import Signup from '@/views/Signup'
import Login from '@/views/Login'
import Posts from '@/views/Posts'
import MemberData from '@/views/MemberData'
import CreatePost from '@/views/CreatePost'
import Post from '@/views/Post'
import UpdatePost from '@/views/UpdatePost'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { requiresAuth: false },
  },
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
    path: '/memberData',
    name: 'memberData',
    component: MemberData,
    meta: { requiresAuth: true }
  },
  {
    path: '/create_post',
    name: 'create_post',
    component: CreatePost,
    meta: { requiresAuth: true }
  },
  {
    path: '/posts',
    name: 'posts',
    component: Posts,
    meta: { requiresAuth: false },
  },
  {
    path: '/post',
    name: 'post',
    component: Post,
    meta: { requiresAuth: false }
  },
  {
    path: '/update_post',
    name: '/update_post',
    component: UpdatePost,
    meta: { requiresAuth: true }
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
