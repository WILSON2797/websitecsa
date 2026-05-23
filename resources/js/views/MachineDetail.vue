<template>
  <div class="landing-page-wrapper">
    <!-- Machine Details Container -->
    <div class="detail-page-wrapper min-vh-100 position-relative">
      <!-- Drafting Blueprint Accent Grid -->
      <div class="hero-bg-lines"></div>
      <div class="hero-accent-bar"></div>

      <!-- Not Found State -->
      <div v-if="!machine" class="container py-5 text-center position-relative z-1" style="max-width: 600px; margin: 0 auto;">
        <h2 class="text-danger mb-4" style="font-family: var(--font-head); font-size: 32px;">Alat Tidak Ditemukan</h2>
        <p class="text-secondary mb-4 opacity-75">Spesifikasi mesin yang Anda cari tidak tersedia dalam inventaris kami.</p>
        <router-link to="/" class="btn-ghost-dark">
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
              <span class="badge bg-danger font-label-md py-1 px-3 text-white text-uppercase tracking-wider mb-2" style="background: var(--accent); padding: 4px 10px; border-radius: 2px; font-size: 9px; font-weight: 600; letter-spacing: 1px; display: inline-block;">CSA MACHINE FLEET #0{{ machineId + 1 }}</span>
              <h1 class="font-headline-lg mb-1" style="font-family: var(--font-head); font-size: 32px; font-weight: 700; color: var(--navy); line-height: 1.1;">{{ machine.name }}</h1>
              <p class="font-body-md mb-3" style="color: var(--gray700); opacity: 0.85; font-size: 14px; margin-bottom: 16px;">{{ machine.longDesc }}</p>

              <!-- Technical Parameter Cards Grid -->
              <div class="row g-2 mb-3 param-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">KAPASITAS TEKAN</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ machine.spec }}</span>
                </div>
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">ARMADA TERSEDIA</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ machine.qty }} Unit Aktif</span>
                </div>
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">NEGARA ASAL PABRIKAN</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ machine.details.origin }}</span>
                </div>
                <div class="p-2.5 rounded-xl" style="background: var(--gray50); border: 1px solid var(--gray100); padding: 12px; border-radius: 4px;">
                  <span class="font-label-md" style="display: block; font-size: 9px; color: var(--gray500); font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">STANDAR AKURASI</span>
                  <span class="fs-5 fw-bold" style="display: block; font-size: 14px; font-weight: 700; color: var(--navy);">{{ machine.details.precision }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Side: Elegant Padded Card Frame with Machine Photo Inside -->
          <div class="technical-blueprint-grid rounded-xl position-relative overflow-hidden" style="background: var(--white); border: 1px solid rgba(15, 42, 66, 0.12); box-shadow: 0 10px 30px rgba(15, 42, 66, 0.05); border-radius: 6px; padding: 20px; min-height: 320px; display: flex; flex-direction: column; justify-content: space-between;">
            <!-- Blueprint lines overlay -->
            <div class="blueprint-grid-overlay"></div>
            
            <!-- Photo Framed Inside the Card -->
            <div class="position-relative w-100" style="height: 180px; border-radius: 4px; overflow: hidden; border: 1px solid var(--gray100); margin-bottom: 12px;">
              <div class="blueprint-lines-overlay" style="opacity: 0.1; z-index: 2;"></div>
              <img :src="getMachineImg(machineId)" style="width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 1;" :alt="machine.name" />
            </div>
            
            <!-- Card Details below the photo -->
            <div class="position-relative z-1 d-flex justify-content-between align-items-center mb-2">
              <div class="text-start">
                <span class="font-label-md" style="display: block; font-size: 10px; font-weight: 700; color: var(--navy); letter-spacing: 0.5px;">CROSS-SECTION SCHEMA</span>
                <span class="font-body-sm text-secondary" style="display: block; font-size: 9px; color: var(--gray500);">Calibration index certified standard</span>
              </div>
              <div class="metrology-indicator-dot-blue pulse"></div>
            </div>
            
            <div class="d-flex justify-content-between font-label-md text-secondary pt-1" style="border-top: 1px solid rgba(15, 42, 66, 0.1); font-size: 10px; color: var(--gray500);">
              <span>SYS_REF: CSA-MCH-0{{ machineId + 1 }}</span>
              <span>CALIBRATION: PASSED (JIS)</span>
            </div>
          </div>
        </div>

        <!-- Advanced Technical Profile Sheet -->
        <h3 style="font-family: var(--font-head); font-size: 20px; font-weight: 700; color: var(--navy); margin-bottom: 16px; border-bottom: 1px solid rgba(15, 42, 66, 0.1); padding-bottom: 6px;">Sub-Micron Engineering Profile</h3>
        
        <div class="row g-3 mb-4 profile-cards-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;">
          
          <div class="p-3.5 rounded-3xl engineering-profile-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3 mb-2" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <div class="spec-icon-box" style="color: var(--accent);"><i class="ti ti-shield-check fs-4" style="font-size: 18px;"></i></div>
              <h4 class="font-headline-sm text-primary mb-0" style="font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--navy);">Sistem Keselamatan Kerja</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0" style="font-size: 12.5px; line-height: 1.5; color: var(--gray700);">
              {{ machine.details.safety }}
            </p>
          </div>

          <div class="p-3.5 rounded-3xl engineering-profile-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3 mb-2" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <div class="spec-icon-box" style="color: var(--accent);"><i class="ti ti-target fs-4" style="font-size: 18px;"></i></div>
              <h4 class="font-headline-sm text-primary mb-0" style="font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--navy);">Aplikasi Utama Komponen</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0" style="font-size: 12.5px; line-height: 1.5; color: var(--gray700);">
              {{ machine.details.application }}
            </p>
          </div>

          <div class="p-3.5 rounded-3xl engineering-profile-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3 mb-2" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <div class="spec-icon-box" style="color: var(--accent);"><i class="ti ti-settings fs-4" style="font-size: 18px;"></i></div>
              <h4 class="font-headline-sm text-primary mb-0" style="font-family: var(--font-head); font-size: 15px; font-weight: 700; color: var(--navy);">Sistem Pelumas & Daya</h4>
            </div>
            <p class="font-body-sm text-secondary opacity-75 mb-0" style="font-size: 12.5px; line-height: 1.5; color: var(--gray700);">
              Masing-masing armada diintegrasikan dengan unit otomatisasi sirkulasi fluida pembersih dies, sensor pengukur ketegangan plat, pelumas spray bertekanan mikro udara, dan sirkuit catu daya ramah lingkungan bersertifikasi ISO 14001.
            </p>
          </div>
        </div>

        <!-- Action Footer Stamping CTA (Redesigned to Professional Light Theme) -->
        <div class="rounded-3xl text-center position-relative overflow-hidden" style="background: var(--gray50); border: 1px solid var(--gray100); border-radius: 6px; padding: 36px; text-align: center; color: var(--navy); margin-bottom: 32px; box-shadow: 0 4px 20px rgba(15, 42, 66, 0.02);">
          <div class="blueprint-grid-overlay" style="opacity: 0.04;"></div>
          <div class="position-relative z-1" style="max-width: 700px; margin: 0 auto;">
            <h3 class="font-headline-md mb-2" style="font-family: var(--font-head); font-size: 24px; font-weight: 700; color: var(--navy);">Ingin Melihat Fasilitas Produksi Kami Secara Langsung?</h3>
            <p class="font-body-md mb-3" style="font-size: 14px; margin-bottom: 16px; color: var(--gray700); opacity: 0.9;">
              Hubungi tim marketing kami untuk melakukan penjadwalan Factory Audit (kunjungan pabrik) secara langsung di kawasan MM2100 Cikarang Barat.
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
  name: 'MachineDetail',
  props: {
    content: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const route = useRoute();
    const machine = ref(null);
    const machineId = ref(0);

    const getMachineImg = (idx) => {
      if (machine.value && machine.value.img) return machine.value.img;
      const fallbacks = [
        'https://images.unsplash.com/photo-1537462715879-360eeb61a0bc?q=80&w=800&auto=format&fit=crop', // Mechanical Press
        'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=800&auto=format&fit=crop', // Hydraulic Press
        'https://images.unsplash.com/photo-1581092335397-9583fe92d232?q=80&w=800&auto=format&fit=crop', // Servo Press
        'https://images.unsplash.com/photo-1581091870621-827a8409f58a?q=80&w=800&auto=format&fit=crop', // Transfer Press
        'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?q=80&w=800&auto=format&fit=crop', // CNC Machining
        'https://images.unsplash.com/photo-1531986371297-4979e4946fc6?q=80&w=800&auto=format&fit=crop'  // Welding Robot
      ];
      return fallbacks[idx] || fallbacks[0];
    };

    const loadMachine = () => {
      // Custom static dataset containing augmented data matching Machines list
      const baseMachines = [
        { 
          name: 'Mechanical Press', 
          spec: 'Kapasitas 30T — 300T', 
          qty: 24, 
          icon: 'ti-settings-2',
          longDesc: 'Armada mesin press mekanikal kami merupakan tulang punggung produksi massal dengan kecepatan ketukan tinggi. Dilengkapi dengan unit otomatisasi rol koil pengumpan (feeder lines) untuk mendukung efisiensi pengerjaan tinggi.',
          details: {
            origin: 'Jepang / Taiwan (AIDA, Seyi)',
            precision: 'JIS B 6402 Grade 1 Accuracy',
            safety: 'Dual-valve pneumatic clutch & optoelectronic light curtains keselamatan kerja otomatis, interlock overload sensors.',
            application: 'Braket otomotif, terminal kuningan kelistrikan, klip logam otomotif, EMI shielding electronics housing.'
          }
        },
        { 
          name: 'Hydraulic Press', 
          spec: 'Kapasitas 100T — 500T', 
          qty: 12, 
          icon: 'ti-droplet-half-2',
          longDesc: 'Didesain untuk proses pengerjaan penarikan dalam (deep drawing) yang membutuhkan tekanan stabil sepanjang stroke. Mampu menangani lembaran logam tebal menjadi bentuk silinder atau cup tanpa sambungan.',
          details: {
            origin: 'Jepang / Taiwan (AMADA, Seyi)',
            precision: 'JIS B 6403 Hydraulic Standard',
            safety: 'Hydraulic overload protector valves, emergency physical interlock gate sensor, rem kopling darurat pneumatik.',
            application: 'EV battery case shells, filter oli silinder, tabung pemadam api tanpa sambungan, tutup tangki bahan bakar.'
          }
        },
        { 
          name: 'Servo Press', 
          spec: 'Kapasitas 80T — 200T', 
          qty: 8, 
          icon: 'ti-cpu',
          longDesc: 'Menggunakan teknologi motor AC servo dengan kurva ketukan yang dapat diprogram penuh. Memberikan akurasi penekanan mikro-milimeter guna menghindari keretakan material (springback control) pada material berkekuatan tarik tinggi.',
          details: {
            origin: 'Jepang (AIDA, AMADA)',
            precision: 'Micron-level control (±0.005mm)',
            safety: 'Direct torque motor feedback sensor, emergency brake switch, sensor pendeteksi ketebalan plat ganda.',
            application: 'Komponen safety-critical otomotif, roda gigi transmisi mikro presisi halus, kopling cakram, spacer mesin.'
          }
        },
        { 
          name: 'Transfer Press', 
          spec: 'Kapasitas 200T — 500T', 
          qty: 6, 
          icon: 'ti-box',
          longDesc: 'Menghubungkan beberapa proses dies (blanking, bending, piercing) dalam satu rangkaian stasiun otomatis dengan robot lengan transfer. Ideal untuk memproduksi komponen struktural berukuran menengah hingga besar secara massal.',
          details: {
            origin: 'Jepang (Komatsu)',
            precision: 'Automated stational alignment accuracy',
            safety: 'Pneumatic transfer feedback lines, physical perimeter fencing, tirai cahaya optoelektronik keselamatan kerja.',
            application: 'Sasis struktural otomotif ukuran besar, kompresor AC shells, panel pintu struktural, komponen frame.'
          }
        },
        { 
          name: 'CNC Machining Center', 
          spec: 'Tool room & die making', 
          qty: 14, 
          icon: 'ti-sparkles',
          longDesc: 'Fasilitas in-house tooling kami dilengkapi dengan CNC millings berkecepatan tinggi untuk membuat, memodifikasi, dan merawat cetakan dies logam secara presisi, menjamin kesiapan cetakan tanpa bergantung vendor luar.',
          details: {
            origin: 'Jepang / Taiwan (Matsuura, YCM)',
            precision: 'Sub-micron tooling tolerance (±0.002mm)',
            safety: 'Enclosed cabin interlock door, automated spindle overload monitor, dust filtration suction loops.',
            application: 'Pembuatan progressive dies set, stage tooling dies, transfer die arm fixtures, CNC custom molds presisi.'
          }
        },
        { 
          name: 'Welding Robot', 
          spec: 'Spot & MIG Welding', 
          qty: 10, 
          icon: 'ti-robot',
          longDesc: 'Lengan robot multi-axis otomatis untuk pengelasan titik (spot welding) dan pengelasan gas metal (MIG). Menghasilkan pengelasan yang rapi, penetrasi kuat, dan bebas dari cacat las manusia pada komponen rakitan.',
          details: {
            origin: 'Jepang (OTC Daihen, Yaskawa)',
            precision: 'High repeatability path accuracy (±0.08mm)',
            safety: 'Flash arc safety curtain, area laser safety scanners, physical fencing perimeter interlock.',
            application: 'Sub-assembly welding bracket otomotif, reinforcement panel sasis, pengelasan presisi sasis.'
          }
        }
      ];

      let list = props.content.machines_list || [];
      if (typeof list === 'string') {
        list = JSON.parse(list);
      }

      // Merge user custom database items if existing
      const mergedList = baseMachines.map((bm, index) => {
        if (list[index]) {
          return {
            ...bm,
            name: list[index].name || bm.name,
            spec: list[index].spec || bm.spec,
            qty: list[index].qty || bm.qty
          };
        }
        return bm;
      });

      const paramId = route.params.id;
      const index = parseInt(paramId, 10);
      if (!isNaN(index) && mergedList[index]) {
        machine.value = mergedList[index];
        machineId.value = index;
      }
    };

    const waUrl = computed(() => {
      const number = props.content.contact_wa || '6281289901234';
      return `https://api.whatsapp.com/send?phone=${number}&text=Halo%20PT%20Cahaya%20Sentosa%20Abadi%2C%20saya%20tertarik%20untuk%20berkonsultasi%20mengenai%20mesin%20${encodeURIComponent(machine.value?.name || '')}.`;
    });

    onMounted(() => {
      loadMachine();
      window.scrollTo(0, 0);
    });

    return {
      machine,
      machineId,
      getMachineImg,
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
