<template>
  <div class="app-root-frame">
    <!-- Premium Loader on Initial Load Only -->
    <div v-if="loading" class="d-flex-loader">
      <div class="spinner"></div>
      <h5 class="loader-text">MEMUAT CAHAYA SENTOSA ABADI...</h5>
    </div>

    <div v-else class="app-content-shell">
      <!-- Globally Persistent Navbar (Zero-flash during page transition) -->
      <Navbar v-if="!isAdminRoute" :activeSection="activeSection" />

      <!-- Main Routed View SWAP Frame -->
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component 
            :is="Component" 
            :content="content" 
            :certificates="certificates"
            @update-active-section="updateActiveSection" 
          />
        </transition>
      </router-view>

      <!-- Globally Persistent Footer -->
      <Footer v-if="!isAdminRoute" :content="content" />
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

import Navbar from './components/LandingPage/Navbar.vue';
import Footer from './components/LandingPage/Footer.vue';

export default {
  name: 'App',
  components: {
    Navbar,
    Footer
  },
  setup() {
    const loading = ref(true);
    const content = ref({});
    const certificates = ref([]);
    const activeSection = ref('hero');
    const route = useRoute();

    const isAdminRoute = computed(() => {
      return route.path.startsWith('/admin');
    });

    const fetchContent = async () => {
      try {
        const [contentRes, certRes] = await Promise.all([
          axios.get('/api/content'),
          axios.get('/api/certificates')
        ]);
        content.value = contentRes.data;
        certificates.value = certRes.data;
      } catch (error) {
        console.error('Failed to load global company content:', error);
      } finally {
        setTimeout(() => {
          loading.value = false;
        }, 300);
      }
    };

    const updateActiveSection = (section) => {
      activeSection.value = section;
    };

    // Auto-update navbar highlights based on route path
    watch(
      () => route.path,
      (newPath) => {
        // Silently fetch fresh content on route change so SPA always has latest CMS data
        fetchContent();

        if (newPath === '/about') activeSection.value = 'about';
        else if (newPath === '/products') activeSection.value = 'products';
        else if (newPath === '/machines') activeSection.value = 'machines';
        else if (newPath === '/quality') activeSection.value = 'quality';
        else if (newPath === '/clients') activeSection.value = 'clients';
        else if (newPath === '/contact') activeSection.value = 'contact';
        else if (newPath === '/') {
          activeSection.value = 'hero';
        }
      },
      { immediate: true }
    );

    onMounted(() => {
      fetchContent();

      // Silently fetch fresh content when user switches back to this tab (from admin panel tab)
      document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
          fetchContent();
        }
      });
    });

    return {
      loading,
      content,
      certificates,
      activeSection,
      updateActiveSection,
      isAdminRoute
    };
  }
};
</script>

<style>
/* Page transition animation */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
