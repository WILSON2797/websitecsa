<template>
  <!-- CLIENTS -->
  <section id="clients" class="section">
    <div class="section-inner">
      <div class="section-header reveal">
        <div class="section-eyebrow">{{ content.clients_eyebrow || 'KLIEN & MITRA' }}</div>
        <h2 class="section-title" v-html="titleHtml"></h2>
      </div>
      <p class="client-intro reveal">{{ content.clients_intro || 'Selama lebih dari 26 tahun, kami telah menjadi mitra terpercaya bagi perusahaan-perusahaan multinasional di berbagai sektor industri di Indonesia dan kawasan Asia Tenggara.' }}</p>
      
      <!-- Interactive Industry Tabs Filtering -->
      <div class="industry-tabs reveal">
        <div 
          v-for="tab in tabs" 
          :key="tab" 
          @click="activeTab = tab"
          :class="['ind-tab', activeTab === tab ? 'active' : '']"
        >
          {{ tab }}
        </div>
      </div>

      <!-- Clients Grid -->
      <div class="clients-grid reveal">
        <div v-for="(c, idx) in filteredClients" :key="idx" class="client-card">
          <div class="client-name">{{ c.name }}</div>
          <div class="client-industry">{{ c.industry }}</div>
        </div>
      </div>
      
      <!-- Testimonial Block -->
      <div class="testimonial reveal">
        <div class="testi-quote">{{ content.testimonial_quote || 'PT. Cahaya Sentosa Abadi telah menjadi mitra stamping kami selama lebih dari 10 tahun. Kualitas komponen yang konsisten, pengiriman tepat waktu, dan responsivitas tim mereka terhadap perubahan spesifikasi menjadikan mereka mitra yang sangat kami andalkan.' }}</div>
        <div class="testi-author">
          <div class="testi-name">{{ content.testimonial_author || 'Budi Santoso' }}</div>
          <div class="testi-role">{{ content.testimonial_role || 'Procurement Manager, PT Astra Honda Motor' }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, computed } from 'vue';

export default {
  name: 'Clients',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const activeTab = ref('SEMUA');
    const tabs = ['SEMUA', 'OTOMOTIF', 'ELEKTRONIK & ELEKTRIKAL'];

    const titleHtml = computed(() => {
      const rawTitle = props.content.clients_title || 'Dipercaya Oleh<br>Perusahaan Terkemuka';
      return rawTitle;
    });

    const clientList = computed(() => {
      if (props.content.clients_list) {
        try {
          return typeof props.content.clients_list === 'string'
            ? JSON.parse(props.content.clients_list)
            : props.content.clients_list;
        } catch (e) {
          console.error('Failed to parse clients_list:', e);
        }
      }
      return [
        { name: 'Toyota', industry: 'Otomotif' },
        { name: 'Astra Honda', industry: 'Otomotif' },
        { name: 'Denso', industry: 'Komponen Otomotif' },
        { name: 'Yamaha', industry: 'Otomotif' },
        { name: 'Panasonic', industry: 'Elektronik' },
        { name: 'Samsung SDI', industry: 'Baterai EV' },
        { name: 'Schneider', industry: 'Elektrikal' },
        { name: 'Mitsubishi', industry: 'Otomotif & Elektrik' }
      ];
    });

    const filteredClients = computed(() => {
      const list = clientList.value;
      if (activeTab.value === 'SEMUA') {
        return list;
      } else if (activeTab.value === 'OTOMOTIF') {
        return list.filter(c => c.industry.toLowerCase().includes('otomotif') || c.industry.toLowerCase().includes('komponen'));
      } else {
        return list.filter(c => c.industry.toLowerCase().includes('elektronik') || c.industry.toLowerCase().includes('elektrik') || c.industry.toLowerCase().includes('baterai'));
      }
    });

    return {
      activeTab,
      tabs,
      titleHtml,
      filteredClients
    };
  }
};
</script>
