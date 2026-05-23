<template>
  <!-- HERO -->
  <section id="hero">
    <div class="hero-accent-bar"></div>
    <div class="hero-bg-lines"></div>
    <div class="hero-inner">
      <div>
        <div class="hero-badge">
          <i class="ti ti-award" aria-hidden="true"></i> 
          {{ content.hero_badge || 'IATF 16949 & ISO 9001:2015 Certified' }}
        </div>
        <h1 class="hero-title" v-html="heroTitleHtml"></h1>
        <p class="hero-tagline">{{ content.hero_tagline || 'SOLUTIONS FOR INDUSTRY' }}</p>
        <p class="hero-desc">{{ content.hero_desc || 'PT. Cahaya Sentosa Abadi adalah produsen komponen stamping logam presisi tinggi yang melayani industri otomotif, elektronik, dan manufaktur sejak tahun 1998. Berlokasi di Kawasan Industri MM2100, Cikarang, Jawa Barat.' }}</p>
        <div class="hero-btns">
          <a href="#products" class="btn-primary"><i class="ti ti-packages" aria-hidden="true"></i> Lihat Produk</a>
          <a href="#contact" class="btn-ghost"><i class="ti ti-message" aria-hidden="true"></i> Hubungi Kami</a>
        </div>
      </div>
      
      <!-- Stats Sidebar Card Panel -->
      <div class="hero-panel">
        <div class="panel-title">COMPANY AT A GLANCE</div>
        <div class="stats-grid">
          <div v-for="(stat, idx) in statsList" :key="idx" class="stat-item">
            <div class="stat-num">
              {{ stat.num }}<span class="stat-unit">{{ stat.unit }}</span>
            </div>
            <div class="stat-label">{{ stat.label }}</div>
          </div>
        </div>
        <hr class="panel-divider">
        <div class="cert-row">
          <span v-for="(cert, idx) in certBadges" :key="idx" class="cert-badge">
            {{ cert }}
          </span>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'Hero',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    // Process the title text to insert <em> tags for exact style replication
    const heroTitleHtml = computed(() => {
      const rawTitle = props.content.hero_title || 'PRECISION METAL STAMPING';
      // Replace "METAL" with "<em>METAL</em>" to match original styling perfectly
      return rawTitle.replace('METAL', '<em>METAL</em>');
    });

    const statsList = computed(() => {
      if (props.content.hero_stats) {
        try {
          return typeof props.content.hero_stats === 'string'
            ? JSON.parse(props.content.hero_stats)
            : props.content.hero_stats;
        } catch (e) {
          console.error('Failed to parse hero_stats:', e);
        }
      }
      return [
        { num: '26', unit: '+', label: 'Tahun Pengalaman' },
        { num: '80', unit: '+', label: 'Unit Press Machine' },
        { num: '500', unit: 'T', label: 'Kapasitas Maks.' },
        { num: '0.1', unit: '%', label: 'Defect Rate' },
        { num: '200', unit: '+', label: 'Klien Aktif' },
        { num: '±0.05', unit: 'mm', label: 'Toleransi Presisi' }
      ];
    });

    const certBadges = computed(() => {
      if (props.content.hero_certs) {
        try {
          return typeof props.content.hero_certs === 'string'
            ? JSON.parse(props.content.hero_certs)
            : props.content.hero_certs;
        } catch (e) {
          console.error('Failed to parse hero_certs:', e);
        }
      }
      return ['ISO 9001:2015', 'IATF 16949', 'ISO 14001', '5S & Kaizen'];
    });

    return {
      heroTitleHtml,
      statsList,
      certBadges
    };
  }
};
</script>
