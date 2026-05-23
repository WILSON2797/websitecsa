import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from './views/LandingPage.vue';
import ProductDetail from './views/ProductDetail.vue';
import MachineDetail from './views/MachineDetail.vue';
import AboutPage from './views/AboutPage.vue';
import ProductsPage from './views/ProductsPage.vue';
import MachinesPage from './views/MachinesPage.vue';
import QualityPage from './views/QualityPage.vue';
import ClientsPage from './views/ClientsPage.vue';
import ContactPage from './views/ContactPage.vue';

const routes = [
  {
    path: '/',
    name: 'LandingPage',
    component: LandingPage
  },
  {
    path: '/about',
    name: 'AboutPage',
    component: AboutPage
  },
  {
    path: '/products',
    name: 'ProductsPage',
    component: ProductsPage
  },
  {
    path: '/machines',
    name: 'MachinesPage',
    component: MachinesPage
  },
  {
    path: '/quality',
    name: 'QualityPage',
    component: QualityPage
  },
  {
    path: '/clients',
    name: 'ClientsPage',
    component: ClientsPage
  },
  {
    path: '/contact',
    name: 'ContactPage',
    component: ContactPage
  },
  {
    path: '/product/:id',
    name: 'ProductDetail',
    component: ProductDetail
  },
  {
    path: '/machine/:id',
    name: 'MachineDetail',
    component: MachineDetail
  },
  // Fallback catch-all to redirect back to landing page
  {
    path: '/:catchAll(.*)',
    redirect: '/'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
        top: 80
      };
    }
    return savedPosition || { top: 0 };
  }
});

export default router;
