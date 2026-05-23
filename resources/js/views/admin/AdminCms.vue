<template>
  <div class="crud-page">
    <div class="cms-sticky-header">
      <div class="admin-page-title">
        Pengaturan Teks Website
        <button class="btn-primary" @click="saveCms" :disabled="saving">
          <i class="ti ti-device-floppy"></i> {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>
      </div>

      <div class="cms-tabs" v-if="!loading">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          :class="['tab-btn', { active: activeTab === tab.id }]"
          @click="activeTab = tab.id"
        >
          <i :class="['ti', tab.icon]"></i> <span class="tab-label">{{ tab.label }}</span>
        </button>
      </div>
    </div>

    <div v-if="loading" style="text-align: center; padding: 40px;">
      Loading...
    </div>
    
    <div v-else class="cms-container">
      <!-- Tab Content Area -->
      <div class="cms-content-area admin-card">
        
        <!-- Section: Hero -->
        <div v-if="activeTab === 'hero'" class="tab-pane">
          <h3><i class="ti ti-layout-navbar"></i> Pengaturan Hero & Banner</h3>
          <p class="section-desc">Atur teks utama, statistik, dan banner berjalan di halaman depan.</p>
          
          <div class="form-group">
            <label>Badge Atas</label>
            <input type="text" v-model="cms.hero_badge" class="form-control">
          </div>
          <div class="form-group">
            <label>Judul Utama (Heading)</label>
            <input type="text" v-model="cms.hero_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Tagline (Sub-judul)</label>
            <input type="text" v-model="cms.hero_tagline" class="form-control">
          </div>
          <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea v-model="cms.hero_desc" class="form-control" rows="3"></textarea>
          </div>

          <h5 class="mt-4">Statistik (Angka Besar)</h5>
          <div class="list-editor">
            <div v-for="(item, index) in cms.hero_stats" :key="'hs'+index" class="list-item">
              <div class="list-inputs form-row">
                <input type="text" v-model="item.num" placeholder="Angka (ex: 26)" class="form-control" style="flex: 1;">
                <input type="text" v-model="item.unit" placeholder="Unit (ex: + atau %)" class="form-control" style="flex: 1;">
                <input type="text" v-model="item.label" placeholder="Label (ex: Klien Aktif)" class="form-control" style="flex: 2;">
              </div>
              <button @click="removeItem('hero_stats', index)" class="btn-danger-icon" title="Hapus"><i class="ti ti-trash"></i></button>
            </div>
            <button @click="addItem('hero_stats', {num: '', unit: '', label: ''})" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Statistik</button>
          </div>

          <h5 class="mt-4">Banner Teks Berjalan (Ticker)</h5>
          <div class="list-editor">
            <div v-for="(item, index) in cms.ticker_items" :key="'tk'+index" class="list-item">
              <input type="text" v-model="cms.ticker_items[index]" placeholder="Teks Ticker" class="form-control">
              <button @click="removeSimpleItem('ticker_items', index)" class="btn-danger-icon" title="Hapus"><i class="ti ti-trash"></i></button>
            </div>
            <button @click="addSimpleItem('ticker_items')" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Teks Berjalan</button>
          </div>
        </div>

        <!-- Section: About -->
        <div v-if="activeTab === 'about'" class="tab-pane">
          <h3><i class="ti ti-info-circle"></i> Pengaturan Tentang Kami</h3>
          <p class="section-desc">Atur deskripsi profil perusahaan, sorotan utama, visi, dan misi.</p>
          
          <div class="form-group">
            <label>Judul Seksi</label>
            <input type="text" v-model="cms.about_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Deskripsi Paragraf 1</label>
            <textarea v-model="cms.about_desc_1" class="form-control" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Deskripsi Paragraf 2</label>
            <textarea v-model="cms.about_desc_2" class="form-control" rows="3"></textarea>
          </div>

          <h5 class="mt-4">Sorotan Utama (Highlights)</h5>
          <div class="list-editor">
            <div v-for="(item, index) in cms.about_highlights" :key="'ah'+index" class="list-item complex-item">
              <div class="complex-inputs">
                <div class="form-row">
                  <div class="form-group half"><label>Judul</label><input type="text" v-model="item.title" class="form-control"></div>
                  <div class="form-group half"><label>Ikon</label><IconPicker v-model="item.icon" /></div>
                </div>
                <div class="form-group" style="margin-bottom:0;"><label>Deskripsi</label><input type="text" v-model="item.desc" class="form-control"></div>
              </div>
              <button @click="removeItem('about_highlights', index)" class="btn-danger-icon" style="align-self: center;" title="Hapus"><i class="ti ti-trash"></i></button>
            </div>
            <button @click="addItem('about_highlights', {title: '', desc: '', icon: ''})" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Sorotan</button>
          </div>

          <div class="form-group mt-4">
            <label>Visi Perusahaan</label>
            <textarea v-model="cms.about_visi" class="form-control" rows="2"></textarea>
          </div>

          <h5 class="mt-4">Misi Perusahaan</h5>
          <div class="list-editor">
            <div v-for="(item, index) in cms.about_misi" :key="'am'+index" class="list-item">
              <input type="text" v-model="cms.about_misi[index]" placeholder="Teks Misi" class="form-control">
              <button @click="removeSimpleItem('about_misi', index)" class="btn-danger-icon" title="Hapus"><i class="ti ti-trash"></i></button>
            </div>
            <button @click="addSimpleItem('about_misi')" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Misi</button>
          </div>
        </div>

        <!-- Section: Product -->
        <div v-if="activeTab === 'product'" class="tab-pane">
          <h3><i class="ti ti-box"></i> Pengaturan Label Seksi Produk</h3>
          <p class="section-desc">Atur teks pengantar sebelum daftar produk (diambil dari database) ditampilkan.</p>
          <div class="form-group">
            <label>Eyebrow (Label Kecil di atas judul)</label>
            <input type="text" v-model="cms.products_eyebrow" class="form-control">
          </div>
          <div class="form-group">
            <label>Judul Utama</label>
            <input type="text" v-model="cms.products_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Sub-judul / Deskripsi Singkat</label>
            <textarea v-model="cms.products_sub" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <!-- Section: Machine -->
        <div v-if="activeTab === 'machine'" class="tab-pane">
          <h3><i class="ti ti-settings-2"></i> Pengaturan Label Seksi Mesin</h3>
          <p class="section-desc">Atur teks pengantar sebelum daftar mesin (diambil dari database) ditampilkan.</p>
          <div class="form-group">
            <label>Eyebrow (Label Kecil di atas judul)</label>
            <input type="text" v-model="cms.machines_eyebrow" class="form-control">
          </div>
          <div class="form-group">
            <label>Judul Utama</label>
            <input type="text" v-model="cms.machines_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Sub-judul / Deskripsi Singkat</label>
            <textarea v-model="cms.machines_sub" class="form-control" rows="3"></textarea>
          </div>

          <h5 class="mt-4">Daftar Fasilitas Tambahan</h5>
          <div class="list-editor">
            <div v-for="(item, index) in cms.machines_facilities" :key="'mf'+index" class="list-item complex-item">
              <div class="complex-inputs">
                <div class="form-row">
                  <div class="form-group half"><label>Nama Fasilitas</label><input type="text" v-model="item.name" class="form-control"></div>
                  <div class="form-group half"><label>Ikon</label><IconPicker v-model="item.icon" /></div>
                </div>
                <div class="form-group" style="margin-bottom:0;"><label>Deskripsi</label><input type="text" v-model="item.desc" class="form-control"></div>
              </div>
              <button @click="removeItem('machines_facilities', index)" class="btn-danger-icon" style="align-self: center;"><i class="ti ti-trash"></i></button>
            </div>
            <button @click="addItem('machines_facilities', {name: '', desc: '', icon: ''})" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Fasilitas</button>
          </div>
        </div>

        <!-- Section: Quality -->
        <div v-if="activeTab === 'quality'" class="tab-pane">
          <h3><i class="ti ti-award"></i> Pengaturan Kualitas (QA)</h3>
          <p class="section-desc">Atur teks pengantar, daftar sertifikasi, dan tahapan Quality Control.</p>
          
          <div class="form-group">
            <label>Eyebrow (Label Kecil)</label>
            <input type="text" v-model="cms.quality_eyebrow" class="form-control">
          </div>
          <div class="form-group">
            <label>Judul Utama</label>
            <input type="text" v-model="cms.quality_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Sub-judul / Deskripsi Singkat</label>
            <textarea v-model="cms.quality_sub" class="form-control" rows="3"></textarea>
          </div>

          <h5 class="mt-4">Tahapan Proses QC</h5>
          <div class="list-editor">
            <div v-for="(item, index) in cms.quality_steps" :key="'qs'+index" class="list-item complex-item">
              <div class="complex-inputs">
                <div class="form-row">
                  <div class="form-group" style="width: 80px;"><label>Nomor</label><input type="text" v-model="item.num" placeholder="01" class="form-control"></div>
                  <div class="form-group" style="flex: 1;"><label>Judul Proses</label><input type="text" v-model="item.title" class="form-control"></div>
                </div>
                <div class="form-group" style="margin-bottom:0;"><label>Deskripsi Proses</label><textarea v-model="item.desc" class="form-control" rows="2"></textarea></div>
              </div>
              <button @click="removeItem('quality_steps', index)" class="btn-danger-icon" style="align-self: center;"><i class="ti ti-trash"></i></button>
            </div>
            <button @click="addItem('quality_steps', {num: '', title: '', desc: ''})" class="btn-add-item"><i class="ti ti-plus"></i> Tambah Proses QC</button>
          </div>
        </div>

        <!-- Section: Client -->
        <div v-if="activeTab === 'client'" class="tab-pane">
          <h3><i class="ti ti-building-factory-2"></i> Pengaturan Seksi Klien</h3>
          <p class="section-desc">Atur teks pengantar daftar klien dan testimonial utama.</p>
          
          <div class="form-group">
            <label>Eyebrow (Label Kecil)</label>
            <input type="text" v-model="cms.clients_eyebrow" class="form-control">
          </div>
          <div class="form-group">
            <label>Judul Utama</label>
            <input type="text" v-model="cms.clients_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Intro Teks Klien</label>
            <textarea v-model="cms.clients_intro" class="form-control" rows="3"></textarea>
          </div>

          <h5 class="mt-4">Testimonial Utama</h5>
          <div class="form-group">
            <label>Kutipan Testimonial</label>
            <textarea v-model="cms.testimonial_quote" class="form-control" rows="4"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group half">
              <label>Penulis / Nama Tokoh</label>
              <input type="text" v-model="cms.testimonial_author" class="form-control">
            </div>
            <div class="form-group half">
              <label>Jabatan & Perusahaan</label>
              <input type="text" v-model="cms.testimonial_role" class="form-control">
            </div>
          </div>
        </div>

        <!-- Section: Profile -->
        <div v-if="activeTab === 'profile'" class="tab-pane">
          <h3><i class="ti ti-building"></i> Profil & Kontak Global</h3>
          <p class="section-desc">Atur identitas perusahaan, alamat, dan kontak yang berlaku global di seluruh website.</p>

          <h5>Alamat & Kontak Utama</h5>
          <div class="form-group mt-3">
            <label>Alamat Baris 1</label>
            <input type="text" v-model="cms.contact_alamat" class="form-control">
          </div>
          <div class="form-group">
            <label>Alamat Baris 2 (Kota, Kodepos)</label>
            <input type="text" v-model="cms.contact_alamat_sub" class="form-control">
          </div>
          
          <div class="form-row">
            <div class="form-group half">
              <label>Nomor Telepon</label>
              <input type="text" v-model="cms.contact_telp" class="form-control">
            </div>
            <div class="form-group half">
              <label>Nomor Fax</label>
              <input type="text" v-model="cms.contact_fax" class="form-control">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group half">
              <label>Email Utama</label>
              <input type="email" v-model="cms.contact_email_1" class="form-control">
            </div>
            <div class="form-group half">
              <label>Email Alternatif</label>
              <input type="email" v-model="cms.contact_email_2" class="form-control">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group half">
              <label>Jam Kerja (Senin-Jumat)</label>
              <input type="text" v-model="cms.contact_jam_1" class="form-control">
            </div>
            <div class="form-group half">
              <label>Jam Kerja (Sabtu)</label>
              <input type="text" v-model="cms.contact_jam_2" class="form-control">
            </div>
          </div>
        </div>

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
  name: 'AdminCms',
  components: {
    IconPicker
  },
  setup() {
    const cms = ref({});
    const loading = ref(true);
    const saving = ref(false);
    
    const activeTab = ref('hero');
    
    const tabs = [
      { id: 'hero', label: 'Hero', icon: 'ti-layout-navbar' },
      { id: 'about', label: 'About', icon: 'ti-info-circle' },
      { id: 'product', label: 'Produk', icon: 'ti-box' },
      { id: 'machine', label: 'Mesin', icon: 'ti-settings-2' },
      { id: 'quality', label: 'Kualitas', icon: 'ti-award' },
      { id: 'client', label: 'Klien', icon: 'ti-building-factory-2' },
      { id: 'profile', label: 'Profil Perusahaan', icon: 'ti-building' }
    ];

    // Daftar kunci yang harus diproses sebagai JSON Array
    const jsonKeys = [
      'hero_stats', 'ticker_items', 
      'about_highlights', 'about_misi', 
      'machines_facilities', 'quality_steps'
    ];

    const loadCms = async () => {
      try {
        const res = await axios.get('/api/admin/cms');
        let data = res.data;
        
        // Parsing JSON strings to actual Javascript Arrays
        jsonKeys.forEach(key => {
          if (data[key]) {
            try {
              data[key] = JSON.parse(data[key]);
            } catch (e) {
              console.error(`Failed to parse ${key}:`, e);
              data[key] = []; // Fallback empty array
            }
          } else {
            data[key] = [];
          }
        });

        cms.value = data;
      } catch (err) {
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    const saveCms = async () => {
      saving.value = true;
      try {
        // Clone CMS to avoid mutating the UI during save
        let dataToSave = JSON.parse(JSON.stringify(cms.value));

        // Stringify Arrays back to JSON strings
        jsonKeys.forEach(key => {
          if (dataToSave[key] !== undefined) {
            dataToSave[key] = JSON.stringify(dataToSave[key]);
          }
        });

        const contents = Object.keys(dataToSave).map(key => {
          return { key: key, value: dataToSave[key] };
        });

        await axios.post('/api/admin/cms', { contents });
        Swal.fire({
          title: 'Sukses',
          text: 'Pengaturan Teks Website berhasil diperbarui!',
          icon: 'success',
          timer: 2000,
          showConfirmButton: false
        });
      } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Gagal menyimpan perubahan.', 'error');
      } finally {
        saving.value = false;
      }
    };

    // Functions for Array Editor
    const addSimpleItem = (key) => {
      if (!cms.value[key]) cms.value[key] = [];
      cms.value[key].push('');
    };

    const removeSimpleItem = (key, index) => {
      cms.value[key].splice(index, 1);
    };

    const addItem = (key, template) => {
      if (!cms.value[key]) cms.value[key] = [];
      // Push a deep copy of the template
      cms.value[key].push(JSON.parse(JSON.stringify(template)));
    };

    const removeItem = (key, index) => {
      cms.value[key].splice(index, 1);
    };

    onMounted(() => {
      loadCms();
    });

    return {
      cms, loading, saving, saveCms, activeTab, tabs,
      addSimpleItem, removeSimpleItem, addItem, removeItem
    };
  }
}
</script>

<style scoped>
.cms-sticky-header {
  position: sticky;
  top: 60px; /* Sticks right below the main 60px admin navbar */
  z-index: 80;
  background-color: #f3f4f6; /* Matches admin background */
  margin-top: -24px;
  padding-top: 24px;
  margin-left: -24px;
  padding-left: 24px;
  margin-right: -24px;
  padding-right: 24px;
  padding-bottom: 15px;
}

.cms-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Tabs Styling */
.cms-tabs {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 10px;
  background: white;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.tab-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  background: transparent;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.95rem;
  font-weight: 500;
  color: #4b5563;
  text-align: left;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.tab-btn:hover {
  background: #f3f4f6;
  color: #111827;
}

.tab-btn.active {
  background: #eff6ff;
  color: #2563eb;
  font-weight: 600;
}

/* Content Area */
.cms-content-area {
  flex: 1;
  min-height: 400px;
}

.tab-pane {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}

.tab-pane h3 {
  margin-top: 0;
  color: #111827;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 5px;
}

.section-desc {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e5e7eb;
}

h5.mt-4 {
  margin-top: 30px;
  margin-bottom: 15px;
  color: #111827;
  font-size: 1.05rem;
  font-weight: 600;
  padding-bottom: 5px;
  border-bottom: 1px dashed #e5e7eb;
}

/* Form Styles */
.form-group {
  margin-bottom: 20px;
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
  margin-bottom: 8px;
  font-weight: 500;
  color: #374151;
  font-size: 0.9rem;
}

.form-control {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-family: inherit;
  box-sizing: border-box;
  font-size: 0.95rem;
  transition: border-color 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Dynamic List Editor Styles */
.list-editor {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.list-item {
  display: flex;
  gap: 10px;
  align-items: flex-start;
  background: #f9fafb;
  padding: 10px;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  animation: slideIn 0.2s ease-out;
}

@keyframes slideIn {
  from { opacity: 0; transform: translateX(-10px); }
  to { opacity: 1; transform: translateX(0); }
}

.list-inputs {
  flex: 1;
  margin-bottom: 0 !important;
}

.complex-item {
  flex-direction: row;
}

.complex-inputs {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.btn-danger-icon {
  background: #fee2e2;
  color: #ef4444;
  border: none;
  border-radius: 4px;
  padding: 10px 12px;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-danger-icon:hover {
  background: #fca5a5;
  color: #b91c1c;
}

.btn-add-item {
  background: #eff6ff;
  color: #2563eb;
  border: 1px dashed #bfdbfe;
  padding: 10px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

.btn-add-item:hover {
  background: #dbeafe;
  border-color: #93c5fd;
}
</style>
