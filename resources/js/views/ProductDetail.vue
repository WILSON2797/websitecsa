<template>
  <div class="landing-page-wrapper">
    <!-- Details View Container -->
    <div class="detail-page-wrapper min-vh-100 position-relative">
      <!-- Drafting Blueprint Accent Grid -->
      <div class="hero-bg-lines"></div>
      <div class="hero-accent-bar"></div>

      <!-- Not Found State -->
      <div v-if="!product" class="container py-5 text-center position-relative z-1 empty-state-container">
        <h2 class="text-danger mb-4 empty-state-title">Spesifikasi Tidak Ditemukan</h2>
        <p class="text-secondary mb-4 opacity-75">Lembar data teknis komponen yang Anda cari tidak tersedia dalam arsip kami.</p>
        <router-link to="/" class="btn-ghost-dark">
          <i class="ti ti-arrow-left me-2"></i> Kembali ke Beranda
        </router-link>
      </div>

      <!-- Main Specifications Sheet -->
      <div v-else class="container position-relative z-1 main-spec-container">
        
        <!-- Spec Overview Grid -->
        <div class="row g-4 mb-4 detail-grid">
          
          <!-- Left Side: Technical Parameters Card (Pristine Light Blueprint) -->
          <div class="spec-blueprint-canvas rounded-3xl">
            <div class="blueprint-grid-overlay"></div>
            
            <div class="position-relative z-1 w-100">
              <span class="badge bg-danger font-label-md py-1 px-3 text-white text-uppercase tracking-wider mb-2 spec-badge">DOKUMEN TEKNIS #0{{ productId + 1 }}</span>
              <h1 class="font-headline-lg mb-1 spec-title">{{ product.name }}</h1>
              <p class="font-body-md mb-3 spec-desc">{{ product.desc }}</p>

              <!-- Technical Parameter Cards Grid -->
              <div class="row g-2 mb-3 param-grid">
                <div class="p-2.5 rounded-xl param-card">
                  <span class="font-label-md param-label">TOLERANCE / SPECIFICATION</span>
                  <span class="fs-5 fw-bold param-val">{{ getToleranceVal(productId) }}</span>
                </div>
                <div class="p-2.5 rounded-xl param-card">
                  <span class="font-label-md param-label">PRODUCTION CAPACITY</span>
                  <span class="fs-5 fw-bold param-val">{{ getCapacityVal(productId) }}</span>
                </div>
                <div class="p-2.5 rounded-xl param-card">
                  <span class="font-label-md param-label">SPEED RATE</span>
                  <span class="fs-5 fw-bold param-val">{{ getSpeedVal(productId) }}</span>
                </div>
                <div class="p-2.5 rounded-xl param-card">
                  <span class="font-label-md param-label">PRODUCTION VOLUME</span>
                  <span class="fs-5 fw-bold param-val">{{ getVolumeVal(productId) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Side: Graphic Schema Drafting Overlay -->
          <div class="technical-blueprint-grid rounded-xl p-3 position-relative overflow-hidden">
            <div class="blueprint-lines-overlay"></div>
            
            <div class="position-relative z-1 d-flex justify-content-between align-items-center">
              <div class="text-start">
                <span class="font-label-md d-block schema-label">CROSS-SECTION SCHEMA</span>
                <span class="font-body-sm text-secondary schema-sub">Tolerances calibrated to ±0.01mm</span>
              </div>
              <div class="metrology-indicator-dot-blue pulse"></div>
            </div>
            
            <div class="blueprint-vector-graphic my-2 py-1 text-center position-relative z-1" style="min-height: 250px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
              
              <!-- Custom Image Slider -->
              <template v-if="detailImages.length > 0">
                <div class="slider-container" style="position: relative; width: 100%; height: 100%; border-radius: 8px; overflow: hidden; background: rgba(255,255,255,0.05); cursor: pointer;" @click="openLightbox(detailImages[activeSlide])">
                  <img :src="detailImages[activeSlide]" alt="Product detail" style="width: 100%; height: 250px; object-fit: contain; transition: opacity 0.3s ease;">
                  
                  <!-- Navigation Arrows -->
                  <div v-if="detailImages.length > 1" class="slider-arrows" style="position: absolute; inset: 0; display: flex; justify-content: space-between; align-items: center; padding: 0 10px; pointer-events: none;">
                    <button type="button" @click.stop="prevSlide" style="pointer-events: auto; background: rgba(15, 42, 66, 0.7); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.2s;"><i class="ti ti-chevron-left"></i></button>
                    <button type="button" @click.stop="nextSlide" style="pointer-events: auto; background: rgba(15, 42, 66, 0.7); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: background 0.2s;"><i class="ti ti-chevron-right"></i></button>
                  </div>
                </div>
                
                <!-- Dots -->
                <div v-if="detailImages.length > 1" class="slider-dots" style="display: flex; gap: 6px; margin-top: 15px;">
                  <button v-for="(img, idx) in detailImages" :key="idx" @click="setSlide(idx)" :style="{ width: '8px', height: '8px', borderRadius: '50%', border: 'none', padding: 0, background: activeSlide === idx ? 'var(--accent)' : '#d1d5db', transition: 'all 0.2s' }"></button>
                </div>
              </template>

              <!-- Fallback Icon if no detail images -->
              <template v-else>
                <i :class="['ti', product.icon || 'ti-hammer']" class="schema-icon" style="font-size: 6rem; color: rgba(74, 143, 204, 0.5);"></i>
              </template>
            </div>
            
            <div class="d-flex justify-content-between font-label-md text-secondary mt-1 pt-1 position-relative z-1 schema-footer">
              <span>SYS_REF: CSA-SPEC-0{{ productId + 1 }}</span>
              <span>STATUS: CERTIFIED</span>
            </div>
          </div>
        </div>

        <!-- Advanced Technical Profile Sheet -->
        <h3 v-if="getAuxiliary(productId) || getSafety(productId) || getTypical(productId)" class="profile-section-title">Sub-Micron Engineering Profile</h3>
        
        <div class="row g-3 mb-4 profile-cards-grid">
          
          <div v-if="getAuxiliary(productId)" class="p-3.5 rounded-3xl engineering-profile-card">
            <div class="d-flex align-items-center gap-3 mb-2 profile-card-header">
              <div class="spec-icon-box"><i class="ti ti-settings fs-4 profile-icon"></i></div>
              <h4 class="font-headline-sm text-primary mb-0 profile-card-title">Auxiliary System</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0 profile-card-desc">
              {{ getAuxiliary(productId) }}
            </p>
          </div>

          <div v-if="getSafety(productId)" class="p-3.5 rounded-3xl engineering-profile-card">
            <div class="d-flex align-items-center gap-3 mb-2 profile-card-header">
              <div class="spec-icon-box"><i class="ti ti-shield-check fs-4 profile-icon"></i></div>
              <h4 class="font-headline-sm text-primary mb-0 profile-card-title">Safety</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0 profile-card-desc">
              {{ getSafety(productId) }}
            </p>
          </div>

          <div v-if="getTypical(productId)" class="p-3.5 rounded-3xl engineering-profile-card">
            <div class="d-flex align-items-center gap-3 mb-2 profile-card-header">
              <div class="spec-icon-box"><i class="ti ti-packages fs-4 profile-icon"></i></div>
              <h4 class="font-headline-sm text-primary mb-0 profile-card-title">Product Applications</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0 profile-card-desc">
              {{ getTypical(productId) }}
            </p>
          </div>
        </div>

        <!-- Action Footer Stamping CTA (Redesigned to Professional Light Theme) -->
        <div class="rounded-3xl text-center position-relative overflow-hidden cta-container">
          <div class="blueprint-grid-overlay cta-overlay"></div>
          <div class="position-relative z-1 cta-content-wrapper">
            <h3 class="font-headline-md mb-2 cta-title">Need Custom Stamping Consultation?</h3>
            <p class="font-body-md mb-3 cta-desc">
              Send your inquiries or product technical drawing blueprints directly to our engineering estimation team. We are ready to provide the right DFM (Design for Manufacturing) solutions tailored to your needs.
            </p>
            <div class="cta-actions">
              <router-link to="/contact" class="btn-primary-dark cta-btn-email">
                <i class="ti ti-mail me-2"></i> Send Inquiry (Email)
              </router-link>
              <a :href="waUrl" target="_blank" class="btn-whatsapp cta-btn-wa">
                <i class="ti ti-brand-whatsapp me-2"></i> Chat via WhatsApp
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
    
    <!-- Lightbox Modal -->
    <div v-if="showLightbox" class="lightbox-overlay" @click="closeLightbox" style="position: fixed; inset: 0; background: rgba(15, 42, 66, 0.95); z-index: 9999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
      <button @click="closeLightbox" style="position: absolute; top: 20px; right: 20px; background: transparent; border: none; color: white; font-size: 2rem; cursor: pointer;"><i class="ti ti-x"></i></button>
      <img :src="lightboxImg" style="max-width: 90%; max-height: 90vh; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
    </div>

  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'ProductDetail',
  props: {
    content: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const route = useRoute();
    const product = ref(null);
    const productId = ref(0);
    const activeSlide = ref(0);
    const showLightbox = ref(false);
    const lightboxImg = ref('');

    const detailImages = computed(() => {
      if (!product.value) return [];
      const imgs = [];
      if (product.value.detail_img1) imgs.push(product.value.detail_img1);
      if (product.value.detail_img2) imgs.push(product.value.detail_img2);
      if (product.value.detail_img3) imgs.push(product.value.detail_img3);
      return imgs;
    });

    const nextSlide = () => {
      if (detailImages.value.length === 0) return;
      activeSlide.value = (activeSlide.value + 1) % detailImages.value.length;
    };

    const prevSlide = () => {
      if (detailImages.value.length === 0) return;
      activeSlide.value = activeSlide.value === 0 ? detailImages.value.length - 1 : activeSlide.value - 1;
    };

    const setSlide = (index) => {
      activeSlide.value = index;
    };

    const openLightbox = (imgSrc) => {
      if (!imgSrc) return;
      lightboxImg.value = imgSrc;
      showLightbox.value = true;
    };

    const closeLightbox = () => {
      showLightbox.value = false;
      lightboxImg.value = '';
    };

    const loadProduct = () => {
      let products = props.content.products_list || [];
      if (typeof products === 'string') {
        products = JSON.parse(products);
      }

      const paramId = route.params.id;
      const index = parseInt(paramId, 10);
      if (!isNaN(index) && products[index]) {
        product.value = products[index];
        productId.value = index;
        activeSlide.value = 0; // reset slide
      }
    };

    const getToleranceVal = (id) => {
      if (product.value && product.value.tolerance) return product.value.tolerance;
      switch (id) {
        case 0: return '±0.05 mm (High Precision)';
        case 1: return '±0.10 mm (Deep drawing flow)';
        case 2: return '±0.01 mm (Micro fine clearance)';
        case 3: return '±0.15 mm (Transfer layout spec)';
        case 4: return '±0.002 mm (CNC Tooling precision)';
        case 5: return 'AS9100 / ASTM coating rating';
        default: return '±0.05 mm';
      }
    };

    const getCapacityVal = (id) => {
      if (product.value && product.value.capacity) return product.value.capacity;
      switch (id) {
        case 0: return '45 Ton — 300 Ton';
        case 1: return '100 Ton — 500 Ton';
        case 2: return '80 Ton — 250 Ton';
        case 3: return '200 Ton — 500 Ton';
        case 4: return 'CNC travel clearance';
        case 5: return 'Electroplating conveyorized';
        default: return 'Armada Press';
      }
    };

    const getSpeedVal = (id) => {
      if (product.value && product.value.speed) return product.value.speed;
      switch (id) {
        case 0: return '45 — 120 strokes / m';
        case 1: return '15 — 35 strokes / m';
        case 2: return '60 — 180 strokes / m';
        case 3: return '12 — 30 strokes / m';
        case 4: return 'Up to 24,000 RPM Spindle';
        case 5: return 'Realtime Continuous Line';
        default: return 'Varies by speed rate';
      }
    };

    const getVolumeVal = (id) => {
      if (product.value && product.value.volume) return product.value.volume;
      switch (id) {
        case 0: return 'High Volume Mass';
        case 1: return 'Medium to High Parts';
        case 2: return 'Safety-Critical Precision';
        case 3: return 'Large Frame Structural';
        case 4: return 'Custom Prototype & Die';
        case 5: return 'Outbound Finished Coating';
        default: return 'Mass production volume';
      }
    };

    const getAuxiliary = (id) => {
      return product.value && product.value.auxiliary ? product.value.auxiliary : null;
    };

    const getSafety = (id) => {
      return product.value && product.value.safety ? product.value.safety : null;
    };

    const getTypical = (id) => {
      return product.value && product.value.typical ? product.value.typical : null;
    };

    const waUrl = computed(() => {
      const number = props.content.contact_wa || '6281289901234';
      return `https://api.whatsapp.com/send?phone=${number}&text=Halo%20PT%20Cahaya%20Sentosa%20Abadi%2C%20saya%20tertarik%20untuk%20berkonsultasi%20mengenai%20produk%20${encodeURIComponent(product.value?.name || '')}.`;
    });

    onMounted(() => {
      loadProduct();
      window.scrollTo(0, 0);
    });

    return {
      product,
      productId,
      getToleranceVal,
      getCapacityVal,
      getSpeedVal,
      getVolumeVal,
      getAuxiliary,
      getSafety,
      getTypical,
      waUrl,
      detailImages,
      activeSlide,
      nextSlide,
      prevSlide,
      setSlide,
      showLightbox,
      lightboxImg,
      openLightbox,
      closeLightbox
    };
  }
};
</script>

<style scoped>
.detail-page-wrapper {
  background-color: var(--white);
  color: var(--navy);
  padding: 100px 48px 32px;
}

.hero-bg-lines {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 55%;
  opacity: 0.05;
  background-image:
    repeating-linear-gradient(90deg, var(--accent) 0px, var(--accent) 1px, transparent 1px, transparent 40px),
    repeating-linear-gradient(0deg, var(--accent) 0px, var(--accent) 1px, transparent 1px, transparent 40px);
  pointer-events: none;
}

.hero-accent-bar {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--accent);
}

