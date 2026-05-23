<template>
  <div class="landing-page-wrapper">
    <div class="page-content">
      <!-- Hero Section -->
      <Hero :content="content" :certificates="certificates" />

      <!-- Ticker Strip -->
      <Ticker :content="content" />

      <!-- About Section -->
      <About :content="content" />

      <!-- Products Capabilities Section -->
      <Products :content="content" />

      <!-- Machines Fleet Section -->
      <Machines :content="content" />

      <!-- Quality Assurance Section -->
      <Quality :content="content" :certificates="certificates" />

      <!-- Clients Section -->
      <Clients :content="content" />

      <!-- Contact Form & Strategic Details Section -->
      <Contact :content="content" />
    </div>
  </div>
</template>

<script>
import { onMounted, onUnmounted } from 'vue';

import Hero from '../components/LandingPage/Hero.vue';
import Ticker from '../components/LandingPage/Ticker.vue';
import About from '../components/LandingPage/About.vue';
import Products from '../components/LandingPage/Products.vue';
import Machines from '../components/LandingPage/Machines.vue';
import Quality from '../components/LandingPage/Quality.vue';
import Clients from '../components/LandingPage/Clients.vue';
import Contact from '../components/LandingPage/Contact.vue';

export default {
  name: 'LandingPage',
  components: {
    Hero,
    Ticker,
    About,
    Products,
    Machines,
    Quality,
    Clients,
    Contact
  },
  props: {
    content: {
      type: Object,
      default: () => ({})
    },
    certificates: {
      type: Array,
      default: () => []
    }
  },
  emits: ['update-active-section'],
  setup(props, { emit }) {
    let observer = null;

    // Tracking active navigation links on scroll and emitting to parent Navbar
    const handleScroll = () => {
      const sections = document.querySelectorAll('section[id]');
      let current = 'hero';
      const scrollPosition = window.scrollY + 120;

      sections.forEach(s => {
        if (scrollPosition >= s.offsetTop) {
          current = s.id;
        }
      });
      emit('update-active-section', current);
    };

    // Scroll reveal intersection observers
    const initScrollObservers = () => {
      const reveals = document.querySelectorAll('.reveal');
      
      observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, {
        threshold: 0.1
      });

      reveals.forEach(r => observer.observe(r));
    };

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
      setTimeout(() => {
        initScrollObservers();
        
        // Handle SPA Hash Redirect scroll on load if any
        const hash = window.location.hash;
        if (hash) {
          const targetEl = document.querySelector(hash);
          if (targetEl) {
            const yOffset = -80;
            const y = targetEl.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
          }
        }
      }, 150);
    });

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll);
      if (observer) {
        observer.disconnect();
      }
    });

    return {};
  }
};
</script>
