<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Fasilitas
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Fasilitas
      </button>
    </div>

    <div class="admin-card">
      <DataTable
        :data="facilities"
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
          <h3>{{ form.id ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}</h3>
          <button class="close-btn" @click="closeModal">&times;</button>
        </div>
        
        <form @submit.prevent="saveItem" class="modal-body">
          <div class="form-row">
            <div class="form-group half">
              <label>Nama Fasilitas *</label>
              <input type="text" v-model="form.name" required class="form-control" placeholder="e.g. Area Produksi">
            </div>
            <div class="form-group half">
              <label>Deskripsi Singkat</label>
              <input type="text" v-model="form.short_desc" class="form-control" placeholder="Tampil di kartu beranda">
            </div>
          </div>

          <div class="form-group">
            <label>Deskripsi Lengkap (View Details)</label>
            <textarea v-model="form.long_desc" class="form-control" rows="4"></textarea>
          </div>

          <div class="form-group">
            <label>Spesifikasi / Info Tambahan</label>
            <div v-for="(line, index) in specLines" :key="index" style="display: flex; gap: 10px; margin-bottom: 10px;">
              <input type="text" v-model="specLines[index].label" class="form-control" placeholder="Label (e.g. Luas Area)">
              <input type="text" v-model="specLines[index].value" class="form-control" placeholder="Value (e.g. 500m2)">
              <button type="button" @click="removeSpecLine(index)" class="btn-danger" style="padding: 10px; border-radius: 6px;" title="Hapus baris">
                <i class="ti ti-trash"></i>
              </button>
            </div>
            <button type="button" @click="addSpecLine" style="background: var(--gray100, #f3f4f6); color: #374151; border: 1px dashed #d1d5db; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; width: 100%; justify-content: center;">
              <i class="ti ti-plus"></i> Tambah Baris Spesifikasi
            </button>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>Ikon Tabler</label>
              <IconPicker v-model="form.icon" />
            </div>
            <div class="form-group half">
              <label>Gambar Fasilitas</label>
              <input type="file" @change="handleFileUpload" class="form-control" accept="image/*">
              <div v-if="form.previewImg" style="margin-top:15px; display: flex; align-items: flex-start;">
                <div style="position: relative; display: inline-block; width: max-content;">
                  <img :src="form.previewImg" alt="Preview" style="max-height: 80px; border-radius: 4px; border: 1px solid #ddd; padding: 2px; display: block;">
                  <button type="button" @click="removeImage" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="ti ti-x"></i>
                  </button>
                </div>
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
import IconPicker from '../../components/Admin/IconPicker.vue';
import DataTable from '../../components/admin/DataTable.vue';

export default {
  name: 'AdminFacilities',
  components: {
    IconPicker,
    DataTable
  },
  setup() {
    const facilities = ref([]);
    const pagination = ref(null);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);
    const specLines = ref([{ label: '', value: '' }]);

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
        header: 'Gambar',
        meta: { width: '100px' },
        cell: ({ row }) => {
          const item = row.original;
          if (item.img) {
            return h('img', {
              src: item.img,
              alt: 'facility image',
              style: { width: '50px', height: '50px', objectFit: 'cover', borderRadius: '4px' }
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
              h('i', { class: item.icon || 'ti ti-building' })
            ]);
          }
        }
      },
      {
        accessorKey: 'name',
        header: 'Nama Fasilitas',
        cell: ({ row }) => {
          const item = row.original;
          return h('div', {}, [
            h('strong', {}, item.name),
            h('br'),
            h('small', { class: 'text-muted' }, item.short_desc)
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
    
    const defaultForm = {
      id: null,
      name: '',
      short_desc: '',
      long_desc: '',
      icon: '',
      imgFile: null,
      previewImg: '',
      spec: ''
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
        const res = await axios.get('/api/admin/facilities', { params });
        facilities.value = res.data.data;
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
        form.value = { ...item, imgFile: null, previewImg: item.img, remove_img: 0 };
        try {
          const parsed = JSON.parse(item.spec);
          specLines.value = Array.isArray(parsed) && parsed.length ? parsed : [{ label: '', value: '' }];
        } catch (e) {
          if (item.spec) {
            specLines.value = item.spec.split('\n').map(line => {
              const parts = line.split(':');
              if (parts.length > 1) {
                return { label: parts[0].trim(), value: parts.slice(1).join(':').trim() };
              }
              return { label: line.trim(), value: '' };
            });
          } else {
            specLines.value = [{ label: '', value: '' }];
          }
        }
      } else {
        form.value = { ...defaultForm, remove_img: 0 };
        specLines.value = [{ label: '', value: '' }];
      }
      showModal.value = true;
    };

    const addSpecLine = () => {
      specLines.value.push({ label: '', value: '' });
    };

    const removeSpecLine = (index) => {
      specLines.value.splice(index, 1);
      if (specLines.value.length === 0) {
        specLines.value.push({ label: '', value: '' });
      }
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
          form.value.imgFile = compressedFile;
          form.value.previewImg = URL.createObjectURL(compressedFile);
          form.value.remove_img = 0;
          Swal.close();
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
      Swal.fire({
        title: 'Menyimpan Data...',
        text: 'Sedang mengunggah data ke server.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });
      try {
        // Gabungkan specLines menjadi JSON string
        const validSpecs = specLines.value.filter(line => line.label.trim() !== '' || line.value.trim() !== '');
        form.value.spec = JSON.stringify(validSpecs);
        
        const formData = new FormData();
        Object.keys(form.value).forEach(key => {
          if (key !== 'imgFile' && key !== 'previewImg' && key !== 'img' && key !== 'remove_img' && form.value[key] !== null) {
            formData.append(key, form.value[key]);
          }
        });

        if (form.value.imgFile) {
          formData.append('img', form.value.imgFile);
        }
        if (form.value.remove_img) {
          formData.append('remove_img', 1);
        }

        if (form.value.id) {
          formData.append('_method', 'PUT');
          await axios.post(`/api/admin/facilities/${form.value.id}`, formData);
        } else {
          await axios.post('/api/admin/facilities', formData);
        }

        closeModal();
        loadData();
        Swal.fire('Sukses', 'Data fasilitas berhasil disimpan.', 'success');
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
        text: 'Yakin ingin menghapus fasilitas ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/admin/facilities/${id}`);
          loadData();
          Swal.fire('Terhapus!', 'Fasilitas telah dihapus.', 'success');
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
      facilities, loading, saving, showModal, form, columns, pagination, specLines,
      openModal, closeModal, handleFileUpload, removeImage, saveItem, deleteItem, addSpecLine, removeSpecLine, handleSearch
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
