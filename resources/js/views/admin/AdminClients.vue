<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Klien
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Klien
      </button>
    </div>

    <div class="admin-card">
      <DataTable
        :data="clients"
        :columns="columns"
        :pagination="pagination"
        :loading="loading"
        @page-change="loadData"
        @search="handleSearch"
      />
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
          <h3>{{ form.id ? 'Edit Klien' : 'Tambah Klien Baru' }}</h3>
          <button class="close-btn" @click="closeModal">&times;</button>
        </div>
        
        <form @submit.prevent="saveItem" class="modal-body">
          <div class="form-group">
            <label>Nama Perusahaan *</label>
            <input type="text" v-model="form.name" required class="form-control" placeholder="PT Contoh Maju">
          </div>
          
          <div class="form-group">
            <label>Industri *</label>
            <input type="text" v-model="form.industry" required class="form-control" placeholder="Otomotif">
          </div>

            <div class="form-group">
              <label>Logo Klien</label>
              <input type="file" @change="handleFileUpload" class="form-control" accept="image/*">
              <div v-if="form.previewImg" style="margin-top:15px; display: flex; align-items: flex-start; justify-content: center; background: #f9fafb; padding: 15px; border-radius: 4px;">
                <div style="position: relative; display: inline-block; width: max-content;">
                  <img :src="form.previewImg" alt="Preview" style="max-height: 60px; max-width: 100%; object-fit: contain; display: block; border-radius: 4px;">
                  <button type="button" @click="removeImage" style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="ti ti-x"></i>
                  </button>
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
import { ref, onMounted, h } from 'vue';
import axios from 'axios';
import imageCompression from 'browser-image-compression';
import Swal from 'sweetalert2';
import DataTable from '../../components/admin/DataTable.vue';

export default {
  name: 'AdminClients',
  components: {
    DataTable
  },
  setup() {
    const clients = ref([]);
    const pagination = ref(null);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);

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
        accessorKey: 'logo_path',
        header: 'Logo',
        meta: { width: '100px' },
        cell: ({ row }) => {
          const item = row.original;
          if (item.logo_path) {
            return h('img', {
              src: item.logo_path,
              alt: 'logo',
              style: { maxWidth: '60px', maxHeight: '40px', objectFit: 'contain' }
            });
          } else {
            return h('div', {
              style: {
                width: '40px',
                height: '40px',
                background: '#eee',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                borderRadius: '4px'
              }
            }, [
              h('i', { class: 'ti ti-building-factory-2' })
            ]);
          }
        }
      },
      {
        accessorKey: 'name',
        header: 'Nama Perusahaan',
        cell: ({ row }) => h('strong', {}, row.original.name)
      },
      {
        accessorKey: 'industry',
        header: 'Industri'
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
    
    const defaultForm = {
      id: null,
      name: '',
      industry: '',
      imgFile: null,
      previewImg: ''
    };

    const form = ref({ ...defaultForm });
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
        const res = await axios.get(`/api/admin/clients`, { params });
        clients.value = res.data.data;
        pagination.value = {
          current_page: res.data.current_page,
          last_page: res.data.last_page,
          total: res.data.total,
          per_page: res.data.per_page,
          from: res.data.from,
          to: res.data.to
        };
      } catch (err) {
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    const handleSearch = (newSearchQueries) => {
      searchQueries.value = newSearchQueries;
      loadData(1);
    };

      const openModal = (item = null) => {
        if (item) {
          form.value = { ...item, imgFile: null, previewImg: item.logo_path, remove_img: 0 };
        } else {
          form.value = { ...defaultForm, remove_img: 0 };
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

      const removeImage = () => {
        form.value.imgFile = null;
        form.value.previewImg = '';
        form.value.remove_img = 1;
        const fileInput = document.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = '';
      };

      const saveItem = async () => {
      saving.value = true;
      try {
        const formData = new FormData();
        formData.append('name', form.value.name);
        formData.append('industry', form.value.industry);

          if (form.value.imgFile) {
            formData.append('logo_path', form.value.imgFile);
          }
          if (form.value.remove_img) {
            formData.append('remove_img', 1);
          }

          if (form.value.id) {
          formData.append('_method', 'PUT');
          await axios.post(`/api/admin/clients/${form.value.id}`, formData);
        } else {
          await axios.post('/api/admin/clients', formData);
        }

        closeModal();
        loadData();
        Swal.fire('Sukses', 'Data klien berhasil disimpan.', 'success');
      } catch (err) {
        console.error(err);
        let errorMsg = 'Gagal menyimpan data.';
        if (err.response && err.response.data && err.response.data.message) {
          errorMsg = err.response.data.message;
          if (err.response.data.errors) {
            const firstError = Object.values(err.response.data.errors)[0][0];
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
        title: 'Konfirmasi',
        text: 'Yakin ingin menghapus klien ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/admin/clients/${id}`);
          loadData();
          Swal.fire('Terhapus!', 'Klien telah dihapus.', 'success');
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
        clients, loading, saving, showModal, form, columns, pagination,
        openModal, closeModal, handleFileUpload, removeImage, saveItem, deleteItem, handleSearch
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
</style>
