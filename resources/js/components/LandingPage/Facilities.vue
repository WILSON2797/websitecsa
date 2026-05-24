<template>
  <!-- FACILITIES SECTION -->
  <section id="facilities" class="section">
    <!-- Visual background blueprint accents -->
    <div class="facilities-grid-accent"></div>

    <div class="section-inner">
      <div class="section-header" data-aos="fade-down">
        <div class="section-eyebrow">FASILITAS PENDUKUNG</div>
        <h2 class="section-title">Infrastruktur & Layanan</h2>
        <p class="section-sub">Mendukung kelancaran proses produksi dengan standar industri terbaik dari tahap perancangan hingga jaminan mutu.</p>
      </div>
      
      <!-- Facilities Cards Grid (2-4 Columns depending on data) -->
      <div class="facility-grid">
        <div v-for="(fac, idx) in facilitiesData" :key="fac.id || idx" data-aos="fade-up" :data-aos-delay="idx * 150">
          <div class="facility-card-new">
          <!-- Card Image Slot -->
          <div class="facility-img-slot">
            <div class="facility-grid-bg"></div>
            <img v-if="fac.img" :src="fac.img" class="facility-real-img" :alt="fac.name" loading="lazy" decoding="async" />
            <div v-else class="facility-placeholder-graphic">
              <i :class="fac.icon || 'ti ti-building'"></i>
            </div>
          </div>

          <!-- Card Body Details -->
          <div class="facility-body">
            <h3 class="facility-card-title">{{ fac.name }}</h3>
            <p class="facility-card-desc">{{ fac.short_desc || fac.long_desc }}</p>
            
              <router-link :to="'/facility/' + (fac.id || idx)" class="btn-facility-detail">
                View Details <i class="ti ti-chevron-right ms-1"></i>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

export default {
  name: 'Facilities',
  props: {
    facilities: {
      type: Array,
      required: false,
      default: () => null
    }
  },
  setup(props) {
    const baseFacilities = [
      {
        id: 'produksi',
        name: 'Area Produksi',
        short_desc: 'Area stamping dan assembling utama dengan tata letak optimal.',
        icon: 'ti-settings-2'
      },
      {
        id: 'toolroom',
        name: 'Tool Room',
        short_desc: 'Perawatan, perbaikan, dan modifikasi dies (cetakan) secara mandiri.',
        icon: 'ti-tool'
      },
      {
        id: 'gudang',
        name: 'Gudang',
        short_desc: 'Penyimpanan bahan baku dan barang jadi dengan manajemen inventory yang baik.',
        icon: 'ti-package'
      },
      {
        id: 'qclab',
        name: 'QC Lab',
        short_desc: 'Laboratorium jaminan mutu dengan alat ukur presisi tinggi (CMM).',
        icon: 'ti-microscope'
      }
    ];

    const facilitiesData = ref([...baseFacilities]);

    watch(() => props.facilities, (newVal) => {
      if (newVal && newVal.length > 0) {
        facilitiesData.value = newVal;
      }
    }, { immediate: true });

    const fetchFacilities = async () => {
      if (props.facilities && props.facilities.length > 0) return;
      
      try {
        const res = await axios.get('/api/facilities');
        if (res.data && res.data.length > 0) {
          facilitiesData.value = res.data;
        }
      } catch (err) {
        console.error('Error fetching facilities:', err);
      }
    };

    onMounted(() => {
      fetchFacilities();
    });

    return {
      facilitiesData
    };
  }
};
</script>

<style scoped>
#facilities {
  background-color: var(--white) !important;
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid var(--gray100);
}

.facilities-grid-accent {
  position: absolute;
  inset: 0;
  opacity: 0.02;
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

/* Facility Grid Layout */
.facility-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
  margin-bottom: 48px;
}

/* Facility Cards (Pristine Light Theme) */
.facility-card-new {
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

.facility-card-new:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(15, 42, 66, 0.08);
  border-color: var(--accent);
}

/* Image/Illustration Slot */
.facility-img-slot {
  height: 160px;
  background: var(--navy);
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
}

.facility-grid-bg {
  position: absolute;
  inset: 0;
  opacity: 0.08;
  background-image: 
    linear-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
  background-size: 20px 20px;
  pointer-events: none;
}

.facility-real-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: relative;
  z-index: 2;
}

.facility-placeholder-graphic {
  position: relative;
  z-index: 1;
  font-size: 52px;
  color: var(--light);
  opacity: 0.85;
  transition: transform 0.3s ease;
}

.facility-card-new:hover .facility-placeholder-graphic {
  transform: scale(1.1);
}

/* Card Body */
.facility-body {
  padding: 24px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.facility-card-title {
  font-family: var(--font-head);
  font-size: 18px;
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 12px;
}

.facility-card-desc {
  font-size: 13px;
  color: var(--gray500);
  line-height: 1.6;
  margin-bottom: 20px;
  flex-grow: 1;
}

.btn-facility-detail {
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
  text-decoration: none;
}

.btn-facility-detail:hover {
  background: var(--navy);
  color: var(--white);
  border-color: var(--navy);
  transform: translateX(2px);
}

/* Responsive Overrides */
@media (max-width: 992px) {
  .facility-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .facility-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .facility-img-slot {
    height: 100px;
  }
  .facility-card-title {
    font-size: 14px;
    margin-bottom: 6px;
  }
  .facility-card-desc {
    font-size: 11px;
    margin-bottom: 12px;
  }
  .btn-facility-detail {
    padding: 6px 12px;
    font-size: 10px;
  }
  .facility-placeholder-graphic {
    font-size: 32px;
  }
  .facility-body {
    padding: 16px;
  }
}
</style>
