<template>
  <div class="landing-page-wrapper">
    <!-- Details View Container -->
    <div class="detail-page-wrapper min-vh-100 position-relative">
      <!-- Drafting Blueprint Accent Grid -->
      <div class="hero-bg-lines"></div>
      <div class="hero-accent-bar"></div>

      <!-- Not Found State -->
      <div v-if="!product" class="container py-5 text-center position-relative z-1" style="max-width: 600px; margin: 0 auto;">
        <h2 class="text-danger mb-4" style="font-family: var(--font-head); font-size: 32px;">Spesifikasi Tidak Ditemukan</h2>
        <p class="text-secondary mb-4 opacity-75">Lembar data teknis komponen yang Anda cari tidak tersedia dalam arsip kami.</p>
        <router-link to="/" class="btn-ghost-dark">
          <i class="ti ti-arrow-left me-2"></i> Kembali ke Beranda
        </router-link>
      </div>

      <!-- Main Specifications Sheet -->
      <div v-else class="container position-relative z-1" style="max-width: 1600px; margin: 0 auto;">
        
        <!-- Spec Overview Grid -->
        <div class="row g-4 mb-4 detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px; align-items: stretch;">
          
          <!-- Left Side: Technical Parameters Card (Pristine Light Blueprint) -->
          <div class="spec-blueprint-canvas rounded-3xl" style="background: var(--white); border: 1px solid rgba(15, 42, 66, 0.12); box-shadow: 0 10px 30px rgba(15, 42, 66, 0.05); border-radius: 6px; padding: 24px; position: relative; overflow: hidden;">
            <div class="blueprint-grid-overlay"></div>
            
            <div class="position-relative z-1 w-100">
              <span class="badge bg-danger font-label-md py-1 px-3 text-white text-uppercase tracking-wider mb-2" style="background: var(--accent); padding: 4px 10px; border-radius: 2px; font-size: 9px; font-weight: 600; letter-spacing: 1px; display: inline-block;">DOKUMEN TEKNIS #0{{ productId + 1 }}</span>
              <h1 class="font-headline-lg mb-1" style="font-family: var(--font-head); font-size: 32px; font-weight: 700; color: var(--navy); line-height: 1.1;">{{ product.name }}</h1>
              <p class="font-body-md mb-3" style="color: var(--gray700); opacity: 0.85; font-size: 14px; margin-bottom: 16px;">{{ product.desc }}</p>

              <!-- Technical Parameter Cards Grid -->
              <div class="row g-2 mb-3 param-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">KELAS TOLERANSI</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ getToleranceVal(productId) }}</span>
                </div>
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">KAPASITAS TEKAN</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ getCapacityVal(productId) }}</span>
                </div>
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">SPEED RATE (STROKES)</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ getSpeedVal(productId) }}</span>
                </div>
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">VOLUME PRODUKSI</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ getVolumeVal(productId) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Side: Graphic Schema Drafting Overlay -->
          <div class="technical-blueprint-grid rounded-xl p-3 position-relative overflow-hidden" style="background: var(--gray50); border: 1px solid var(--gray100); border-radius: 6px; padding: 24px; height: 100%; min-height: 320px; display: flex; flex-direction: column; justify-content: space-between;">
            <div class="blueprint-lines-overlay"></div>
            
            <div class="position-relative z-1 d-flex justify-content-between align-items-center">
              <div class="text-start">
                <span class="font-label-md d-block" style="font-size: 10px; font-weight: 700; color: var(--navy); letter-spacing: 0.5px;">CROSS-SECTION SCHEMA</span>
                <span class="font-body-sm text-secondary" style="font-size: 9px; color: var(--gray500);">Tolerances calibrated to ±0.01mm</span>
              </div>
              <div class="metrology-indicator-dot-blue pulse"></div>
            </div>
            
            <div class="blueprint-vector-graphic my-2 py-1 text-center position-relative z-1" style="display: flex; align-items: center; justify-content: center; min-height: 140px;">
              <i :class="['ti', product.icon || 'ti-hammer']" style="font-size: 90px; color: var(--accent); opacity: 0.85;"></i>
            </div>
            
            <div class="d-flex justify-content-between font-label-md text-secondary mt-1 pt-1 position-relative z-1" style="border-top: 1px solid rgba(15, 42, 66, 0.1); font-size: 10px; color: var(--gray500);">
              <span>SYS_REF: CSA-SPEC-0{{ productId + 1 }}</span>
              <span>STATUS: CERTIFIED</span>
            </div>
          </div>
        </div>

        <!-- Advanced Technical Profile Sheet -->
        <h3 style="font-family: var(--font-head); font-size: 20px; font-weight: 700; color: var(--navy); margin-bottom: 16px; border-bottom: 1px solid rgba(15, 42, 66, 0.1); padding-bottom: 6px;">Sub-Micron Engineering Profile</h3>
        
        <div class="row g-3 mb-4 profile-cards-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
          
          <div class="p-3.5 rounded-3xl engineering-profile-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3 mb-2" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <div class="spec-icon-box" style="color: var(--accent);"><i class="ti ti-settings fs-4" style="font-size: 18px;"></i></div>
              <h4 class="font-headline-sm text-primary mb-0" style="font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--navy);">Sistem Pendukung (Auxiliary)</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0" style="font-size: 12.5px; line-height: 1.5; color: var(--gray700);">
              {{ getAuxiliary(productId) }}
            </p>
          </div>

          <div class="p-3.5 rounded-3xl engineering-profile-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3 mb-2" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <div class="spec-icon-box" style="color: var(--accent);"><i class="ti ti-shield-check fs-4" style="font-size: 18px;"></i></div>
              <h4 class="font-headline-sm text-primary mb-0" style="font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--navy);">Keamanan & Sensor</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0" style="font-size: 12.5px; line-height: 1.5; color: var(--gray700);">
              {{ getSafety(productId) }}
            </p>
          </div>

          <div class="p-3.5 rounded-3xl engineering-profile-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3 mb-2" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <div class="spec-icon-box" style="color: var(--accent);"><i class="ti ti-packages fs-4" style="font-size: 18px;"></i></div>
              <h4 class="font-headline-sm text-primary mb-0" style="font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--navy);">Aplikasi Produk</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0" style="font-size: 12.5px; line-height: 1.5; color: var(--gray700);">
              {{ getTypical(productId) }}
            </p>
          </div>
        </div>

        <!-- Action Footer Stamping CTA (Redesigned to Professional Light Theme) -->
        <div class="rounded-3xl text-center position-relative overflow-hidden" style="background: var(--gray50); border: 1px solid var(--gray100); border-radius: 6px; padding: 36px; text-align: center; color: var(--navy); margin-bottom: 32px; box-shadow: 0 4px 20px rgba(15, 42, 66, 0.02);">
          <div class="blueprint-grid-overlay" style="opacity: 0.04;"></div>
          <div class="position-relative z-1" style="max-width: 700px; margin: 0 auto;">
            <h3 class="font-headline-md mb-2" style="font-family: var(--font-head); font-size: 24px; font-weight: 700; color: var(--navy);">Butuh Konsultasi Stamping Khusus?</h3>
            <p class="font-body-md mb-3" style="font-size: 14px; margin-bottom: 16px; color: var(--gray700); opacity: 0.9;">
              Kirimkan pertanyaan atau blueprint gambar teknik produk Anda langsung ke tim estimasi teknik kami. Kami siap memberikan solusi DFM yang tepat guna.
            </p>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-top: 16px;">
              <router-link to="/contact" class="btn-primary-dark" style="padding: 10px 24px; font-size: 12.5px; text-decoration: none;">
                <i class="ti ti-mail me-2"></i> Kirim Inquiry (Email)
              </router-link>
              <a :href="waUrl" target="_blank" class="btn-whatsapp" style="padding: 12px 28px; font-size: 13px; text-decoration: none;">
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
      }
    };

    const getToleranceVal = (id) => {
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
      switch (id) {
        case 0: return 'Peralatan decoiler loops otomatis, roller straightener leveling loops, pelumas otomatis mikro spray.';
        case 1: return 'Bantalan hidrolik kontrol tekanan, pengontrol ketebalan lembaran, sistem pembentuk tanpa sambungan khusus.';
        case 2: return 'Penjepit pilot mikro hidrolik presisi tinggi, fine blanking punch clearances, die ejector pad assemblies.';
        case 3: return 'Robot manipulator lengan transfer otomatis, stasiun pengubah die cepat, pilot indexing pins.';
        case 4: return 'Sistem CAD/CAM die design modeling, wire cut EDM generator sub-micron, multi-axis CNC stage fixtures.';
        case 5: return 'Bak pelapis electroplating kimia otomatis, oven conveyorized powder coating, spray pretreatment loops.';
        default: return 'Sistem sensor penumpuk ganda otomatis, pengumpan rol presisi, sensor kelonggaran die.';
      }
    };

    const getSafety = (id) => {
      switch (id) {
        case 0:
        case 1:
        case 2:
        case 3:
          return 'Tirai cahaya optoelektronik keselamatan kerja, sensor deteksi penumpukan pelat ganda, rem kopling pneumatik darurat.';
        case 4:
          return 'Pintu kabin tertutup sensor interlock laser, sensor pendeteksi getaran poros spindel spindel, sensor overload motor CNC.';
        case 5:
          return 'Pembersih udara filter hisap uap kimia asam, pengaman katup pelepas gas otomatis, sensor kebocoran kimia.';
        default:
          return 'Sensor darurat emergency stop switch interlock, sirkuit pengaman kelistrikan panel, sensor pelindung optis.';
      }
    };

    const getTypical = (id) => {
      switch (id) {
        case 0: return 'Terminal kelistrikan kuningan, klip pegas motor, braket mesin mobil, EMI shielding electronics housing.';
        case 1: return 'Baterai EV shell cups, filter oli silinder tanpa sambungan, tabung pemadam api, tutup tangki BBM.';
        case 2: return 'Roda gigi transmisi presisi halus, kopling cakram, pelat katup pengereman safety, spacer mesin mikro.';
        case 3: return 'Braket penguat sasis otomotif ukuran besar, panel pintu struktural, penutup kompresor pendingin udara.';
        case 4: return 'Progressive die sets, stage tooling dies, transfer die arm fixtures, CNC custom molds.';
        case 5: return 'Braket berlapis zinc anti korosi, panel eksterior powder coated, komponen elektrikal dengan ketahanan karat.';
        default: return 'Komponen stamping industri otomotif, elektronik, dan kelistrikan umum.';
      }
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
      waUrl
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

/* Responsive Overrides */
@media (max-width: 900px) {
  .detail-page-wrapper {
    padding-top: 100px !important;
  }
  .detail-grid {
    grid-template-columns: 1fr !important;
    gap: 32px !important;
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
