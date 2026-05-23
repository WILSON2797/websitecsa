<template>
  <div class="app-root-frame">
    <!-- Premium Loader on Initial Load Only -->
    <div v-if="loading" class="d-flex-loader">
      <div class="spinner"></div>
      <h5 class="loader-text">MEMUAT CAHAYA SENTOSA ABADI...</h5>
    </div>

    <div v-else class="app-content-shell">
      <!-- Globally Persistent Navbar (Zero-flash during page transition) -->
      <Navbar :activeSection="activeSection" />

      <!-- Main Routed View SWAP Frame -->
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component 
            :is="Component" 
            :content="content" 
            @update-active-section="updateActiveSection" 
          />
        </transition>
      </router-view>

      <!-- Globally Persistent Footer -->
      <Footer :content="content" />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
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
    const activeSection = ref('hero');
    const route = useRoute();

    const fetchContent = async () => {
      try {
        const response = await axios.get('/api/content');
        content.value = response.data;
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
        if (newPath === '/about') activeSection.value = 'about';
        else if (newPath === '/products') activeSection.value = 'products';
        else if (newPath === '/machines') activeSection.value = 'machines';
        else if (newPath === '/quality') activeSection.value = 'quality';
        else if (newPath === '/clients') activeSection.value = 'clients';
        else if (newPath === '/contact') activeSection.value = 'contact';
        else if (newPath === '/') {
          // If on home route, scroll position handleScroll in LandingPage will update it
          activeSection.value = 'hero';
        }
      },
      { immediate: true }
    );

    onMounted(() => {
      fetchContent();
    });

    return {
      loading,
      content,
      activeSection,
      updateActiveSection
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
