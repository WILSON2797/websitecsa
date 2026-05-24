<template>
  <div class="app-root-frame">
    <!-- Wait for router to be ready before rendering anything -->
    <template v-if="!routerReady">
      <!-- Empty: nothing rendered until router resolves the route -->
    </template>

    <!-- ADMIN route: no loader, no navbar, no footer, no CMS fetch -->
    <template v-else-if="isAdminRoute">
      <router-view v-slot="{ Component }">
        <component :is="Component" />
      </router-view>
    </template>

    <!-- PUBLIC route: show loader, navbar, footer, CMS data -->
    <template v-else>
      <transition name="intro-fade">
        <IntroLoader v-if="loading" />
      </transition>

      <div v-if="!loading" class="app-content-shell">
        <Navbar :activeSection="activeSection" />

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

        <Footer :content="content" :certificates="certificates" />
      </div>
    </template>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

import Navbar from './components/LandingPage/Navbar.vue';
import Footer from './components/LandingPage/Footer.vue';
import IntroLoader from './components/LandingPage/IntroLoader.vue';

export default {
  name: 'App',
  components: {
    Navbar,
    Footer,
    IntroLoader
  },
  setup() {
    const routerReady = ref(false);
    const loading = ref(true);
    const content = ref({});
    const certificates = ref([]);
    const activeSection = ref('hero');
    const route = useRoute();
    const router = useRouter();

    const isAdminRoute = computed(() => {
      return route.path.startsWith('/admin');
    });

    const fetchContent = async () => {
      // Never fetch CMS content on admin routes
      if (isAdminRoute.value) return;

      try {
        const fetchPromise = Promise.all([
          axios.get('/api/content'),
          axios.get('/api/certificates')
        ]);
        
        // Minimum display time for Intro Animation is 2.5s
        const minimumDelayPromise = new Promise(resolve => setTimeout(resolve, 2500));
        
        // Wait for both fetch and minimum delay to finish
        const [contentRes, certRes] = await Promise.all([fetchPromise, minimumDelayPromise]).then(results => results[0]);
        
        content.value = contentRes.data;
        certificates.value = certRes.data;
      } catch (error) {
        console.error('Failed to load global company content:', error);
      } finally {
        // Delay slight tick to allow fade out transition
        setTimeout(() => {
          loading.value = false;
        }, 100);
      }
    };

    const updateActiveSection = (section) => {
      activeSection.value = section;
    };

    // Auto-update navbar highlights based on route path (public only)
    watch(
      () => route.path,
      (newPath) => {
        if (newPath.startsWith('/admin')) return;

        // Silently fetch fresh content on route change so SPA always has latest CMS data
        fetchContent();

        if (newPath === '/about') activeSection.value = 'about';
        else if (newPath === '/products') activeSection.value = 'products';
        else if (newPath === '/machines') activeSection.value = 'machines';
        else if (newPath === '/facilities') activeSection.value = 'facilities';
        else if (newPath === '/quality') activeSection.value = 'quality';
        else if (newPath === '/clients') activeSection.value = 'clients';
        else if (newPath === '/contact') activeSection.value = 'contact';
        else if (newPath === '/') {
          activeSection.value = 'hero';
        }
      }
    );

    onMounted(async () => {
      // Wait for router to fully resolve the initial route before rendering
      await router.isReady();
      routerReady.value = true;

      // Only fetch CMS content for public routes
      if (!isAdminRoute.value) {
        fetchContent();
      } else {
        // Admin routes don't need the loader
        loading.value = false;
      }

      // Silently fetch fresh content when user switches back to this tab (public routes only)
      document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible' && !isAdminRoute.value) {
          fetchContent();
        }
      });
    });

    return {
      routerReady,
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
/* Intro overlay transition */
.intro-fade-leave-active {
  transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 1s cubic-bezier(0.4, 0, 0.2, 1);
}
.intro-fade-leave-to {
  opacity: 0;
  transform: scale(1.05);
}

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
