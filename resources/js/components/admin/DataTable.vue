<template>
  <div class="data-table-wrapper">
    <div class="admin-table-container">
      <table class="admin-table">
        <thead>
          <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <th 
              v-for="header in headerGroup.headers" 
              :key="header.id"
              :style="{ width: header.column.columnDef.meta?.width || 'auto' }"
            >
              <template v-if="!header.isPlaceholder">
                <FlexRender :render="header.column.columnDef.header" :props="header.getContext()" />
              </template>
            </th>
          </tr>
          <!-- Search row -->
          <tr class="search-row">
            <th 
              v-for="header in table.getHeaderGroups()[0]?.headers" 
              :key="'search-' + header.id"
            >
              <input
                v-if="header.column.columnDef.meta?.searchable !== false && !header.column.columnDef.meta?.noSearch"
                type="text"
                class="column-search-input"
                :placeholder="'Cari...'"
                :value="searchValues[header.column.id] || ''"
                @input="onSearchInput(header.column.id, $event.target.value)"
              />
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td :colspan="columns.length" class="text-center py-4">
              <div class="spinner-container">
                <span class="loading-text">Memuat data...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="table.getRowModel().rows.length === 0">
            <td :colspan="columns.length" class="text-center py-4 text-muted">
              Tidak ada data yang tersedia.
            </td>
          </tr>
          <template v-else>
            <tr v-for="row in table.getRowModel().rows" :key="row.id">
              <td v-for="cell in row.getVisibleCells()" :key="cell.id">
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Pagination Controls -->
    <div v-if="pagination && pagination.total > 0" class="table-pagination-container">
      <div class="pagination-info">
        Menampilkan <span>{{ pagination.from || 0 }}</span> sampai <span>{{ pagination.to || 0 }}</span> dari <span>{{ pagination.total }}</span> entri
      </div>
      <div class="pagination-controls">
        <button 
          class="btn-page-nav" 
          :disabled="pagination.current_page === 1" 
          @click="changePage(pagination.current_page - 1)"
        >
          <i class="ti ti-chevron-left"></i>
        </button>

        <template v-for="page in displayedPages" :key="page">
          <span v-if="page === '...'" class="page-ellipsis">...</span>
          <button 
            v-else 
            class="btn-page" 
            :class="{ active: pagination.current_page === page }"
            @click="changePage(page)"
          >
            {{ page }}
          </button>
        </template>

        <button 
          class="btn-page-nav" 
          :disabled="pagination.current_page === pagination.last_page" 
          @click="changePage(pagination.current_page + 1)"
        >
          <i class="ti ti-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive } from 'vue';
import { useVueTable, getCoreRowModel, FlexRender } from '@tanstack/vue-table';

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
  columns: {
    type: Array,
    required: true,
  },
  pagination: {
    type: Object,
    default: null,
  },
  loading: {
    type: Boolean,
    default: false,
  }
});

const emit = defineEmits(['page-change', 'search']);

const searchValues = reactive({});
let debounceTimer = null;

const table = useVueTable({
  get data() { return props.data; },
  get columns() { return props.columns; },
  getCoreRowModel: getCoreRowModel(),
  manualPagination: true,
});

const onSearchInput = (columnId, value) => {
  searchValues[columnId] = value;
  
  // Debounce: emit search after 400ms of no typing
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    emit('search', { ...searchValues });
  }, 400);
};

const changePage = (page) => {
  if (page >= 1 && page <= props.pagination.last_page) {
    emit('page-change', page);
  }
};

// Generates list of pages to show (e.g. [1, 2, '...', 9, 10])
const displayedPages = computed(() => {
  if (!props.pagination) return [];
  const current = props.pagination.current_page;
  const last = props.pagination.last_page;
  
  const pages = [];
  const delta = 2;
  
  for (let i = 1; i <= last; i++) {
    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
      pages.push(i);
    } else if (pages[pages.length - 1] !== '...') {
      pages.push('...');
    }
  }
  return pages;
});
</script>

<style scoped>
.data-table-wrapper {
  background: white;
  width: 100%;
}

.text-center {
  text-align: center;
}

.py-4 {
  padding-top: 1.5rem;
  padding-bottom: 1.5rem;
}

.spinner-container {
  display: flex;
  justify-content: center;
  align-items: center;
  color: #3b82f6;
}

.loading-text {
  font-weight: 500;
  margin-left: 8px;
  color: #6b7280;
}

/* Search row */
.search-row th {
  padding: 6px 10px 10px;
  background-color: #f9fafb;
  border-bottom: 2px solid #e5e7eb;
}

.column-search-input {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid #d1d5db;
  border-radius: 5px;
  font-size: 0.8rem;
  color: #374151;
  background: white;
  transition: border-color 0.2s, box-shadow 0.2s;
  box-sizing: border-box;
}

.column-search-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
}

.column-search-input::placeholder {
  color: #9ca3af;
  font-weight: 400;
}

/* Pagination container styles */
.table-pagination-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-top: 1px solid #e5e7eb;
  background: #f9fafb;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
  font-size: 0.875rem;
  color: #4b5563;
}

.pagination-info span {
  font-weight: 600;
  color: #111827;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.btn-page, .btn-page-nav {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 2rem;
  height: 2rem;
  padding: 0 6px;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
  background: white;
  color: #374151;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.875rem;
}

.btn-page:hover:not(:disabled), .btn-page-nav:hover:not(:disabled) {
  background: #f3f4f6;
  border-color: #9ca3af;
  color: #111827;
}

.btn-page.active {
  background: #2563eb;
  border-color: #2563eb;
  color: white;
}

.btn-page:disabled, .btn-page-nav:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f3f4f6;
  color: #9ca3af;
}

.page-ellipsis {
  padding: 0 0.5rem;
  color: #9ca3af;
}

@media (max-width: 640px) {
  .table-pagination-container {
    flex-direction: column;
    gap: 1rem;
    align-items: center;
    text-align: center;
  }
}
</style>
