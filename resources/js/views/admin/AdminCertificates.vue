<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Sertifikat
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Sertifikat
      </button>
    </div>

    <div class="admin-card">
      <div class="admin-table-container">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Urutan</th>
              <th>Gambar / Ikon</th>
              <th>Nama Sertifikat</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="4" style="text-align: center;">Loading...</td>
            </tr>
            <tr v-else-if="certificates.length === 0">
              <td colspan="4" style="text-align: center;">Belum ada sertifikat.</td>
            </tr>
            <tr v-for="item in certificates" :key="item.id">
              <td>{{ item.order }}</td>
              <td>
                <img v-if="item.img" :src="item.img" alt="certificate image" style="width: 50px; height: 50px; object-fit: contain; border-radius: 4px; background: #fff; border: 1px solid #eee;">
                <div v-else style="width: 50px; height: 50px; background: #eee; display:flex; align-items:center; justify-content:center; border-radius: 4px;">
                  <i :class="['ti', item.icon || 'ti-certificate']"></i>
                </div>
              </td>
              <td>
                <strong>{{ item.name }}</strong><br>
                <small class="text-muted">{{ item.desc || '-' }}</small>
              </td>
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
          <h3>{{ form.id ? 'Edit Sertifikat' : 'Tambah Sertifikat Baru' }}</h3>
          <button class="close-btn" @click="closeModal">&times;</button>
        </div>
        
        <form @submit.prevent="saveItem" class="modal-body">
          <div class="form-row">
            <div class="form-group half">
              <label>Nama Sertifikat *</label>
              <input type="text" v-model="form.name" required class="form-control" placeholder="Contoh: ISO 9001:2015">
            </div>
            <div class="form-group half">
              <label>Urutan Tampil (Opsional)</label>
              <input type="number" v-model="form.order" class="form-control" placeholder="0">
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi (Opsional)</label>
            <textarea v-model="form.desc" class="form-control" rows="3"></textarea>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>Foto/Gambar Sertifikat (Opsional)</label>
              <input type="file" ref="fileInput" @change="handleFileChange" accept="image/*" class="form-control">
              <small class="text-muted">Maksimal 15MB. Akan dikompresi otomatis.</small>
              
              <div v-if="imagePreview || form.img" class="mt-2">
                <img :src="imagePreview || form.img" alt="Preview" style="max-height: 100px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
              </div>
            </div>
            <div class="form-group half">
              <label>Atau Ikon (Jika tidak ada gambar)</label>
              <IconPicker v-model="form.icon" />
              <small class="text-muted">Gunakan ikon jika tidak ada foto unggahan.</small>
            </div>
          </div>
          
          <div class="modal-footer mt-4">
            <button type="button" class="btn-ghost" @click="closeModal">Batal</button>
            <button type="submit" class="btn-primary" :disabled="saving">
              {{ saving ? 'Menyimpan...' : 'Simpan Sertifikat' }}
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
import Swal from 'sweetalert2';
import IconPicker from '../../components/Admin/IconPicker.vue';

export default {
  name: 'AdminCertificates',
  components: {
    IconPicker
  },
  setup() {
    const certificates = ref([]);
    const loading = ref(true);
    const showModal = ref(false);
    const saving = ref(false);
    const fileInput = ref(null);
    const imageFile = ref(null);
    const imagePreview = ref(null);
    
    const form = ref({
      id: null,
      name: '',
      desc: '',
      icon: 'ti-certificate',
      order: 0,
      img: null
    });

    const loadData = async () => {
      loading.value = true;
      try {
        const response = await axios.get('/api/admin/certificates');
        certificates.value = response.data;
      } catch (error) {
        console.error('Error loading certificates', error);
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      loadData();
    });

    const resetForm = () => {
      form.value = {
        id: null,
        name: '',
        desc: '',
        icon: 'ti-certificate',
        order: 0,
        img: null
      };
      imageFile.value = null;
      imagePreview.value = null;
      if (fileInput.value) {
        fileInput.value.value = '';
      }
    };

    const openModal = (item = null) => {
      resetForm();
      if (item) {
        form.value = { ...item };
      }
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
      resetForm();
    };

    const handleFileChange = (e) => {
      const file = e.target.files[0];
      if (file) {
        // Validation size (15MB)
        if (file.size > 15 * 1024 * 1024) {
          Swal.fire('Error', 'Ukuran file maksimal adalah 15MB', 'error');
          e.target.value = '';
          return;
        }
        imageFile.value = file;
        
        // Preview
        const reader = new FileReader();
        reader.onload = (event) => {
          imagePreview.value = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    };

    const saveItem = async () => {
      saving.value = true;
      try {
        const formData = new FormData();
        formData.append('name', form.value.name);
        formData.append('desc', form.value.desc || '');
        formData.append('icon', form.value.icon || '');
        formData.append('order', form.value.order || 0);
        
        if (imageFile.value) {
          formData.append('img', imageFile.value);
        }

        if (form.value.id) {
          formData.append('_method', 'PUT'); // Laravel spoofing
          await axios.post(`/api/admin/certificates/${form.value.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          Swal.fire({
            icon: 'success',
            title: 'Tersimpan',
            text: 'Sertifikat berhasil diubah!',
            timer: 1500,
            showConfirmButton: false
          });
        } else {
          await axios.post('/api/admin/certificates', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Sertifikat baru ditambahkan!',
            timer: 1500,
            showConfirmButton: false
          });
        }
        closeModal();
        loadData();
      } catch (error) {
        console.error('Error saving item', error);
        Swal.fire('Error', 'Gagal menyimpan sertifikat.', 'error');
      } finally {
        saving.value = false;
      }
    };

    const deleteItem = async (id) => {
      const result = await Swal.fire({
        title: 'Hapus Sertifikat?',
        text: 'Data ini tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!'
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/admin/certificates/${id}`);
          Swal.fire({
            icon: 'success',
            title: 'Terhapus',
            text: 'Sertifikat telah dihapus.',
            timer: 1500,
            showConfirmButton: false
          });
          loadData();
        } catch (error) {
          console.error('Error deleting item', error);
          Swal.fire('Error', 'Gagal menghapus sertifikat.', 'error');
        }
      }
    };

    return {
      certificates,
      loading,
      showModal,
      saving,
      form,
      fileInput,
      imagePreview,
      openModal,
      closeModal,
      saveItem,
      deleteItem,
      handleFileChange
    };
  }
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: #fff;
  border-radius: 8px;
  width: 100%;
  max-width: 700px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.modal-header {
  padding: 20px;
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

.close-btn:hover {
  color: #111827;
}

.modal-body {
  padding: 20px;
  overflow-y: auto;
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
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.95rem;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-row {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.form-group.half {
  flex: 1;
  min-width: 250px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
  border-top: 1px solid #e5e7eb;
  padding-top: 20px;
}

.btn-ghost {
  background: transparent;
  border: 1px solid #d1d5db;
  color: #4b5563;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.btn-ghost:hover {
  background: #f3f4f6;
}
</style>
