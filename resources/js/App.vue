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
          <transition name="page" mode="out-in">
            <component 
              :is="Component" 
              :content="content" 
              :certificates="certificates"
              @update-active-section="updateActiveSection" 
            />
          </transition>
        </router-view>

        <Footer :content="content" :certificates="certificates" />

        <!-- Floating WhatsApp Button -->
        <a 
          v-if="content.social_wa || content.contact_telp"
          :href="'https://wa.me/' + (content.social_wa || formatWaNumber(content.contact_telp))"
          target="_blank"
          rel="noopener noreferrer"
          class="whatsapp-float"
          title="Contact Us on WhatsApp"
        >
          <i class="ti ti-brand-whatsapp"></i>
        </a>
      </div>
    </template>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch, nextTick } from 'vue';
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

    // Native AOS implementation with MutationObserver for dynamic elements
    let isAosInitialized = false;
    const initAOS = () => {
      if (isAosInitialized) return;
      isAosInitialized = true;
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('aos-animate');
          } else {
            entry.target.classList.remove('aos-animate');
          }
        });
      }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      });

      const observeElements = () => {
        document.querySelectorAll('[data-aos]:not([data-aos-observed])').forEach(el => {
          const delay = el.getAttribute('data-aos-delay');
          if (delay) el.style.transitionDelay = `${delay}ms`;
          
          const duration = el.getAttribute('data-aos-duration');
          if (duration) el.style.transitionDuration = `${duration}ms`;

          observer.observe(el);
          el.setAttribute('data-aos-observed', 'true');
        });
      };

      // Initial observation
      observeElements();

      // Setup MutationObserver to watch for dynamically inserted elements (v-if, v-for, transitions)
      const mutationObserver = new MutationObserver(() => {
        observeElements();
      });

      mutationObserver.observe(document.body, { childList: true, subtree: true });
    };

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
          nextTick(() => {
            initAOS();
          });
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

    const formatWaNumber = (num) => {
      if (!num) return '';
      let clean = num.replace(/\D/g, '');
      if (clean.startsWith('0')) {
        clean = '62' + clean.substring(1);
      }
      return clean;
    };

    return {
      routerReady,
      loading,
      content,
      certificates,
      activeSection,
      updateActiveSection,
      isAdminRoute,
      formatWaNumber
    };
  }
};
</script>

<style>
.app-root-frame {
  min-height: 100vh;
}
.app-content-shell {
  overflow-x: hidden;
  width: 100%;
}

/* Intro overlay transition */
.intro-fade-leave-active {
  transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 1s cubic-bezier(0.4, 0, 0.2, 1);
}
.intro-fade-leave-to {
  opacity: 0;
  transform: scale(1.05);
}

/* Floating WhatsApp Button */
.whatsapp-float {
  position: fixed;
  width: 56px;
  height: 56px;
  bottom: 24px;
  right: 24px;
  background-color: #25d366;
  color: #fff;
  border-radius: 50%;
  text-align: center;
  font-size: 32px;
  box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
  z-index: 998;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
}
.whatsapp-float:hover {
  background-color: #128c7e;
  transform: translateY(-4px) scale(1.08);
  box-shadow: 0 6px 16px rgba(18, 140, 126, 0.4);
}
.whatsapp-float i {
  display: block;
  line-height: 1;
}
/* Pulse Animation */
.whatsapp-float::before {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #25d366;
  opacity: 0.6;
  z-index: -1;
  animation: wa-pulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}
@keyframes wa-pulse {
  0% {
    transform: scale(1);
    opacity: 0.6;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}

@media (max-width: 768px) {
  .whatsapp-float {
    width: 48px;
    height: 48px;
    bottom: 16px;
    right: 16px;
    font-size: 26px;
  }
}
</style>
