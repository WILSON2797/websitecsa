<template>
  <div class="landing-page-wrapper documentation-page">
    <div class="page-content">
      
      <section class="section docs-section-wrapper">
        <div class="section-inner">
          
          <div class="section-header docs-header-wrapper">
            <div class="section-eyebrow">GALLERY OVERVIEW</div>
            <h2 class="section-title">Our Documentation</h2>
            <p class="section-sub">
              View our various activities, fleet, and facilities through our photo gallery.
            </p>
          </div>

          <!-- Filter Buttons -->
          <div class="gallery-filters">
            <button 
              v-for="cat in categories" 
              :key="cat.value"
              class="filter-btn"
              :class="{ active: activeCategory === cat.value }"
              @click="activeCategory = cat.value"
            >
              <i v-if="cat.icon" :class="cat.icon"></i> {{ cat.label }}
            </button>
          </div>

          <!-- Gallery Grid -->
          <div class="gallery-grid" v-if="filteredGalleries.length > 0">
            <div 
              v-for="(item, idx) in filteredGalleries" 
              :key="item.id || idx"
              data-aos="fade-up"
              :data-aos-delay="idx * 100"
            >
              <div class="gallery-card" @click="openLightbox(idx)">
                <div class="gallery-img-slot">
                <img :src="item.image_url" :alt="item.title" class="gallery-real-img" loading="lazy">
              </div>
              <div class="gallery-body">
                <span class="gallery-category-tag">{{ item.category }}</span>
                <h3 class="gallery-card-title">{{ item.title }}</h3>
                <p class="gallery-card-desc" v-if="item.description">{{ item.description }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-10 text-muted">
            <p>Belum ada dokumentasi tersedia untuk kategori ini.</p>
          </div>

        </div>
      </section>

      <!-- Lightbox / Full Preview -->
      <div v-if="lightboxOpen && activeImage" class="lightbox-overlay" @click.self="closeLightbox">
        <button class="lightbox-close" @click="closeLightbox"><i class="ti ti-x"></i></button>
        
        <button class="lightbox-nav prev" @click.stop="lightboxPrev" v-if="filteredGalleries.length > 1">
          <i class="ti ti-chevron-left"></i>
        </button>

        <div class="lightbox-content">
          <img :src="activeImage.image_url" :alt="activeImage.title">
          <div class="lightbox-caption">
            <span class="caption-category">{{ activeImage.category }}</span>
            <h3>{{ activeImage.title }}</h3>
            <p v-if="activeImage.description">{{ activeImage.description }}</p>
          </div>
        </div>

        <button class="lightbox-nav next" @click.stop="lightboxNext" v-if="filteredGalleries.length > 1">
          <i class="ti ti-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';

export default {
  name: 'DocumentationPage',
  emits: ['update-active-section'],
  props: {
    content: {
      type: Object,
      required: false,
      default: () => ({})
    }
  },
  setup(props, { emit }) {
    const activeCategory = ref('All');
    const categories = [
      { label: 'All', value: 'All', icon: 'ti ti-apps' },
      { label: 'Fleet', value: 'Fleet', icon: 'ti ti-truck' },
      { label: 'Facilities', value: 'Facilities', icon: 'ti ti-building-factory' },
      { label: 'Operations', value: 'Operations', icon: 'ti ti-settings' },
      { label: 'Team', value: 'Team', icon: 'ti ti-users' }
    ];

    const galleries = computed(() => props.content?.galleries_list || []);

    const filteredGalleries = computed(() => {
      if (activeCategory.value === 'All') {
        return galleries.value;
      }
      return galleries.value.filter(g => g.category === activeCategory.value);
    });

    // Lightbox state
    const lightboxOpen = ref(false);
    const activeIndex = ref(0);

    const activeImage = computed(() => {
      if (filteredGalleries.value.length === 0) return null;
      return filteredGalleries.value[activeIndex.value];
    });

    onMounted(() => {
      window.scrollTo(0, 0);
      emit('update-active-section', 'documentation');
      window.addEventListener('keydown', handleKeydown);
    });
    
    onUnmounted(() => {
      window.removeEventListener('keydown', handleKeydown);
    });

    const openLightbox = (idx) => {
      activeIndex.value = idx;
      lightboxOpen.value = true;
      document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
      lightboxOpen.value = false;
      document.body.style.overflow = 'auto';
    };

    const lightboxNext = () => {
      if (activeIndex.value < filteredGalleries.value.length - 1) {
        activeIndex.value++;
      } else {
        activeIndex.value = 0; // loop back to start
      }
    };

    const lightboxPrev = () => {
      if (activeIndex.value > 0) {
        activeIndex.value--;
      } else {
        activeIndex.value = filteredGalleries.value.length - 1; // loop to end
      }
    };

    const handleKeydown = (e) => {
      if (!lightboxOpen.value) return;
      if (e.key === 'ArrowRight') lightboxNext();
      if (e.key === 'ArrowLeft') lightboxPrev();
      if (e.key === 'Escape') closeLightbox();
    };

    return {
      activeCategory,
      categories,
      filteredGalleries,
      lightboxOpen,
      activeImage,
      openLightbox,
      closeLightbox,
      lightboxNext,
      lightboxPrev
    };
  }
}
</script>

<style scoped>
.docs-section-wrapper {
  padding-top: 120px;
  background-color: var(--white);
  min-height: 80vh;
}
.docs-header-wrapper {
  max-width: 600px;
  margin-bottom: 30px;
}
</style>
