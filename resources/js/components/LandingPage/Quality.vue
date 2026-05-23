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
          <div v-for="(c, idx) in certList" :key="idx" class="cert-card">
            <div class="cert-icon"><i :class="['ti', c.icon || 'ti-certificate']" aria-hidden="true"></i></div>
            <div class="cert-name">{{ c.name }}</div>
            <div class="cert-desc">{{ c.desc }}</div>
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
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'Quality',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const certList = computed(() => {
      if (props.content.quality_certs) {
        try {
          return typeof props.content.quality_certs === 'string'
            ? JSON.parse(props.content.quality_certs)
            : props.content.quality_certs;
        } catch (e) {
          console.error('Failed to parse quality_certs:', e);
        }
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

    return {
      certList,
      qcSteps
    };
  }
};
</script>
