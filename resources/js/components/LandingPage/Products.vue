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
          <div class="product-img" style="height: 160px; overflow: hidden; position: relative;">
            <img :src="getProductImg(prod, idx)" class="product-photo" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s ease;" :alt="prod.name" loading="lazy" decoding="async" />
            <div class="product-img-bg" style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent, rgba(15,42,66,0.25)); pointer-events: none;"></div>
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
              View Specifications <i class="ti ti-chevron-right ms-1"></i>
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

    const getProductImg = (prod, idx) => {
      if (prod.img) return prod.img;
      const fallbacks = [
        'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=800&auto=format&fit=crop', // Progressive Stamping
        'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=800&auto=format&fit=crop', // Deep Drawing
        'https://images.unsplash.com/photo-1535813547-99c456a41d4a?q=80&w=800&auto=format&fit=crop', // Fine Blanking
        'https://images.unsplash.com/photo-1616348436168-de43ad0db179?q=80&w=800&auto=format&fit=crop', // Transfer Stamping
        'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?q=80&w=800&auto=format&fit=crop', // Tool & Die Making
        'https://images.unsplash.com/photo-1563770660941-20978e870e26?q=80&w=800&auto=format&fit=crop'  // Surface Treatment
      ];
      return fallbacks[idx] || fallbacks[0];
    };

    return {
      productList,
      getProductImg
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

.product-card:hover .product-photo {
  transform: scale(1.05);
}
</style>
