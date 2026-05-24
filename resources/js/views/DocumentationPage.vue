<template>
  <div class="landing-page-wrapper documentation-page">
    <div class="page-content">
      <div class="standalone-section-wrapper">
        <Documentation :content="content" />
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, onUnmounted } from 'vue';
import Documentation from '../components/LandingPage/Documentation.vue';

export default {
  name: 'DocumentationPage',
  components: {
    Documentation
  },
  emits: ['update-active-section'],
  props: {
    content: {
      type: Object,
      required: false,
      default: () => ({})
    }
  },
  setup(props, { emit }) {
    onMounted(() => {
      window.scrollTo(0, 0);
      emit('update-active-section', 'documentation');
    });

    return {};
  }
}
</script>

<style scoped>
.standalone-section-wrapper {
  padding-top: 80px;
  min-height: 80vh;
  background-color: var(--white);
}
</style>
