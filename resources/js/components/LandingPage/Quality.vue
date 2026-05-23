<template>
  <!-- QUALITY -->
  <section id="quality" class="section">
    <div class="section-inner">
      <div class="section-header reveal">
        <div class="section-eyebrow">{{ content.quality_eyebrow || 'KUALITAS' }}</div>
        <h2 class="section-title">{{ content.quality_title || 'Standar Mutu Tertinggi' }}</h2>
        <p class="section-sub">{{ content.quality_sub || 'Kami berkomitmen pada kualitas yang terukur dan konsisten di setiap tahap produksi.' }}</p>
      </div>
      <div class="quality-layout">
        
        <!-- Certificates Grid (Left) -->
        <div class="cert-grid reveal">
          <div v-for="(c, idx) in certList" :key="idx" class="cert-card" style="display: flex; flex-direction: column;">
            <div class="cert-icon"><i :class="['ti', c.icon || 'ti-certificate']" aria-hidden="true"></i></div>
            <div class="cert-name">{{ c.name }}</div>
            <div class="cert-desc" style="flex-grow: 1;">{{ c.desc }}</div>
            <div class="cert-action" v-if="c.img" style="margin-top: 15px; text-align: left;">
              <a href="#" @click.prevent="openModal(c)" class="view-cert-link" style="display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; color: var(--gold); text-decoration: none; transition: color 0.3s ease; cursor: pointer;">
                <span>View Certificate</span>
                <i class="ti ti-maximize"></i>
              </a>
            </div>
          </div>
        </div>
        
        <!-- QC Steps Checklist (Right) -->
        <div class="qc-steps reveal">
          <h3 style="font-family:var(--font-head);font-size:20px;font-weight:700;color:var(--navy);margin-bottom:20px;">Proses Quality Control</h3>
          <div v-for="(step, idx) in qcSteps" :key="idx" class="qc-step">
            <div class="qc-num">{{ step.num }}</div>
            <div>
              <div class="qc-title">{{ step.title }}</div>
              <div class="qc-desc">{{ step.desc }}</div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>

  <!-- WhatsApp-style Lightbox Modal -->
  <transition name="fade">
    <div v-if="selectedCert" class="cert-modal-overlay" @click.self="closeModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.95); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px;">
      
      <button @click="closeModal" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.1); border: none; color: #fff; font-size: 24px; width: 44px; height: 44px; border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; transition: background 0.3s ease; z-index: 10000;">
        <i class="ti ti-x"></i>
      </button>
      
      <img :src="selectedCert.img" :alt="selectedCert.name" style="max-width: 100%; height: auto; max-height: 80vh; object-fit: contain; display: block; border-radius: 4px; box-shadow: 0 10px 40px rgba(0,0,0,0.8);">
      
      <div style="text-align: center; padding-top: 25px; max-width: 600px;">
        <h4 style="font-family: var(--font-head); font-weight: 600; color: #fff; font-size: 20px; margin-bottom: 5px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
          {{ selectedCert.name }}
        </h4>
        <p style="font-family: var(--font-body); color: rgba(255,255,255,0.7); font-size: 14px; margin: 0 auto; line-height: 1.5; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">
          {{ selectedCert.desc }}
        </p>
      </div>
      
    </div>
  </transition>
</template>

<script>
import { computed, ref } from 'vue';

export default {
  name: 'Quality',
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
  setup(props) {
    const certList = computed(() => {
      if (props.certificates && props.certificates.length > 0) {
        return props.certificates;
      }
      return [
        { name: 'ISO 9001:2015', desc: 'Sistem manajemen kualitas untuk konsistensi produk dan kepuasan pelanggan', icon: 'ti-certificate' },
        { name: 'IATF 16949', desc: 'Standar kualitas internasional khusus industri otomotif', icon: 'ti-car' },
        { name: 'ISO 14001', desc: 'Sistem manajemen lingkungan untuk operasional yang berkelanjutan', icon: 'ti-leaf' },
        { name: '5S & Kaizen', desc: 'Penerapan lean manufacturing untuk efisiensi dan perbaikan berkelanjutan', icon: 'ti-award' }
      ];
    });

    const qcSteps = computed(() => {
      if (props.content.quality_steps) {
        try {
          return typeof props.content.quality_steps === 'string'
            ? JSON.parse(props.content.quality_steps)
            : props.content.quality_steps;
        } catch (e) {
          console.error('Failed to parse quality_steps:', e);
        }
      }
      return [
        { num: '01', title: 'Incoming Material Inspection', desc: 'Inspeksi material bahan baku menggunakan spectrometer dan hardness tester sebelum masuk produksi.' },
        { num: '02', title: 'In-Process Quality Check', desc: 'Pemeriksaan dimensi secara berkala selama proses produksi berlangsung menggunakan SPC.' },
        { num: '03', title: 'Final Inspection', desc: 'Inspeksi akhir menggunakan CMM dan 3D scanner untuk memastikan setiap komponen sesuai drawing.' },
        { num: '04', title: 'Traceability & Documentation', desc: 'Setiap lot produksi terdokumentasi lengkap dengan nomor batch dan hasil inspeksi untuk traceability penuh.' }
      ];
    });

    const selectedCert = ref(null);
    const openModal = (cert) => {
      selectedCert.value = cert;
      document.body.style.overflow = 'hidden';
    };
    const closeModal = () => {
      selectedCert.value = null;
      document.body.style.overflow = '';
    };

    return {
      certList,
      qcSteps,
      selectedCert,
      openModal,
      closeModal
    };
  }
};
</script>
