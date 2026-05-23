<template>
  <div class="dashboard-page">
    <div class="admin-page-title">
      Dashboard
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon bg-blue">
          <i class="ti ti-box"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.products }}</h3>
          <p>Total Produk</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon bg-green">
          <i class="ti ti-settings-2"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.machines }}</h3>
          <p>Total Mesin</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon bg-purple">
          <i class="ti ti-users"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.clients }}</h3>
          <p>Klien Aktif</p>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon bg-orange">
          <i class="ti ti-messages"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.inquiries }}</h3>
          <p>Pesan Baru</p>
        </div>
      </div>
    </div>

    <div class="dashboard-widgets">
      <div class="widget-card admin-card">
        <h4>Selamat Datang di Admin Panel</h4>
        <p>Gunakan menu di sebelah kiri untuk mengelola konten website Anda. Perubahan yang Anda lakukan akan langsung tayang secara realtime di halaman utama.</p>
        
        <div class="quick-links">
          <router-link to="/admin/products" class="btn-primary">Kelola Produk</router-link>
          <router-link to="/admin/machines" class="btn-primary">Kelola Mesin</router-link>
          <router-link to="/admin/cms" class="btn-primary">Edit Teks Website</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'Dashboard',
  setup() {
    const stats = ref({
      products: 0,
      machines: 0,
      clients: 0,
      inquiries: 0
    });

    const loadStats = async () => {
      try {
        const [resP, resM, resC, resI] = await Promise.all([
          axios.get('/api/admin/products'),
          axios.get('/api/admin/machines'),
          axios.get('/api/admin/clients'),
          axios.get('/api/admin/inquiries')
        ]);
        
        stats.value.products = resP.data.length;
        stats.value.machines = resM.data.length;
        stats.value.clients = resC.data.length;
        stats.value.inquiries = resI.data.length;
      } catch (err) {
        console.error('Failed to load dashboard stats', err);
      }
    };

    onMounted(() => {
      loadStats();
    });

    return {
      stats
    };
  }
}
</script>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  display: flex;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  gap: 20px;
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: white;
}

.bg-blue { background-color: #3b82f6; }
.bg-green { background-color: #10b981; }
.bg-purple { background-color: #8b5cf6; }
.bg-orange { background-color: #f59e0b; }

.stat-content h3 {
  margin: 0 0 5px;
  font-size: 1.8rem;
  color: #111827;
}

.stat-content p {
  margin: 0;
  color: #6b7280;
  font-size: 0.95rem;
}

.widget-card h4 {
  margin-top: 0;
  color: #111827;
  font-size: 1.2rem;
}

.widget-card p {
  color: #4b5563;
  line-height: 1.6;
}

.quick-links {
  margin-top: 20px;
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}
</style>
