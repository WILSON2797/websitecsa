<template>
  <div class="crud-page">
    <div class="admin-page-title">
      Kelola Produk
      <button class="btn-primary" @click="openModal()">
        <i class="ti ti-plus"></i> Tambah Produk
      </button>
    </div>

    <div class="admin-card">
      <DataTable
        :data="products"
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

          <div class="form-group">
            <label>Tag / Spesifikasi Singkat (Maks 3-4 disarankan)</label>
            <div class="list-editor">
              <div v-for="(item, index) in form.specs" :key="'sp'+index" class="list-item">
                <input type="text" v-model="form.specs[index]" placeholder="e.g. Toleransi ±0.05mm" class="form-control">
                <button type="button" @click="removeSpec(index)" class="btn-danger-icon" title="Hapus"><i class="ti ti-trash"></i></button>
              </div>
              <button type="button" @click="addSpec" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Tag</button>
            </div>
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
              <label>Ikon Tabler (Opsional)</label>
              <IconPicker v-model="form.icon" />
            </div>
            <div class="form-group half">
              <label>Gambar Utama (Thumbnail Depan)</label>
              <input type="file" @change="handleFileUpload" class="form-control" accept="image/*" ref="mainImgInput">
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

          <hr style="margin: 20px 0; border-color: #e2e8f0;" />
          <h4 style="margin-bottom: 15px; font-size: 14px; font-weight: 600; color: var(--navy);">Gambar Detail (Slider Halaman Produk)</h4>
          
          <div class="form-row">
            <!-- Detail Image 1 -->
            <div class="form-group" style="width: 32%;">
              <label>Gambar Slider 1</label>
              <input type="file" @change="(e) => handleDetailUpload(e, 1)" class="form-control" accept="image/*" ref="detailImgInput1">
              <div v-if="form.previewDetailImg1" style="margin-top:15px;">
                <div style="position: relative; display: inline-block;">
                  <img :src="form.previewDetailImg1" style="max-height: 70px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
                  <button type="button" @click="removeDetailImage(1)" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px;">
                    <i class="ti ti-x"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Detail Image 2 -->
            <div class="form-group" style="width: 32%;">
              <label>Gambar Slider 2</label>
              <input type="file" @change="(e) => handleDetailUpload(e, 2)" class="form-control" accept="image/*" ref="detailImgInput2">
              <div v-if="form.previewDetailImg2" style="margin-top:15px;">
                <div style="position: relative; display: inline-block;">
                  <img :src="form.previewDetailImg2" style="max-height: 70px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
                  <button type="button" @click="removeDetailImage(2)" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px;">
                    <i class="ti ti-x"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Detail Image 3 -->
            <div class="form-group" style="width: 32%;">
              <label>Gambar Slider 3</label>
              <input type="file" @change="(e) => handleDetailUpload(e, 3)" class="form-control" accept="image/*" ref="detailImgInput3">
              <div v-if="form.previewDetailImg3" style="margin-top:15px;">
                <div style="position: relative; display: inline-block;">
                  <img :src="form.previewDetailImg3" style="max-height: 70px; border-radius: 4px; border: 1px solid #ddd; padding: 2px;">
                  <button type="button" @click="removeDetailImage(3)" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px;">
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
  name: 'AdminProducts',
  components: {
    IconPicker,
    DataTable
  },
  setup() {
    const products = ref([]);
    const pagination = ref(null);
    const loading = ref(true);
    const saving = ref(false);
    const showModal = ref(false);

    const mainImgInput = ref(null);
    const detailImgInput1 = ref(null);
    const detailImgInput2 = ref(null);
    const detailImgInput3 = ref(null);

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
              alt: 'product image',
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
              h('i', { class: item.icon || 'ti ti-box' })
            ]);
          }
        }
      },
      {
        accessorKey: 'name',
        header: 'Nama & Tag',
        cell: ({ row }) => {
          const item = row.original;
          return h('div', {}, [
            h('strong', {}, item.name),
            h('br'),
            h('small', { class: 'text-muted' }, item.tag)
          ]);
        }
      },
      {
        accessorKey: 'tolerance',
        header: 'Toleransi',
        cell: ({ row }) => row.original.tolerance || '-'
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
      tag: '',
      desc: '',
      icon: '',
      imgFile: null,
      previewImg: '',
      detailImgFile1: null,
      previewDetailImg1: '',
      detailImgFile2: null,
      previewDetailImg2: '',
      detailImgFile3: null,
      previewDetailImg3: '',
      tolerance: '',
      capacity: '',
      speed: '',
      volume: '',
      auxiliary: '',
      safety: '',
      typical: '',
      specs: []
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
        const res = await axios.get('/api/admin/products', { params });
        products.value = res.data.data;
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
        form.value = { 
          ...item, 
          imgFile: null, 
          previewImg: item.img, 
          remove_img: 0,
          detailImgFile1: null,
          previewDetailImg1: item.detail_img1,
          remove_detail_img1: 0,
          detailImgFile2: null,
          previewDetailImg2: item.detail_img2,
          remove_detail_img2: 0,
          detailImgFile3: null,
          previewDetailImg3: item.detail_img3,
          remove_detail_img3: 0,
          specs: item.specs ? [...item.specs] : [] 
        };
      } else {
        form.value = { 
          ...defaultForm, 
          remove_img: 0, 
          remove_detail_img1: 0,
          remove_detail_img2: 0,
          remove_detail_img3: 0,
          specs: [] 
        };
      }
      showModal.value = true;
    };

    const closeModal = () => {
      showModal.value = false;
    };

    const addSpec = () => {
      if (!form.value.specs) form.value.specs = [];
      form.value.specs.push('');
    };

    const removeSpec = (index) => {
      form.value.specs.splice(index, 1);
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
          const options = { maxSizeMB: 1, maxWidthOrHeight: 1920, useWebWorker: true };
          const compressedFile = await imageCompression(file, options);
          form.value.imgFile = compressedFile;
          form.value.previewImg = URL.createObjectURL(compressedFile);
          Swal.close();
        } catch (error) {
          console.error('Compression error:', error);
          Swal.fire('Error', 'Gagal mengompres gambar.', 'error');
        }
      }
    };

    const handleDetailUpload = async (event, index) => {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 15 * 1024 * 1024) {
          Swal.fire('Error', 'Ukuran gambar maksimal 15MB!', 'error');
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
          const options = { maxSizeMB: 1, maxWidthOrHeight: 1920, useWebWorker: true };
          const compressedFile = await imageCompression(file, options);
          
          // Debugging log to ensure file is processed correctly
          console.log(`Compressed detail image ${index}:`, compressedFile);

          form.value[`detailImgFile${index}`] = compressedFile;
          form.value[`previewDetailImg${index}`] = URL.createObjectURL(compressedFile);
          Swal.close();
        } catch (error) {
          console.error(error);
          Swal.fire('Error', 'Gagal mengompres gambar detail.', 'error');
        }
      }
    };

    const removeImage = () => {
      form.value.imgFile = null;
      form.value.previewImg = '';
      form.value.remove_img = 1;
      if (mainImgInput.value) mainImgInput.value.value = '';
    };

    const removeDetailImage = (index) => {
      form.value[`detailImgFile${index}`] = null;
      form.value[`previewDetailImg${index}`] = '';
      form.value[`remove_detail_img${index}`] = 1;
      if (index === 1 && detailImgInput1.value) detailImgInput1.value.value = '';
      if (index === 2 && detailImgInput2.value) detailImgInput2.value.value = '';
      if (index === 3 && detailImgInput3.value) detailImgInput3.value.value = '';
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
        Object.keys(form.value).forEach(key => {
          if (!key.startsWith('imgFile') && !key.startsWith('preview') && key !== 'img' && !key.startsWith('remove_') && !key.startsWith('detailImgFile') && form.value[key] !== null) {
            if (Array.isArray(form.value[key])) {
              form.value[key].forEach(item => {
                formData.append(`${key}[]`, item);
              });
            } else {
              formData.append(key, form.value[key]);
            }
          }
        });

        if (form.value.imgFile) formData.append('img', form.value.imgFile);
        if (form.value.remove_img) formData.append('remove_img', 1);

        for (let i = 1; i <= 3; i++) {
          if (form.value[`detailImgFile${i}`]) formData.append(`detail_img${i}`, form.value[`detailImgFile${i}`]);
          if (form.value[`remove_detail_img${i}`]) formData.append(`remove_detail_img${i}`, 1);
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
      products, loading, saving, showModal, form, columns, pagination,
      openModal, closeModal, handleFileUpload, handleDetailUpload, removeImage, removeDetailImage, saveItem, deleteItem, addSpec, removeSpec,
      mainImgInput, detailImgInput1, detailImgInput2, detailImgInput3, handleSearch
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

