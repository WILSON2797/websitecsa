<template>
  <div class="landing-page-wrapper">
    <div class="page-content">
      <!-- Single Section wrapper with padding to clear Navbar -->
      <div class="standalone-section-wrapper" style="padding-top: 80px; min-height: 80vh; background-color: var(--white);">
        <Facilities :facilities="content.facilities_list || []" />
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, onUnmounted } from 'vue';
import Facilities from '../components/LandingPage/Facilities.vue';

export default {
  name: 'FacilitiesPage',
  emits: ['update-active-section'],
  components: {
    Facilities
  },
  props: {
    content: {
      type: Object,
      required: false,
      default: () => ({})
    }
  },
  setup(props, { emit }) {
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
      emit('update-active-section', 'facilities');
      setTimeout(initScrollObservers, 100);
    });

    onUnmounted(() => {
      if (observer) observer.disconnect();
    });

    return {};
  }
};
</script>
