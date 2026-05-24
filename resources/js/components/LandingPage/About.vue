<template>
  <!-- ABOUT -->
  <section id="about" class="section">
    <div class="section-inner">
      <div class="about-grid">
        <div class="about-text" data-aos="fade-up" data-aos-duration="1000">
          <div class="section-eyebrow">TENTANG KAMI</div>
          <h2 class="section-title" style="margin-bottom:24px;">
            {{ content.about_title || 'Pengalaman & Kepercayaan Sejak 1998' }}
          </h2>
          <p>{{ content.about_desc_1 || 'PT. Cahaya Sentosa Abadi didirikan pada tahun 1998 sebagai perusahaan manufaktur komponen stamping logam di kawasan industri Cikarang, Jawa Barat. Selama lebih dari dua dekade, kami telah berkembang menjadi salah satu mitra stamping terpercaya bagi perusahaan-perusahaan multinasional di Indonesia.' }}</p>
          <p>{{ content.about_desc_2 || 'Dengan lebih dari 500 karyawan terlatih dan fasilitas produksi seluas 35.000 m², kami mampu memenuhi kebutuhan komponen presisi dalam volume besar dengan konsistensi kualitas tinggi dan tepat waktu.' }}</p>
          
          <div class="about-highlights">
            <div v-for="(hl, idx) in highlights" :key="idx" class="highlight-row">
              <div class="highlight-icon"><i :class="['ti', hl.icon || 'ti-circle-dot']" aria-hidden="true"></i></div>
              <div class="highlight-text">
                <strong>{{ hl.title }}</strong>
                <span>{{ hl.desc }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="visi-misi" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
          <div class="vm-card visi" style="margin-bottom:0;">
            <div class="vm-label">VISI</div>
            <div class="vm-text">
              {{ content.about_visi || 'Menjadi perusahaan stamping logam terkemuka di Asia Tenggara yang dikenal atas presisi, keandalan, dan inovasi dalam setiap komponen yang kami produksi.' }}
            </div>
          </div>
          <div class="vm-card misi" style="margin-top:16px;">
            <div class="vm-label">MISI</div>
            <ul class="vm-list">
              <li v-for="(item, idx) in misiList" :key="idx">
                {{ item }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'About',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const highlights = computed(() => {
      if (props.content.about_highlights) {
        try {
          return typeof props.content.about_highlights === 'string'
            ? JSON.parse(props.content.about_highlights)
            : props.content.about_highlights;
        } catch (e) {
          console.error('Failed to parse about_highlights:', e);
        }
      }
      return [
        { title: 'Lokasi Strategis', desc: 'MM2100 Industrial Town, Cikarang Barat, Bekasi', icon: 'ti-map-pin' },
        { title: '500+ Karyawan Terlatih', desc: 'Teknisi, engineer, dan QC berpengalaman', icon: 'ti-users' },
        { title: 'Fasilitas 35.000 m²', desc: 'Area produksi, tool room, gudang, dan QC lab', icon: 'ti-building-factory-2' }
      ];
    });

    const misiList = computed(() => {
      if (props.content.about_misi) {
        try {
          return typeof props.content.about_misi === 'string'
            ? JSON.parse(props.content.about_misi)
            : props.content.about_misi;
        } catch (e) {
          console.error('Failed to parse about_misi:', e);
        }
      }
      return [
        'Menghasilkan komponen stamping berkualitas tinggi dengan toleransi presisi sesuai standar industri',
        'Menerapkan sistem manajemen mutu IATF 16949 secara konsisten',
        'Mengembangkan sumber daya manusia yang kompeten dan berkarakter',
        'Memberikan layanan terbaik kepada pelanggan dengan pengiriman tepat waktu',
        'Menjalankan operasional yang ramah lingkungan sesuai ISO 14001'
      ];
    });

    return {
      highlights,
      misiList
    };
  }
};
</script>
