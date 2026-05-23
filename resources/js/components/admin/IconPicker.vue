<template>
  <div class="icon-picker-container" ref="pickerContainer">
    <div class="selected-icon-display" @click="toggleDropdown">
      <div class="icon-preview">
        <i :class="['ti', modelValue || 'ti-circle-dot']"></i>
      </div>
      <div class="icon-name">{{ modelValue || 'Pilih Ikon...' }}</div>
      <i class="ti ti-chevron-down dropdown-arrow"></i>
    </div>

    <div v-if="isOpen" class="icon-dropdown">
      <div class="icon-search">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Cari ikon..." 
          class="form-control"
          @click.stop
        >
      </div>
      <div class="icon-grid">
        <div 
          v-for="icon in filteredIcons" 
          :key="icon" 
          :class="['icon-item', { active: modelValue === icon }]"
          @click="selectIcon(icon)"
          :title="icon"
        >
          <i :class="['ti', icon]"></i>
        </div>
        <div v-if="filteredIcons.length === 0" class="no-icons">
          Ikon tidak ditemukan.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

export default {
  name: 'IconPicker',
  props: {
    modelValue: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const isOpen = ref(false);
    const searchQuery = ref('');
    const pickerContainer = ref(null);

    // Daftar ikon Tabler yang relevan dengan manufaktur & korporat
    const iconsList = [
      'ti-box', 'ti-settings-2', 'ti-microscope', 'ti-scan', 'ti-temperature', 
      'ti-package', 'ti-certificate', 'ti-car', 'ti-leaf', 'ti-award', 
      'ti-map-pin', 'ti-users', 'ti-building-factory-2', 'ti-adjustments-horizontal', 
      'ti-arrow-down-circle', 'ti-scissors', 'ti-repeat', 'ti-tool', 'ti-droplets', 
      'ti-droplet-half-2', 'ti-cpu', 'ti-sparkles', 'ti-robot', 'ti-check', 
      'ti-star', 'ti-shield', 'ti-truck', 'ti-briefcase', 'ti-world', 
      'ti-bulb', 'ti-plug', 'ti-camera', 'ti-phone', 'ti-mail', 'ti-info-circle',
      'ti-layout-navbar', 'ti-address-book', 'ti-heading', 'ti-circle-dot',
      'ti-assembly', 'ti-crane', 'ti-engine', 'ti-forklift', 'ti-manual-gearbox',
      'ti-steering-wheel', 'ti-tire', 'ti-tools'
    ];

    const filteredIcons = computed(() => {
      if (!searchQuery.value) return iconsList;
      const lowerQuery = searchQuery.value.toLowerCase();
      return iconsList.filter(icon => icon.toLowerCase().includes(lowerQuery));
    });

    const toggleDropdown = () => {
      isOpen.value = !isOpen.value;
      if (isOpen.value) {
        searchQuery.value = '';
      }
    };

    const selectIcon = (icon) => {
      emit('update:modelValue', icon);
      isOpen.value = false;
    };

    const closeDropdown = (e) => {
      if (pickerContainer.value && !pickerContainer.value.contains(e.target)) {
        isOpen.value = false;
      }
    };

    onMounted(() => {
      document.addEventListener('click', closeDropdown);
    });

    onBeforeUnmount(() => {
      document.removeEventListener('click', closeDropdown);
    });

    return {
      isOpen,
      searchQuery,
      pickerContainer,
      filteredIcons,
      toggleDropdown,
      selectIcon
    };
  }
};
</script>

<style scoped>
.icon-picker-container {
  position: relative;
  width: 100%;
}

.selected-icon-display {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: white;
  cursor: pointer;
  transition: all 0.2s;
}

.selected-icon-display:hover {
  border-color: #3b82f6;
}

.icon-preview {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3f4f6;
  border-radius: 4px;
  color: #111827;
  font-size: 1.1rem;
}

.icon-name {
  flex: 1;
  font-size: 0.95rem;
  color: #374151;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.dropdown-arrow {
  color: #6b7280;
  font-size: 1rem;
}

.icon-dropdown {
  position: absolute;
  top: calc(100% + 5px);
  left: 0;
  width: 100%;
  min-width: 250px;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  z-index: 1000;
  padding: 10px;
}

.icon-search {
  margin-bottom: 10px;
}

.icon-search input {
  padding: 8px 12px;
  font-size: 0.9rem;
}

.icon-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
  gap: 5px;
  max-height: 200px;
  overflow-y: auto;
  padding-right: 5px;
}

.icon-grid::-webkit-scrollbar {
  width: 5px;
}
.icon-grid::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.icon-grid::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 5px;
}

.icon-item {
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1.25rem;
  color: #4b5563;
  transition: all 0.15s;
}

.icon-item:hover {
  background: #eff6ff;
  color: #2563eb;
}

.icon-item.active {
  background: #3b82f6;
  color: white;
}

.no-icons {
  grid-column: 1 / -1;
  text-align: center;
  padding: 20px 0;
  color: #6b7280;
  font-size: 0.9rem;
}
</style>
