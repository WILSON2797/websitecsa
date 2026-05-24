<template>
  <!-- CLIENTS -->
  <section id="clients" class="section">
    <div class="section-inner">
      <div class="section-header" data-aos="fade-down">
        <div class="section-eyebrow">{{ content.clients_eyebrow || 'KLIEN & MITRA' }}</div>
        <h2 class="section-title" v-html="titleHtml"></h2>
      </div>
      <p class="client-intro" data-aos="fade-up" data-aos-delay="100">{{ content.clients_intro || 'Selama lebih dari 26 tahun, kami telah menjadi mitra terpercaya bagi perusahaan-perusahaan multinasional di berbagai sektor industri di Indonesia dan kawasan Asia Tenggara.' }}</p>
      
      <!-- Interactive Industry Tabs Filtering -->
      <div class="industry-tabs" data-aos="fade-up" data-aos-delay="200">
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
      <div class="clients-grid">
        <div v-for="(c, idx) in filteredClients" :key="idx" data-aos="fade-up" :data-aos-delay="idx * 100">
          <div class="client-card">
            <div class="client-logo-wrap">
              <img v-if="c.logo_path" :src="c.logo_path" :alt="c.name" class="client-logo-img">
              <div v-else class="client-logo-placeholder">{{ c.name.charAt(0) }}</div>
            </div>
            <div class="client-name">{{ c.name }}</div>
            <div class="client-industry">{{ c.industry }}</div>
          </div>
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
    const activeTab = ref('ALL');
    const tabs = ['ALL', 'AUTOMOTIVE', 'ELECTRONICS & ELECTRICAL'];

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
        { name: 'Toyota', industry: 'Automotive' },
        { name: 'Astra Honda', industry: 'Automotive' },
        { name: 'Denso', industry: 'Automotive Parts' },
        { name: 'Yamaha', industry: 'Automotive' },
        { name: 'Panasonic', industry: 'Electronics' },
        { name: 'Samsung SDI', industry: 'EV Battery' },
        { name: 'Schneider', industry: 'Electrical' },
        { name: 'Mitsubishi', industry: 'Automotive & Electrical' }
      ];
    });

    const filteredClients = computed(() => {
      const list = clientList.value;
      if (activeTab.value === 'ALL') {
        return list;
      } else if (activeTab.value === 'AUTOMOTIVE') {
        return list.filter(c => c.industry.toLowerCase().includes('automotive') || c.industry.toLowerCase().includes('parts'));
      } else {
        return list.filter(c => c.industry.toLowerCase().includes('electronics') || c.industry.toLowerCase().includes('electrical') || c.industry.toLowerCase().includes('battery') || c.industry.toLowerCase().includes('ev'));
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
