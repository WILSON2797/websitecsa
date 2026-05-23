<template>
  <!-- MACHINES FLEET SECTION (Pristine Light Theme) -->
  <section id="machines" class="section">
    <!-- Visual background blueprint accents -->
    <div class="machines-grid-accent"></div>

    <div class="section-inner">
      <div class="section-header reveal">
        <div class="section-eyebrow">{{ content.machines_eyebrow || 'FASILITAS PRODUKSI' }}</div>
        <h2 class="section-title">{{ content.machines_title || 'Mesin & Peralatan' }}</h2>
        <p class="section-sub">{{ content.machines_sub || 'Dilengkapi mesin press modern dengan berbagai kapasitas untuk memenuhi kebutuhan produksi Anda.' }}</p>
      </div>
      
      <!-- Machines Cards Grid (3 Columns) -->
      <div class="machine-grid reveal">
        <div v-for="(m, idx) in machineList" :key="idx" class="machine-card-new">
          <!-- Card Image Slot / Graphic Drafting Backdrop -->
          <div class="machine-img-slot">
            <div class="machine-grid-bg"></div>
            <img :src="getMachineImg(m, idx)" class="machine-real-img" :alt="m.name" />
            <!-- Dynamic Count Badge -->
            <div class="machine-qty-badge">{{ m.qty }} Unit</div>
          </div>

          <!-- Card Body Details -->
          <div class="machine-body">
            <h3 class="machine-card-title">{{ m.name }}</h3>
            <p class="machine-card-spec"><i class="ti ti-activity me-1 text-accent"></i> {{ m.spec }}</p>
            <p class="machine-card-desc">{{ m.shortDesc }}</p>
            
            <router-link :to="'/machine/' + idx" class="btn-machine-detail">
              Lihat Detail <i class="ti ti-chevron-right ms-1"></i>
            </router-link>
          </div>
        </div>
      </div>
      
      <!-- Auxiliary Quality Facilities Row (Clean Icons on Light Theme) -->
      <div class="facility-row reveal">
        <div v-for="(fac, idx) in facilityList" :key="idx" class="facility-item-new">
          <div class="facility-icon-wrap-new">
            <i :class="['ti', fac.icon || 'ti-package']" aria-hidden="true"></i>
          </div>
          <div class="facility-name-new">{{ fac.name }}</div>
          <div class="facility-desc-new">{{ fac.desc }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'Machines',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const machineList = computed(() => {
      // Base static details that we augment with rich descriptions and specs
      const baseMachines = [
        { 
          name: 'Mechanical Press', 
          spec: 'Kapasitas 30T — 300T', 
          qty: 24, 
          icon: 'ti-settings-2',
          img: '', // Can be loaded dynamically in the future
          shortDesc: 'Mesin press mekanis berkecepatan tinggi untuk lembaran plat logam berulang.',
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
          img: '',
          shortDesc: 'Mesin press hidrolik dengan kekuatan tekan konstan untuk deep drawing.',
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
          img: '',
          shortDesc: 'Kontrol ketukan rekayasa mikro servo untuk part akurasi tinggi.',
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
          img: '',
          shortDesc: 'Sistem transfer multi-stasiun otomatis terintegrasi untuk part berukuran besar.',
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

      // If user uploaded customized text blocks, merge them
      if (props.content.machines_list) {
        try {
          const parsed = typeof props.content.machines_list === 'string'
            ? JSON.parse(props.content.machines_list)
            : props.content.machines_list;
          
          return baseMachines.map((bm, index) => {
            if (parsed[index]) {
              return {
                ...bm,
                name: parsed[index].name || bm.name,
                spec: parsed[index].spec || bm.spec,
                qty: parsed[index].qty || bm.qty,
                img: parsed[index].img || bm.img
              };
            }
            return bm;
          });
        } catch (e) {
          console.error('Failed to parse dynamic machines_list, fallback to default:', e);
        }
      }
      return baseMachines;
    });

    const facilityList = computed(() => {
      if (props.content.machines_facilities) {
        try {
          return typeof props.content.machines_facilities === 'string'
            ? JSON.parse(props.content.machines_facilities)
            : props.content.machines_facilities;
        } catch (e) {
          console.error('Failed to parse machines_facilities:', e);
        }
      }
      return [
        { name: 'CMM Room', desc: 'Coordinate measuring machine', icon: 'ti-microscope' },
        { name: '3D Scanner', desc: 'Reverse engineering & inspection', icon: 'ti-scan' },
        { name: 'Heat Treatment', desc: 'Hardening & tempering', icon: 'ti-temperature' },
        { name: 'Automated WH', desc: 'Automated warehouse system', icon: 'ti-package' }
      ];
    });

    const getMachineImg = (m, idx) => {
      if (m.img) return m.img;
      const fallbacks = [
        'https://images.unsplash.com/photo-1537462715879-360eeb61a0bc?q=80&w=600&auto=format&fit=crop', // Mechanical Press
        'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=600&auto=format&fit=crop', // Hydraulic Press
        'https://images.unsplash.com/photo-1581092335397-9583fe92d232?q=80&w=600&auto=format&fit=crop', // Servo Press
        'https://images.unsplash.com/photo-1581091870621-827a8409f58a?q=80&w=600&auto=format&fit=crop', // Transfer Press
        'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?q=80&w=600&auto=format&fit=crop', // CNC Machining
        'https://images.unsplash.com/photo-1531986371297-4979e4946fc6?q=80&w=600&auto=format&fit=crop'  // Welding Robot
      ];
      return fallbacks[idx] || fallbacks[0];
    };

    return {
      machineList,
      facilityList,
      getMachineImg
    };
  }
};
</script>

<style scoped>
#machines {
  background-color: var(--white) !important;
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid var(--gray100);
}

