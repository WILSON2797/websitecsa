<template>
  <div class="landing-page-wrapper">
    <!-- Facility Details Container -->
    <div class="detail-page-wrapper min-vh-100 position-relative">
      <!-- Drafting Blueprint Accent Grid -->
      <div class="hero-bg-lines"></div>
      <div class="hero-accent-bar"></div>

      <!-- Not Found State -->
      <div v-if="!facility" class="container py-5 text-center position-relative z-1" style="max-width: 600px; margin: 0 auto;">
        <h2 class="text-danger mb-4" style="font-family: var(--font-head); font-size: 32px;">Fasilitas Tidak Ditemukan</h2>
        <p class="text-secondary mb-4 opacity-75">Detail fasilitas yang Anda cari tidak tersedia.</p>
        <router-link to="/#facilities" class="btn-ghost-dark">
          <i class="ti ti-arrow-left me-2"></i> Kembali ke Beranda
        </router-link>
      </div>

      <!-- Main Specifications Sheet -->
      <div v-else class="container position-relative z-1" style="max-width: 1600px; margin: 0 auto;">
        
        <!-- Spec Overview Grid -->
        <div class="row g-4 mb-4 detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px; align-items: stretch;">
          
          <!-- Left Side: Technical Parameters Card -->
          <div class="spec-blueprint-canvas rounded-3xl" style="background: var(--white); border: 1px solid rgba(15, 42, 66, 0.12); box-shadow: 0 10px 30px rgba(15, 42, 66, 0.05); border-radius: 6px; padding: 24px; position: relative; overflow: hidden;">
            <div class="blueprint-grid-overlay"></div>
            
            <div class="position-relative z-1 w-100">
              <span class="badge bg-danger font-label-md py-1 px-3 text-white text-uppercase tracking-wider mb-2" style="background: var(--accent); padding: 4px 10px; border-radius: 2px; font-size: 9px; font-weight: 600; letter-spacing: 1px; display: inline-block;">CSA FACILITY #0{{ facility.id || facilityId }}</span>
              <h1 class="font-headline-lg mb-1" style="font-family: var(--font-head); font-size: 32px; font-weight: 700; color: var(--navy); line-height: 1.1;">{{ facility.name }}</h1>
              <p class="font-body-md mb-3" style="color: var(--gray700); opacity: 0.85; font-size: 14px; margin-bottom: 16px;">{{ facility.long_desc || facility.short_desc || 'Infrastruktur pendukung operasional PT Cahaya Sentosa Abadi.' }}</p>

              <!-- Technical Parameter Cards Grid -->
              <div class="row g-2 mb-3 param-grid" style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 16px;">
                <div v-for="(spec, idx) in parsedSpecs" :key="idx" class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">{{ spec.label }}</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ spec.value }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Side: Elegant Padded Card Frame with Photo Inside -->
          <div class="technical-blueprint-grid rounded-xl position-relative overflow-hidden" style="background: var(--white); border: 1px solid rgba(15, 42, 66, 0.12); box-shadow: 0 10px 30px rgba(15, 42, 66, 0.05); border-radius: 6px; padding: 20px; min-height: 320px; display: flex; flex-direction: column; justify-content: space-between;">
            <!-- Blueprint lines overlay -->
            <div class="blueprint-grid-overlay"></div>
            
            <!-- Photo Framed Inside the Card -->
            <div class="position-relative w-100" style="height: 250px; border-radius: 4px; overflow: hidden; border: 1px solid var(--gray100); margin-bottom: 12px; display: flex; align-items: center; justify-content: center; background: var(--navy);">
              <div class="blueprint-lines-overlay" style="opacity: 0.1; z-index: 2;"></div>
              <img v-if="facility.img" :src="facility.img" style="width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 1;" :alt="facility.name" loading="lazy" decoding="async" />
              <i v-else :class="facility.icon || 'ti ti-building'" style="font-size: 80px; color: var(--light); opacity: 0.8; z-index: 1;"></i>
            </div>
            
            <!-- Card Details below the photo -->
            <div class="position-relative z-1 d-flex justify-content-between align-items-center mb-2">
              <div class="text-start">
                <span class="font-label-md" style="display: block; font-size: 10px; font-weight: 700; color: var(--navy); letter-spacing: 0.5px;">INFRASTRUCTURE LAYOUT</span>
                <span class="font-body-sm text-secondary" style="display: block; font-size: 9px; color: var(--gray500);">Industrial zone mapping standard</span>
              </div>
              <div class="metrology-indicator-dot-blue pulse"></div>
            </div>
            
            <div class="d-flex justify-content-between font-label-md text-secondary pt-1" style="border-top: 1px solid rgba(15, 42, 66, 0.1); font-size: 10px; color: var(--gray500);">
              <span>SYS_REF: CSA-FAC-0{{ facility.id || facilityId }}</span>
              <span>STATUS: OPERATIONAL</span>
            </div>
          </div>
        </div>

        <!-- Action Footer CTA (Redesigned to Professional Light Theme) -->
        <div class="rounded-3xl text-center position-relative overflow-hidden" style="background: var(--gray50); border: 1px solid var(--gray100); border-radius: 6px; padding: 36px; text-align: center; color: var(--navy); margin-bottom: 32px; box-shadow: 0 4px 20px rgba(15, 42, 66, 0.02);">
          <div class="blueprint-grid-overlay" style="opacity: 0.04;"></div>
          <div class="position-relative z-1" style="max-width: 700px; margin: 0 auto;">
            <h3 class="font-headline-md mb-2" style="font-family: var(--font-head); font-size: 24px; font-weight: 700; color: var(--navy);">Ingin Mengunjungi Fasilitas Kami?</h3>
            <p class="font-body-md mb-3" style="font-size: 14px; margin-bottom: 16px; color: var(--gray700); opacity: 0.9;">
              Hubungi tim marketing kami untuk menjadwalkan Factory Audit (kunjungan pabrik) secara langsung di kawasan MM2100 Cikarang Barat.
            </p>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-top: 16px;">
              <router-link to="/contact" class="btn-primary-dark" style="padding: 10px 24px; font-size: 12.5px; text-decoration: none;">
                <i class="ti ti-mail me-2"></i> Kirim Inquiry (Email)
              </router-link>
              <a href="https://api.whatsapp.com/send?phone=6281289901234&text=Halo%20PT%20Cahaya%20Sentosa%20Abadi%2C%20saya%20tertarik%20untuk%20berkunjung." target="_blank" class="btn-whatsapp" style="padding: 12px 28px; font-size: 13px; text-decoration: none;">
                <i class="ti ti-brand-whatsapp me-2"></i> Chat via WhatsApp
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'FacilityDetail',
  props: {
    content: {
      type: Object,
      required: true,
      default: () => ({})
    }
  },
  setup(props) {
    const route = useRoute();
    const facility = ref(null);
    const facilityId = ref('');

    const loadFacility = () => {
      const id = route.params.id;
      facilityId.value = id;
      
      let list = props.content.facilities_list || [];
      let index = parseInt(id);
      let found = null;
      
      if (!isNaN(index)) {
        found = list.find(f => f.id === index);
        if (!found && list[index]) found = list[index];
      }
      
      facility.value = found;
    };

    const parsedSpecs = computed(() => {
      if (!facility.value || !facility.value.spec) return [];
      try {
        const parsed = JSON.parse(facility.value.spec);
        return Array.isArray(parsed) ? parsed : [];
      } catch (e) {
        return facility.value.spec.split('\n').map(line => {
          const parts = line.split(':');
          if (parts.length > 1) {
            return { label: parts[0].trim(), value: parts.slice(1).join(':').trim() };
          }
          return { label: 'INFO', value: line.trim() };
        });
      }
    });

    onMounted(() => {
      loadFacility();
      window.scrollTo(0, 0);
    });

    watch(() => props.content, () => {
      loadFacility();
    }, { deep: true });

    return {
      facility,
      facilityId,
      parsedSpecs
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

/* Blueprint grid overlays */
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
@media (max-width: 900px) {
  .detail-page-wrapper {
    padding-top: 100px !important;
  }
  .detail-grid {
    grid-template-columns: 1fr !important;
    gap: 32px !important;
  }
}
</style>
