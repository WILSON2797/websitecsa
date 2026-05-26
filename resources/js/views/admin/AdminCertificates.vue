<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Sertifikat
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Sertifikat
      </button>
    </div>

    <div class="admin-card">
      <DataTable
        :data="certificates"
        :columns="columns"
        :pagination="pagination"
        :loading="loading"
        @page-change="loadData"
        @search="handleSearch"
      />
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
              
              <div v-if="imagePreview || form.img" class="mt-2" style="display: flex; align-items: flex-start;">
                <div style="position: relative; display: inline-block; width: max-content;">
                  <img :src="imagePreview || form.img" alt="Preview" style="max-height: 100px; border-radius: 4px; border: 1px solid #ddd; padding: 2px; display: block;">
                  <button type="button" @click="removeImage" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="ti ti-x"></i>
                  </button>
                </div>
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
import { ref, onMounted, h } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import imageCompression from 'browser-image-compression';
import IconPicker from '../../components/Admin/IconPicker.vue';
import DataTable from '../../components/admin/DataTable.vue';

export default {
  name: 'AdminCertificates',
  components: {
    IconPicker,
    DataTable
  },
  setup() {
    const certificates = ref([]);
    const pagination = ref(null);
    const loading = ref(true);
    const showModal = ref(false);
    const saving = ref(false);
    const fileInput = ref(null);
    const imageFile = ref(null);
    const imagePreview = ref(null);

    const columns = [
      {
        id: 'no',
        header: 'No',
        meta: { width: '60px', noSearch: true },
        cell: ({ row }) => {
          const from = pagination.value?.from || 1;
          return from + row.index;
        }
      },
      {
        accessorKey: 'img',
        header: 'Gambar / Ikon',
        meta: { width: '100px' },
        cell: ({ row }) => {
          const item = row.original;
          if (item.img) {
            return h('img', {
              src: item.img,
              alt: 'certificate image',
              style: { width: '50px', height: '50px', objectFit: 'contain', borderRadius: '4px', background: '#fff', border: '1px solid #eee' }
            });
          } else {
            return h('div', {
              style: {
                width: '50px',
                height: '50px',
                background: '#eee',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                borderRadius: '4px'
              }
            }, [
              h('i', { class: ['ti', item.icon || 'ti-certificate'] })
            ]);
          }
        }
      },
      {
        accessorKey: 'name',
        header: 'Nama Sertifikat',
        cell: ({ row }) => {
          const item = row.original;
          return h('div', {}, [
            h('strong', {}, item.name),
            h('br'),
            h('small', { class: 'text-muted' }, item.desc || '-')
          ]);
        }
      },
      {
        id: 'actions',
        header: 'Action',
        meta: { width: '100px', noSearch: true },
        cell: ({ row }) => {
          const item = row.original;
          return h('div', { class: 'action-buttons' }, [
            h('button', {
              class: 'btn-edit',
              title: 'Edit',
              onClick: () => openModal(item)
            }, [
              h('i', { class: 'ti ti-pencil' })
            ]),
            h('button', {
              class: 'btn-danger',
              title: 'Hapus',
              onClick: () => deleteItem(item.id)
            }, [
              h('i', { class: 'ti ti-trash' })
            ])
          ]);
        }
      }
    ];
    
    const form = ref({
      id: null,
      name: '',
      desc: '',
      icon: 'ti-certificate',
      order: 0,
      img: null,
      remove_img: 0
    });

    const searchQueries = ref({});

    const loadData = async (page = 1) => {
      loading.value = true;
      try {
        const params = { page };
        if (searchQueries.value) {
          Object.keys(searchQueries.value).forEach(key => {
            if (searchQueries.value[key]) {
              params[`search[${key}]`] = searchQueries.value[key];
            }
          });
        }
        const response = await axios.get('/api/admin/certificates', { params });
        certificates.value = response.data.data;
        pagination.value = {
          current_page: response.data.current_page,
          last_page: response.data.last_page,
          total: response.data.total,
          per_page: response.data.per_page,
          from: response.data.from,
          to: response.data.to
        };
      } catch (error) {
        console.error('Error loading certificates', error);
      } finally {
        loading.value = false;
      }
    };

    const handleSearch = (newSearchQueries) => {
      searchQueries.value = newSearchQueries;
      loadData(1);
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
        img: null,
        remove_img: 0
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
        form.value = { ...item, remove_img: 0 };
      }
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
      resetForm();
    };

    const handleFileChange = async (e) => {
      const file = e.target.files[0];
      if (file) {
        // Validation size (15MB)
        if (file.size > 15 * 1024 * 1024) {
          Swal.fire('Error', 'Ukuran file maksimal adalah 15MB', 'error');
          e.target.value = '';
          return;
        }

        Swal.fire({
          title: 'Memproses Gambar...',
          text: 'Harap tunggu, gambar sedang dikompresi.',
          allowOutsideClick: false,
          didOpen: () => { Swal.showLoading(); }
        });

        try {
          const options = {
            maxSizeMB: 1,
            maxWidthOrHeight: 1920,
            useWebWorker: true
          };
          const compressedFile = await imageCompression(file, options);
          imageFile.value = compressedFile;
          imagePreview.value = URL.createObjectURL(compressedFile);
          form.value.remove_img = 0;
          Swal.close();
        } catch (error) {
          console.error('Compression error:', error);
          Swal.fire('Error', 'Gagal mengompres gambar sebelum diunggah.', 'error');
        }
      }
    };

    const removeImage = () => {
      imageFile.value = null;
      imagePreview.value = null;
      form.value.img = null;
      form.value.remove_img = 1;
      if (fileInput.value) {
        fileInput.value.value = '';
      }
    };

    const saveItem = async () => {
      saving.value = true;
      Swal.fire({
        title: 'Menyimpan Data...',
        text: 'Sedang mengunggah data ke server.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });
      try {
        const formData = new FormData();
        formData.append('name', form.value.name);
        formData.append('desc', form.value.desc || '');
        formData.append('icon', form.value.icon || '');
        formData.append('order', form.value.order || 0);
        
        if (imageFile.value) {
          formData.append('img', imageFile.value);
        }
        if (form.value.remove_img) {
          formData.append('remove_img', 1);
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
        let errorMsg = 'Gagal menyimpan sertifikat.';
        if (error.response && error.response.data && error.response.data.message) {
          errorMsg = error.response.data.message;
          if (error.response.data.errors) {
            const firstError = Object.values(error.response.data.errors)[0][0];
            errorMsg += ': ' + firstError;
          }
        }
        Swal.fire('Error', errorMsg, 'error');
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
      columns,
      pagination,
      openModal,
      closeModal,
      removeImage,
      saveItem,
      deleteItem,
      handleFileChange,
      handleSearch
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
