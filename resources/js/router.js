import { createRouter, createWebHistory } from 'vue-router';
import { authState, checkAuth } from './store/auth';

const LandingPage = () => import('./views/LandingPage.vue');
const ProductDetail = () => import('./views/ProductDetail.vue');
const MachineDetail = () => import('./views/MachineDetail.vue');
const AboutPage = () => import('./views/AboutPage.vue');
const ProductsPage = () => import('./views/ProductsPage.vue');
const MachinesPage = () => import('./views/MachinesPage.vue');
const FacilitiesPage = () => import('./views/FacilitiesPage.vue');
const QualityPage = () => import('./views/QualityPage.vue');
const ClientsPage = () => import('./views/ClientsPage.vue');
const ContactPage = () => import('./views/ContactPage.vue');

// Admin Views
const Login = () => import('./views/admin/Login.vue');
const AdminLayout = () => import('./views/admin/AdminLayout.vue');
const Dashboard = () => import('./views/admin/Dashboard.vue');
const AdminProducts = () => import('./views/admin/AdminProducts.vue');
const AdminMachines = () => import('./views/admin/AdminMachines.vue');
const AdminFacilities = () => import('./views/admin/AdminFacilities.vue');
const AdminClients = () => import('./views/admin/AdminClients.vue');
const AdminCertificates = () => import('./views/admin/AdminCertificates.vue');
const AdminCms = () => import('./views/admin/AdminCms.vue');
const AdminInquiries = () => import('./views/admin/AdminInquiries.vue');

const routes = [
  // Public Routes
  { path: '/', name: 'LandingPage', component: LandingPage },
  { path: '/about', name: 'AboutPage', component: AboutPage },
  { path: '/products', name: 'ProductsPage', component: ProductsPage },
  { path: '/machines', name: 'MachinesPage', component: MachinesPage },
  { path: '/facilities', name: 'FacilitiesPage', component: FacilitiesPage },
  { path: '/quality', name: 'QualityPage', component: QualityPage },
  { path: '/clients', name: 'ClientsPage', component: ClientsPage },
  { path: '/contact', name: 'ContactPage', component: ContactPage },
  { path: '/product/:id', name: 'ProductDetail', component: ProductDetail },
  { path: '/machine/:id', name: 'MachineDetail', component: MachineDetail },
  { path: '/facility/:id', name: 'FacilityDetail', component: () => import('./views/FacilityDetail.vue') },
  
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
      { path: 'facilities', name: 'AdminFacilities', component: AdminFacilities },
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