.machines-grid-accent {
  position: absolute;
  inset: 0;
  opacity: 0.03;
  background-image:
    repeating-linear-gradient(90deg, var(--navy) 0px, var(--navy) 1px, transparent 1px, transparent 40px),
    repeating-linear-gradient(0deg, var(--navy) 0px, var(--navy) 1px, transparent 1px, transparent 40px);
  pointer-events: none;
}

:deep(.section-title) {
  color: var(--navy) !important;
}

:deep(.section-sub) {
  color: var(--gray700) !important;
}

:deep(.section-eyebrow) {
  color: var(--accent) !important;
}

/* Machines Grid Layout */
.machine-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 48px;
}

/* Machine Cards (Pristine Light Theme) */
.machine-card-new {
  background: var(--white);
  border: 1px solid var(--gray100);
  border-radius: 6px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(15, 42, 66, 0.02);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  height: 100%;
}

.machine-card-new:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(15, 42, 66, 0.08);
  border-color: var(--accent);
}

/* Image/Illustration Slot */
.machine-img-slot {
  height: 160px;
  background: var(--navy);
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
}

.machine-grid-bg {
  position: absolute;
  inset: 0;
  opacity: 0.08;
  background-image: 
    linear-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
  background-size: 20px 20px;
  pointer-events: none;
}

.machine-real-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: relative;
  z-index: 2;
}

.machine-placeholder-graphic {
  position: relative;
  z-index: 1;
  font-size: 52px;
  color: var(--light);
  opacity: 0.85;
  transition: transform 0.3s ease;
}

.machine-card-new:hover .machine-placeholder-graphic {
  transform: scale(1.1);
}

.machine-qty-badge {
  position: absolute;
  bottom: 12px;
  right: 12px;
  background: var(--accent);
  color: var(--white);
  font-size: 11px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 3px;
  letter-spacing: 0.5px;
  z-index: 3;
}

/* Card Body */
.machine-body {
  padding: 24px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.machine-card-title {
  font-family: var(--font-head);
  font-size: 20px;
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 6px;
}

.machine-card-spec {
  font-size: 13px;
  font-weight: 600;
  color: var(--accent);
  margin-bottom: 12px;
  display: flex;
  align-items: center;
}

.machine-card-desc {
  font-size: 13px;
  color: var(--gray500);
  line-height: 1.6;
  margin-bottom: 20px;
  flex-grow: 1;
}

.btn-machine-detail {
  align-self: flex-start;
  background: transparent;
  color: var(--navy);
  border: 1px solid rgba(15, 42, 66, 0.15);
  font-size: 12px;
  font-weight: 600;
  padding: 8px 18px;
  border-radius: 4px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  transition: all 0.2s ease;
  font-family: var(--font-body);
}

.btn-machine-detail:hover {
  background: var(--navy);
  color: var(--white);
  border-color: var(--navy);
  transform: translateX(2px);
}

/* Auxiliary Facilities (Light Realignment) */
.facility-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  border-top: 1px solid var(--gray100);
  padding-top: 36px;
  margin-top: 20px;
}

.facility-item-new {
  text-align: center;
}

.facility-icon-wrap-new {
  width: 48px;
  height: 48px;
  background: var(--gray50);
  border: 1px solid var(--gray100);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  margin: 0 auto 10px;
  transition: all 0.2s ease;
}

.facility-icon-wrap-new i {
  font-size: 22px;
  color: var(--navy);
}

.facility-item-new:hover .facility-icon-wrap-new {
  background: var(--pale);
  border-color: var(--accent);
}

.facility-item-new:hover .facility-icon-wrap-new i {
  color: var(--accent);
}