.btn-back-pill-detail {
  display: inline-flex;
  align-items: center;
  padding: 8px 20px;
  background-color: var(--navy) !important;
  border: 1px solid rgba(15,42,66,0.1) !important;
  border-radius: 9999px;
  color: var(--white) !important;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  text-decoration: none;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
}

.btn-back-pill-detail:hover {
  background-color: var(--accent) !important;
  border-color: var(--accent) !important;
  color: var(--white) !important;
  transform: translateX(-4px);
  box-shadow: 0 4px 12px rgba(74,143,204,0.3);
}

.btn-ghost-dark {
  background: transparent;
  color: var(--navy);
  border: 1px solid rgba(15,42,66,0.3);
  font-size: 13px;
  padding: 12px 28px;
  border-radius: 3px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.15s;
  cursor: pointer;
  text-decoration: none;
  font-weight: 600;
}

.btn-ghost-dark:hover {
  border-color: var(--navy);
  background-color: rgba(15,42,66,0.05);
}

/* Drafting blueprints background grid */
.blueprint-grid-overlay {
  position: absolute;
  inset: 0;
  opacity: 0.03;
  background-image: 
    linear-gradient(rgba(15, 42, 66, 0.15) 1px, transparent 1px),
    linear-gradient(90deg, rgba(15, 42, 66, 0.15) 1px, transparent 1px);
  background-size: 24px 24px;
  pointer-events: none;
}

