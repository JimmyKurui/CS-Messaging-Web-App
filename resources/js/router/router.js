import { createRouter, createWebHistory, createWebHashHistory } from 'vue-router'

import Dashboard from '../views/Dashboard.vue'
import Home from '../views/Home.vue'

const router = createRouter({
  history: createWebHashHistory(),//createWebHistory(process.env.BASE_URL || '/'),
  routes: [
    { path: '/', component: Home, name: 'home' },
    { path: '/dashboard', component: Dashboard, name: 'dashboard' },
    { path: '/support', component: Dashboard, name: 'support' }
  ]
})

export default router