.facility-name-new {
  font-size: 12px;
  font-weight: 600;
  color: var(--navy);
}

.facility-desc-new {
  font-size: 11px;
  color: var(--gray500);
  margin-top: 2px;
}

/* Technical Specification Modal Styling */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(15, 42, 66, 0.4);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-card {
  background: var(--white);
  border: 1px solid rgba(15, 42, 66, 0.12);
  box-shadow: 0 20px 50px rgba(15, 42, 66, 0.15);
  border-radius: 8px;
  max-width: 900px;
  width: 100%;
  overflow: hidden;
  position: relative;
  animation: modalSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes modalSlide {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.modal-header {
  padding: 24px;
  border-bottom: 1px solid var(--gray100);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header-left {
  display: flex;
  align-items: center;
  gap: 14px;
}

.modal-logo-mark {
  width: 38px;
  height: 38px;
  background: var(--accent);
  display: flex;
  align-items: center;
  justify-content: center;
  clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
}

.modal-logo-mark i {
  font-size: 18px;
  color: var(--white);
}

.modal-eyebrow {
  font-size: 9px;
  font-weight: 700;
  color: var(--accent);
  letter-spacing: 1.5px;
  display: block;
}

.modal-title {
  font-family: var(--font-head);
  font-size: 24px;
  font-weight: 700;
  color: var(--navy);
  margin-top: 2px;
}

.modal-close-btn {
  background: transparent;
  border: none;
  font-size: 20px;
  color: var(--gray500);
  cursor: pointer;
  transition: color 0.15s;
  padding: 4px;
}

.modal-close-btn:hover {
  color: var(--navy);
}

.modal-body {
  padding: 32px;
}

.modal-grid {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 36px;
}

/* Modal Blueprint graphic */
.modal-blueprint-sheet {
  background: var(--gray50);
  border: 1px solid var(--gray100);
  border-radius: 6px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 280px;
  position: relative;
  overflow: hidden;
}

.modal-blueprint-grid {
  position: absolute;
  inset: 0;
  opacity: 0.12;
  background-image: 
    linear-gradient(rgba(74, 143, 204, 0.2) 1px, transparent 1px),
    linear-gradient(90deg, rgba(74, 143, 204, 0.2) 1px, transparent 1px);
  background-size: 20px 20px;
  pointer-events: none;
}

.blueprint-vector-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-grow: 1;
  position: relative;
  z-index: 1;
}

.blueprint-meta {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
  color: var(--gray500);
  border-top: 1px solid rgba(15,42,66,0.08);
  padding-top: 12px;
  font-weight: 600;
  position: relative;
  z-index: 1;
}

/* Modal Specification content */
.modal-spec-content {
  display: flex;
  flex-direction: column;
}

.modal-badge-row {
  display: flex;
  gap: 8px;
  margin-bottom: 16px;
}

.badge-qty {
  background: var(--pale);
  border: 1px solid var(--gray100);
  color: var(--navy);
  font-size: 11px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 2px;
}

.badge-capacity {
  background: rgba(74, 143, 204, 0.1);
  border: 1px solid rgba(74, 143, 204, 0.25);
  color: var(--accent);
  font-size: 11px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 2px;
}

.spec-section-title {
  font-family: var(--font-head);
  font-size: 16px;
  font-weight: 700;
  color: var(--navy);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
  border-bottom: 1px solid var(--gray100);
  padding-bottom: 4px;
}

.spec-desc-text {
  font-size: 13px;
  color: var(--gray700);
  line-height: 1.6;
  margin-bottom: 20px;
}

.spec-table {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.spec-table-row {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  border-bottom: 1px dashed var(--gray100);
  padding-bottom: 8px;
}

.spec-label {
  color: var(--gray500);
  font-weight: 500;
}

.spec-val {
  color: var(--navy);
  font-weight: 600;
  text-align: right;
  max-width: 250px;
}

.btn-modal-dismiss {
  background: var(--navy);
  color: var(--white);
  border: none;
  font-size: 13px;
  font-weight: 600;
  padding: 10px 24px;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.15s;
}

.btn-modal-dismiss:hover {
  background: var(--blue);
}

/* Modal Fade Animation */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

/* Responsive Overrides */
@media (max-width: 992px) {
  .machine-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .modal-grid {
    grid-template-columns: 1fr;
    gap: 24px;
  }
  .modal-blueprint-sheet {
    min-height: 200px;
  }
}

@media (max-width: 768px) {
  .facility-row {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}

@media (max-width: 576px) {
  .machine-grid {
    grid-template-columns: 1fr;
  }
  .facility-row {
    grid-template-columns: 1fr;
  }
  .modal-card {
    border-radius: 4px;
  }
  .modal-body {
    padding: 20px;
  }
}
</style>