.blueprint-lines-overlay {
  position: absolute;
  inset: 0;
  opacity: 0.12;
  background-image: 
    linear-gradient(rgba(74, 143, 204, 0.2) 1px, transparent 1px),
    linear-gradient(90deg, rgba(74, 143, 204, 0.2) 1px, transparent 1px);
  background-size: 20px 20px;
  pointer-events: none;
}

.engineering-profile-card {
  background: var(--white);
  border: 1px solid rgba(15, 42, 66, 0.1);
  box-shadow: 0 4px 20px rgba(15, 42, 66, 0.03);
  border-radius: 6px;
  padding: 24px;
  transition: all 0.2s ease;
}

.engineering-profile-card:hover {
  border-color: var(--accent);
  box-shadow: 0 8px 24px rgba(74, 143, 204, 0.08);
  transform: translateY(-2px);
}

.btn-whatsapp {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: #25D366 !important;
  color: var(--white) !important;
  border: none !important;
  border-radius: 4px;
  font-weight: 600;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 14px rgba(37, 211, 102, 0.2);
  transition: all 0.25s ease;
  cursor: pointer;
}

.btn-whatsapp:hover {
  background-color: #1ebe57 !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(37, 211, 102, 0.35);
}

.btn-primary-dark {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: var(--navy) !important;
  color: var(--white) !important;
  border: none !important;
  border-radius: 4px;
  font-weight: 600;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 14px rgba(15, 42, 66, 0.15);
  transition: all 0.25s ease;
  cursor: pointer;
}

