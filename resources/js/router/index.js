import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '',
      name: 'panel',
      redirect: { name: 'panel.dashboard' },
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'dashboard',
          name: 'panel.dashboard',
          component: () => import('../views/Dashboard.vue')
        },
        {
          path: 'pages',
          name: 'panel.pages',
          component: () => import('../views/pages/Index.vue')
        }
      ]
    },
    {
      name: 'auth',
      path: '/auth',
      component: () => import('../layouts/auth.vue'),
      children: [
        {
          path: 'signin',
          name: 'auth.signin',
          component: () => import('../views/auth/SignIn.vue')
        }
      ]
    }
  ]
})

export default router
