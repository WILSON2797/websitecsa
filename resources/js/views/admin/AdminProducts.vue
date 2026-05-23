<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Produk
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Produk
      </button>
    </div>

    <div class="admin-card">
      <div class="admin-table-container">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Gambar</th>
              <th>Nama & Tag</th>
              <th>Toleransi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" style="text-align: center;">Loading...</td>
            </tr>
            <tr v-else-if="products.length === 0">
              <td colspan="5" style="text-align: center;">Belum ada produk.</td>
            </tr>
            <tr v-for="item in products" :key="item.id">
              <td>{{ item.id }}</td>
              <td>
                <img v-if="item.img" :src="item.img" alt="product image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                <div v-else style="width: 50px; height: 50px; background: #eee; display:flex; align-items:center; justify-content:center; border-radius: 4px;">
                  <i :class="item.icon || 'ti ti-box'"></i>
                </div>
              </td>
              <td>
                <strong>{{ item.name }}</strong><br>
                <small class="text-muted">{{ item.tag }}</small>
              </td>
              <td>{{ item.tolerance || '-' }}</td>
              <td>
                <div class="action-buttons">
                  <button class="btn-edit" @click="openModal(item)">
                    <i class="ti ti-pencil"></i> Edit
                  </button>
                  <button class="btn-danger" @click="deleteItem(item.id)">
                    <i class="ti ti-trash"></i> Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ form.id ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
          <button class="close-btn" @click="closeModal">&times;</button>
        </div>
        
        <form @submit.prevent="saveItem" class="modal-body">
          <div class="form-row">
            <div class="form-group half">
              <label>Nama Produk *</label>
              <input type="text" v-model="form.name" required class="form-control">
            </div>
            <div class="form-group half">
              <label>Tag / Sub-nama *</label>
              <input type="text" v-model="form.tag" required class="form-control" placeholder="e.g. HIGH VOLUME">
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi Singkat *</label>
            <textarea v-model="form.desc" required class="form-control" rows="3"></textarea>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>Toleransi</label>
              <input type="text" v-model="form.tolerance" class="form-control">
            </div>
            <div class="form-group half">
              <label>Kapasitas (Capacity)</label>
              <input type="text" v-model="form.capacity" class="form-control">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>Kecepatan (Speed)</label>
              <input type="text" v-model="form.speed" class="form-control">
            </div>
            <div class="form-group half">
              <label>Volume</label>
              <input type="text" v-model="form.volume" class="form-control">
            </div>
          </div>

          <div class="form-group">
            <label>Auxiliary (Opsional)</label>
            <textarea v-model="form.auxiliary" class="form-control" rows="2"></textarea>
          </div>

          <div class="form-group">
            <label>Safety (Opsional)</label>
            <textarea v-model="form.safety" class="form-control" rows="2"></textarea>
          </div>

          <div class="form-group">
            <label>Typical Application (Opsional)</label>
            <textarea v-model="form.typical" class="form-control" rows="2"></textarea>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>Ikon Tabler</label>
              <IconPicker v-model="form.icon" />
            </div>
            <div class="form-group half">
              <label>Gambar Produk</label>
              <input type="file" @change="handleFileUpload" class="form-control" accept="image/*">
              <div v-if="form.previewImg" style="margin-top:10px;">
                <img :src="form.previewImg" alt="Preview" style="max-height: 80px; border-radius: 4px;">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn-cancel" @click="closeModal">Batal</button>
            <button type="submit" class="btn-primary" :disabled="saving">
              {{ saving ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import imageCompression from 'browser-image-compression';
import Swal from 'sweetalert2';
import IconPicker from '../../components/Admin/IconPicker.vue';

export default {
  name: 'AdminProducts',
  components: {
    IconPicker
  },
  setup() {
    const products = ref([]);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);
    
    const defaultForm = {
      id: null,
      name: '',
      tag: '',
      desc: '',
      icon: '',
      imgFile: null,
      previewImg: '',
      tolerance: '',
      capacity: '',
      speed: '',
      volume: '',
      auxiliary: '',
      safety: '',
      typical: ''
    };

    const form = ref({ ...defaultForm });

    const loadData = async () => {
      loading.value = true;
      try {
        const res = await axios.get('/api/admin/products');
        products.value = res.data;
      } catch (err) {
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    const openModal = (item = null) => {
      if (item) {
        form.value = { ...item, imgFile: null, previewImg: item.img };
      } else {
        form.value = { ...defaultForm };
      }
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
    };

    const handleFileUpload = async (event) => {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 15 * 1024 * 1024) {
          Swal.fire('Error', 'Ukuran gambar maksimal adalah 15MB!', 'error');
          event.target.value = '';
          return;
        }
        
        try {
          const options = {
            maxSizeMB: 1,
            maxWidthOrHeight: 1920,
            useWebWorker: true
          };
          const compressedFile = await imageCompression(file, options);
          form.value.imgFile = compressedFile;
          form.value.previewImg = URL.createObjectURL(compressedFile);
        } catch (error) {
          console.error('Compression error:', error);
          Swal.fire('Error', 'Gagal mengompres gambar sebelum diunggah.', 'error');
        }
      }
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        const formData = new FormData();
        Object.keys(form.value).forEach(key => {
          if (key !== 'imgFile' && key !== 'previewImg' && form.value[key] !== null) {
            formData.append(key, form.value[key]);
          }
        });

        if (form.value.imgFile) {
          formData.append('img', form.value.imgFile);
        }

        if (form.value.id) {
          formData.append('_method', 'PUT'); // Laravel requires PUT to be spoofed for FormData
          await axios.post(`/api/admin/products/${form.value.id}`, formData);
        } else {
          await axios.post('/api/admin/products', formData);
        }

        closeModal();
        loadData();
        Swal.fire('Sukses', 'Data produk berhasil disimpan.', 'success');
      } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Gagal menyimpan data.', 'error');
      } finally {
        saving.value = false;
      }
    };

    const deleteItem = async (id) => {
      const result = await Swal.fire({
        title: 'Konfirmasi',
        text: 'Yakin ingin menghapus produk ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/admin/products/${id}`);
          loadData();
          Swal.fire('Terhapus!', 'Produk telah dihapus.', 'success');
        } catch (err) {
          console.error(err);
          Swal.fire('Error', 'Gagal menghapus data.', 'error');
        }
      }
    };

    onMounted(() => {
      loadData();
    });

    return {
      products, loading, saving, showModal, form,
      openModal, closeModal, handleFileUpload, saveItem, deleteItem
    };
  }
}
</script>

<style scoped>
.text-muted {
  color: #6b7280;
}

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
  max-width: 800px;
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

.form-row {
  display: flex;
  gap: 15px;
}

.form-group.half {
  flex: 1;
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

@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
    gap: 0;
  }
}
</style>
