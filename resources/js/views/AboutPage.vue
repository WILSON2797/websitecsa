<template>
  <div class="landing-page-wrapper">
    <div class="page-content">
      <!-- Single Section wrapper with padding to clear Navbar -->
      <div class="standalone-section-wrapper" style="padding-top: 80px; min-height: 80vh; background-color: var(--white);">
        <About :content="content" />
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, onUnmounted } from 'vue';
import About from '../components/LandingPage/About.vue';

export default {
  name: 'AboutPage',
  components: {
    About
  },
  props: {
    content: {
      type: Object,
      required: true
    }
  },
  setup() {
    let observer = null;

    const initScrollObservers = () => {
      const reveals = document.querySelectorAll('.reveal');
      observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, { threshold: 0.1 });
      reveals.forEach(r => observer.observe(r));
    };

    onMounted(() => {
      window.scrollTo(0, 0);
      setTimeout(initScrollObservers, 100);
    });

    onUnmounted(() => {
      if (observer) observer.disconnect();
    });

    return {};
  }
};
</script>
