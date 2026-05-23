<template>
  <!-- PRODUCTS -->
  <section id="products" class="section">
    <div class="section-inner">
      <div class="section-header reveal">
        <div class="section-eyebrow">{{ content.products_eyebrow || 'LAYANAN & PRODUK' }}</div>
        <h2 class="section-title">{{ content.products_title || 'Kemampuan Stamping Kami' }}</h2>
        <p class="section-sub">{{ content.products_sub || 'Kami menyediakan solusi stamping lengkap mulai dari desain die hingga produksi massal.' }}</p>
      </div>
      <div class="product-grid">
        <div v-for="(prod, idx) in productList" :key="idx" class="product-card reveal">
          <div class="product-img">
            <div class="product-img-bg"></div>
            <i :class="['ti', prod.icon || 'ti-hammer']" aria-hidden="true"></i>
          </div>
          <div class="product-body">
            <div class="product-tag">{{ prod.tag }}</div>
            <div class="product-name">{{ prod.name }}</div>
            <div class="product-desc">{{ prod.desc }}</div>
            <div class="product-specs">
              <span v-for="(spec, sIdx) in prod.specs" :key="sIdx" class="spec-chip">
                {{ spec }}
              </span>
            </div>
            
            <router-link :to="'/product/' + idx" class="btn-product-detail">
              Lihat Spesifikasi <i class="ti ti-chevron-right ms-1"></i>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'Products',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const productList = computed(() => {
      if (props.content.products_list) {
        try {
          return typeof props.content.products_list === 'string'
            ? JSON.parse(props.content.products_list)
            : props.content.products_list;
        } catch (e) {
          console.error('Failed to parse products_list:', e);
        }
      }
      return [
        {
          tag: 'HIGH VOLUME',
          name: 'Progressive Stamping',
          desc: 'Proses stamping berkelanjutan dengan beberapa stasiun die untuk produksi volume tinggi secara efisien.',
          specs: ['±0.05mm toleransi', 'Vol. tinggi', 'Otomatis'],
          icon: 'ti-adjustments-horizontal'
        },
        {
          tag: 'COMPLEX SHAPE',
          name: 'Deep Drawing',
          desc: 'Proses pembentukan lembaran logam menjadi komponen berbentuk cup, silinder, atau shell tanpa sambungan.',
          specs: ['Tanpa sambungan', 'Ketebalan seragam'],
          icon: 'ti-arrow-down-circle'
        },
        {
          tag: 'PRECISION CUT',
          name: 'Fine Blanking',
          desc: 'Pemotongan presisi dengan permukaan shear yang halus dan rata tanpa memerlukan proses finishing tambahan.',
          specs: ['Permukaan halus', 'No finishing'],
          icon: 'ti-scissors'
        },
        {
          tag: 'MULTI-STEP',
          name: 'Transfer Stamping',
          desc: 'Proses multi-tahap dengan transfer otomatis antar stasiun, ideal untuk komponen berukuran besar and kompleks.',
          specs: ['Komponen besar', 'Transfer otomatis'],
          icon: 'ti-repeat'
        },
        {
          tag: 'IN-HOUSE',
          name: 'Tool & Die Making',
          desc: 'Desain dan fabrikasi die in-house menggunakan CAD/CAM dan mesin CNC presisi tinggi untuk semua jenis tooling.',
          specs: ['CAD/CAM', 'CNC Machining'],
          icon: 'ti-tool'
        },
        {
          tag: 'FINISHING',
          name: 'Surface Treatment',
          desc: 'Layanan electroplating, powder coating, dan phosphating untuk meningkatkan ketahanan korosi dan estetika produk.',
          specs: ['Electroplating', 'Powder Coating'],
          icon: 'ti-droplets'
        }
      ];
    });

    return {
      productList
    };
  }
};
</script>

<style scoped>
.btn-product-detail {
  display: inline-flex;
  align-items: center;
  margin-top: 20px;
  font-size: 11px;
  font-weight: 700;
  color: var(--mid);
  letter-spacing: 0.8px;
  text-decoration: none;
  transition: all 0.2s ease-in-out;
  text-transform: uppercase;
}

.btn-product-detail i {
  font-size: 9px;
  transition: transform 0.2s ease-in-out;
}

.btn-product-detail:hover {
  color: var(--accent);
}

.btn-product-detail:hover i {
  transform: translateX(3px);
}

.product-card {
  display: flex;
  flex-direction: column;
}

.product-body {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  flex-grow: 1;
}
</style>