.btn-primary-dark:hover {
  background-color: var(--blue) !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(15, 42, 66, 0.25);
}

.metrology-indicator-dot-blue {
  width: 10px;
  height: 10px;
  background-color: var(--accent);
  border-radius: 50%;
  box-shadow: 0 0 8px var(--accent);
}

@keyframes pulseAnimation {
  0% { transform: scale(0.95); opacity: 0.6; }
  50% { transform: scale(1.25); opacity: 1; box-shadow: 0 0 12px var(--accent); }
  100% { transform: scale(0.95); opacity: 0.6; }
}

.metrology-indicator-dot-blue.pulse {
  animation: pulseAnimation 2.2s infinite;
}

/* Animations */
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 1s linear infinite;
}

/* Extracted Inline Styles */
.empty-state-container {
  max-width: 600px;
  margin: 0 auto;
}
.empty-state-title {
  font-family: var(--font-head);
  font-size: 32px;
}
.main-spec-container {
  max-width: 1600px;
  margin: 0 auto;
}
.detail-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 28px;
  align-items: stretch;
}
.spec-blueprint-canvas {
  background: var(--white);
  border: 1px solid rgba(15, 42, 66, 0.12);
  box-shadow: 0 10px 30px rgba(15, 42, 66, 0.05);
  border-radius: 6px;
  padding: 24px;
  position: relative;
  overflow: hidden;
}
.spec-badge {
  background: var(--accent) !important;
  padding: 4px 10px;
  border-radius: 2px;
  font-size: 9px;
  font-weight: 600;
  letter-spacing: 1px;
  display: inline-block;
}
.spec-title {
  font-family: var(--font-head);
  font-size: 32px;
  font-weight: 700;
  color: var(--navy);
  line-height: 1.1;
}
.spec-desc {
  color: var(--gray700);
  opacity: 0.85;
  font-size: 14px;
  margin-bottom: 16px;
}
.param-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 16px;
}
.param-card {
  background: var(--gray50);
  border: 1px solid var(--gray100);
  padding: 12px;
  border-radius: 4px;
}
.param-label {
  display: block;
  font-size: 9px;
  color: var(--gray500);
  font-weight: 600;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-bottom: 2px;
}
.param-val {
  display: block;
  font-size: 14px;
  font-weight: 700;
  color: var(--navy);
}
.technical-blueprint-grid {
  background: var(--gray50);
  border: 1px solid var(--gray100);
  border-radius: 6px;
  padding: 24px;
  height: 100%;
  min-height: 320px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.schema-label {
  font-size: 10px;
  font-weight: 700;
  color: var(--navy);
  letter-spacing: 0.5px;
}
.schema-sub {
  font-size: 9px;
  color: var(--gray500);
}
.blueprint-vector-graphic {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 140px;
}
.schema-icon {
  font-size: 90px;
  color: var(--accent);
  opacity: 0.85;
}
.schema-footer {
  border-top: 1px solid rgba(15, 42, 66, 0.1);
  font-size: 10px;
  color: var(--gray500);
}
.profile-section-title {
  font-family: var(--font-head);
  font-size: 20px;
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 16px;
  border-bottom: 1px solid rgba(15, 42, 66, 0.1);
  padding-bottom: 6px;
}
.profile-cards-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 32px;
}
.engineering-profile-card {
  background: var(--white);
  border: 1px solid rgba(15, 42, 66, 0.1);
  box-shadow: 0 4px 20px rgba(15, 42, 66, 0.03);
  border-radius: 6px;
  padding: 20px;
  transition: all 0.2s ease;
}
.profile-card-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}
.spec-icon-box {
  color: var(--accent);
}
.profile-icon {
  font-size: 18px;
}
.profile-card-title {
  font-family: var(--font-head);
  font-size: 15px;
  font-weight: 700;
  color: var(--navy);
}
.profile-card-desc {
  font-size: 12.5px;
  line-height: 1.5;
  color: var(--gray700);
}
.cta-container {
  background: var(--gray50);
  border: 1px solid var(--gray100);
  border-radius: 6px;
  padding: 36px;
  text-align: center;
  color: var(--navy);
  margin-bottom: 32px;
  box-shadow: 0 4px 20px rgba(15, 42, 66, 0.02);
}
.cta-overlay {
  opacity: 0.04;
}
.cta-content-wrapper {
  max-width: 700px;
  margin: 0 auto;
}
.cta-title {
  font-family: var(--font-head);
  font-size: 24px;
  font-weight: 700;
  color: var(--navy);
}
.cta-desc {
  font-size: 14px;
  margin-bottom: 16px;
  color: var(--gray700);
  opacity: 0.9;
}
.cta-actions {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 12px;
  margin-top: 16px;
}
.cta-btn-email {
  padding: 10px 24px;
  font-size: 12.5px;
  text-decoration: none;
}
.cta-btn-wa {
  padding: 12px 28px;
  font-size: 13px;
  text-decoration: none;
}

/* Responsive Overrides */
@media (max-width: 900px) {
  .detail-page-wrapper {
    padding: 90px 16px 32px !important;
  }
  .detail-grid {
    grid-template-columns: 1fr !important;
    gap: 32px !important;
  }
  .spec-blueprint-canvas, .technical-blueprint-grid {
    padding: 20px;
  }
  .profile-cards-grid {
    grid-template-columns: 1fr !important;
    gap: 20px !important;
  }
}

@media (max-width: 576px) {
  .param-grid {
    grid-template-columns: 1fr !important;
  }
}
</style>
