<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Galeri / Dokumentasi
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Foto
      </button>
    </div>

    <div class="admin-card">
      <DataTable
        :data="galleries"
        :columns="columns"
        :loading="loading"
        @page-change="loadData"
      />
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ form.id ? 'Edit Foto' : 'Upload Foto Baru' }}</h3>
          <button class="close-btn" @click="closeModal">&times;</button>
        </div>
        
        <form @submit.prevent="saveItem" class="modal-body">
          <div class="form-group">
            <label>Judul Dokumentasi *</label>
            <input type="text" v-model="form.title" required class="form-control" placeholder="e.g. Proses Stamping Part A">
          </div>

          <div class="form-group">
            <label>Deskripsi (Opsional)</label>
            <textarea v-model="form.description" class="form-control" rows="3" placeholder="Penjelasan singkat tentang foto ini"></textarea>
          </div>

          <div class="form-group">
            <label>Kategori *</label>
            <select v-model="form.category" required class="form-control">
              <option value="Fleet">Fleet</option>
              <option value="Facilities">Facilities</option>
              <option value="Operations">Operations</option>
              <option value="Team">Team</option>
            </select>
          </div>

          <div class="form-group">
            <label>Urutan Tampil (Display Order)</label>
            <input type="number" v-model="form.display_order" class="form-control" placeholder="0">
            <small class="text-muted">Angka lebih kecil tampil lebih dulu (kiri)</small>
          </div>

          <div class="form-group image-upload-group">
            <label>Upload Foto *</label>
            <div class="file-upload-wrapper">
              <input type="file" @change="handleFileUpload" accept="image/*" class="form-control" :required="!form.id">
            </div>
            
            <!-- Image Preview -->
            <div class="image-preview" v-if="imagePreview || form.image_url">
              <p>Preview:</p>
              <img :src="imagePreview || form.image_url" alt="Preview">
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn-cancel" @click="closeModal">Batal</button>
            <button type="submit" class="btn-primary" :disabled="saving">
              <i class="ti ti-device-floppy" v-if="!saving"></i>
              <i class="ti ti-loader ti-spin" v-else></i>
              {{ saving ? 'Menyimpan...' : 'Simpan Foto' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, h } from 'vue';
import axios from 'axios';
import DataTable from '../../components/admin/DataTable.vue';

export default {
  name: 'AdminGalleries',
  components: {
    DataTable
  },
  setup() {
    const galleries = ref([]);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);
    const imagePreview = ref(null);
    const selectedFile = ref(null);
    
    const form = ref({
      id: null,
      title: '',
      category: 'Operations',
      description: '',
      image_url: '',
      display_order: 0
    });

    const loadData = async () => {
      loading.value = true;
      try {
        const response = await axios.get('/api/admin/galleries', {
          headers: { Authorization: `Bearer ${localStorage.getItem('admin_token')}` }
        });
        galleries.value = response.data;
      } catch (error) {
        console.error('Error loading galleries:', error);
        alert('Gagal memuat data galeri');
      } finally {
        loading.value = false;
      }
    };

    onMounted(loadData);

    const openModal = (item = null) => {
      imagePreview.value = null;
      selectedFile.value = null;
      if (item) {
        form.value = { ...item };
      } else {
        form.value = { id: null, title: '', category: 'Operations', description: '', image_url: '', display_order: 0 };
      }
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
    };

    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        selectedFile.value = file;
        const reader = new FileReader();
        reader.onload = e => imagePreview.value = e.target.result;
        reader.readAsDataURL(file);
      }
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        const formData = new FormData();
        formData.append('title', form.value.title);
        formData.append('category', form.value.category);
        formData.append('description', form.value.description || '');
        formData.append('display_order', form.value.display_order || 0);
        
        if (selectedFile.value) {
          formData.append('image', selectedFile.value);
        }

        const config = {
          headers: { 
            'Content-Type': 'multipart/form-data',
            Authorization: `Bearer ${localStorage.getItem('admin_token')}`
          }
        };

        if (form.value.id) {
          formData.append('_method', 'PUT'); // For Laravel Put with multipart
          await axios.post(`/api/admin/galleries/${form.value.id}`, formData, config);
        } else {
          await axios.post('/api/admin/galleries', formData, config);
        }
        
        closeModal();
        await loadData();
      } catch (error) {
        console.error('Error saving gallery:', error);
        alert('Gagal menyimpan foto. Cek ukuran max 5MB.');
      } finally {
        saving.value = false;
      }
    };

    const deleteItem = async (id) => {
      if (confirm('Yakin ingin menghapus foto ini secara permanen?')) {
        try {
          await axios.delete(`/api/admin/galleries/${id}`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('admin_token')}` }
          });
          await loadData();
        } catch (error) {
          console.error('Error deleting gallery:', error);
          alert('Gagal menghapus data');
        }
      }
    };

    const columns = [
      { accessorKey: 'id', header: 'ID', meta: { width: '60px' } },
      { 
        id: 'image_url',
        accessorKey: 'image_url', 
        header: 'Preview', 
        meta: { width: '100px', noSearch: true },
        cell: ({ row }) => {
          const item = row.original;
          return h('div', { class: 'table-img-preview' }, [
            h('img', { 
              src: item.image_url, 
              style: 'width: 60px; height: 60px; object-fit: cover; border-radius: 6px;' 
            })
          ]);
        }
      },
      { accessorKey: 'title', header: 'Judul Dokumentasi' },
      { accessorKey: 'category', header: 'Kategori', meta: { width: '120px' } },
      { accessorKey: 'display_order', header: 'Urutan', meta: { width: '80px' } },
      { 
        id: 'actions', 
        header: 'Aksi', 
        meta: { width: '100px', noSearch: true },
        cell: ({ row }) => {
          const item = row.original;
          return h('div', { class: 'action-buttons' }, [
            h('button', { 
              class: 'btn-edit',
              title: 'Edit',
              onClick: () => openModal(item)
            }, [h('i', { class: 'ti ti-pencil' })]),
            h('button', { 
              class: 'btn-danger',
              title: 'Hapus',
              onClick: () => deleteItem(item.id)
            }, [h('i', { class: 'ti ti-trash' })])
          ]);
        }
      }
    ];

    return {
      galleries,
      columns,
      loading,
      saving,
      showModal,
      form,
      imagePreview,
      loadData,
      openModal,
      closeModal,
      handleFileUpload,
      saveItem
    };
  }
}
</script>

<style scoped>
/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 8px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

.modal-header {
  padding: 15px 20px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  color: #111827;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6b7280;
}

.modal-body {
  padding: 20px;
  overflow-y: auto;
  flex: 1;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
  color: #374151;
}

.form-control {
  width: 100%;
  padding: 10px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-family: inherit;
  box-sizing: border-box;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
}

.modal-footer {
  padding: 15px 20px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.text-muted {
  color: #6b7280;
  font-size: 0.85rem;
  margin-top: 5px;
  display: block;
}

.btn-cancel {
  background: white;
  border: 1px solid #d1d5db;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  color: #374151;
}

.btn-cancel:hover {
  background: #f9fafb;
}

.image-upload-group { margin-bottom: 20px; }
.image-preview { margin-top: 15px; text-align: center; background: #f9fafb; padding: 10px; border-radius: 8px; border: 1px dashed #d1d5db; }
.image-preview img { max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 4px; }
</style>
