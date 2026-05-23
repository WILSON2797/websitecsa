import { createRouter, createWebHistory } from 'vue-router';
import { authState, checkAuth } from './store/auth';

import LandingPage from './views/LandingPage.vue';
import ProductDetail from './views/ProductDetail.vue';
import MachineDetail from './views/MachineDetail.vue';
import AboutPage from './views/AboutPage.vue';
import ProductsPage from './views/ProductsPage.vue';
import MachinesPage from './views/MachinesPage.vue';
import QualityPage from './views/QualityPage.vue';
import ClientsPage from './views/ClientsPage.vue';
import ContactPage from './views/ContactPage.vue';

// Admin Views
import Login from './views/admin/Login.vue';
import AdminLayout from './views/admin/AdminLayout.vue';
import Dashboard from './views/admin/Dashboard.vue';
import AdminProducts from './views/admin/AdminProducts.vue';
import AdminMachines from './views/admin/AdminMachines.vue';
import AdminClients from './views/admin/AdminClients.vue';
import AdminCertificates from './views/admin/AdminCertificates.vue';
import AdminCms from './views/admin/AdminCms.vue';
import AdminInquiries from './views/admin/AdminInquiries.vue';

const routes = [
  // Public Routes
  { path: '/', name: 'LandingPage', component: LandingPage },
  { path: '/about', name: 'AboutPage', component: AboutPage },
  { path: '/products', name: 'ProductsPage', component: ProductsPage },
  { path: '/machines', name: 'MachinesPage', component: MachinesPage },
  { path: '/quality', name: 'QualityPage', component: QualityPage },
  { path: '/clients', name: 'ClientsPage', component: ClientsPage },
  { path: '/contact', name: 'ContactPage', component: ContactPage },
  { path: '/product/:id', name: 'ProductDetail', component: ProductDetail },
  { path: '/machine/:id', name: 'MachineDetail', component: MachineDetail },
  
  // Admin Routes
  { path: '/admin/login', name: 'AdminLogin', component: Login },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'AdminDashboard', component: Dashboard },
      { path: 'products', name: 'AdminProducts', component: AdminProducts },
      { path: 'machines', name: 'AdminMachines', component: AdminMachines },
      { path: 'clients', name: 'AdminClients', component: AdminClients },
      { path: 'certificates', name: 'AdminCertificates', component: AdminCertificates },
      { path: 'cms', name: 'AdminCms', component: AdminCms },
      { path: 'inquiries', name: 'AdminInquiries', component: AdminInquiries },
    ]
  },

  // Fallback catch-all to redirect back to landing page
  { path: '/:catchAll(.*)', redirect: '/' }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth', top: 80 };
    }
    return savedPosition || { top: 0 };
  }
});

// Navigation Guard
router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // If auth state is not checked yet, check it
    if (authState.loading) {
      await checkAuth();
    }
    
    if (!authState.isAuthenticated) {
      next({ name: 'AdminLogin' });
    } else {
      next();
    }
  } else if (to.name === 'AdminLogin') {
    if (authState.loading) {
      await checkAuth();
    }
    if (authState.isAuthenticated) {
      next({ name: 'AdminDashboard' });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
