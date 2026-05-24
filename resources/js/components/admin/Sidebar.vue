<template>
  <aside :class="['admin-sidebar', { 'collapsed': collapsed }]">
    <div class="sidebar-brand">
      <div class="brand-logo">CSA</div>
      <span class="brand-text" v-if="!collapsed">Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
      <router-link to="/admin" class="nav-item" exact-active-class="active">
        <i class="ti ti-layout-dashboard"></i>
        <span v-if="!collapsed">Dashboard</span>
      </router-link>
      <router-link to="/admin/products" class="nav-item" active-class="active">
        <i class="ti ti-box"></i>
        <span v-if="!collapsed">Produk</span>
      </router-link>
      <router-link to="/admin/machines" class="nav-item" active-class="active">
        <i class="ti ti-settings-2"></i>
        <span v-if="!collapsed">Mesin</span>
      </router-link>
      <router-link to="/admin/facilities" class="nav-item" active-class="active">
        <i class="ti ti-building"></i>
        <span v-if="!collapsed">Fasilitas</span>
      </router-link>
      <router-link to="/admin/certificates" class="nav-item" active-class="active">
        <i class="ti ti-certificate"></i>
        <span v-if="!collapsed">Sertifikat</span>
      </router-link>
      <router-link to="/admin/galleries" class="nav-item" active-class="active">
        <i class="ti ti-photo"></i>
        <span v-if="!collapsed">Galeri</span>
      </router-link>
      <router-link to="/admin/clients" class="nav-item" active-class="active">
        <i class="ti ti-users"></i>
        <span v-if="!collapsed">Klien</span>
      </router-link>
      <router-link to="/admin/inquiries" class="nav-item" active-class="active">
        <i class="ti ti-messages"></i>
        <span v-if="!collapsed">Pesan Masuk</span>
      </router-link>
      <router-link to="/admin/cms" class="nav-item" active-class="active">
        <i class="ti ti-world"></i>
        <span v-if="!collapsed">Teks Website</span>
      </router-link>
    </nav>
  </aside>
</template>

<script>
export default {
  name: 'Sidebar',
  props: {
    collapsed: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>
.admin-sidebar {
  width: 250px;
  background-color: #111827;
  color: #fff;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  transition: width 0.3s ease;
  z-index: 100;
  overflow-y: auto;
}

.admin-sidebar.collapsed {
  width: 70px;
}

.sidebar-brand {
  height: 60px;
  display: flex;
  align-items: center;
  padding: 0 15px;
  background-color: #1f2937;
  border-bottom: 1px solid #374151;
  overflow: hidden;
}

.brand-logo {
  background: var(--primary);
  color: #fff;
  font-weight: 800;
  font-size: 1.2rem;
  padding: 5px 10px;
  border-radius: 4px;
  min-width: 40px;
  text-align: center;
}

.brand-text {
  margin-left: 10px;
  font-weight: 600;
  font-size: 1.1rem;
  white-space: nowrap;
}

.sidebar-nav {
  padding: 15px 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: #9ca3af;
  text-decoration: none;
  transition: all 0.2s;
  white-space: nowrap;
}

.admin-sidebar.collapsed .nav-item {
  justify-content: center;
  padding: 12px 0;
}

.nav-item i {
  font-size: 1.3rem;
  min-width: 30px;
  text-align: center;
}

.nav-item span {
  margin-left: 10px;
  font-size: 1rem;
}

.nav-item:hover, .nav-item.active {
  background-color: #374151;
  color: #fff;
  border-left: 4px solid var(--primary);
}

.admin-sidebar.collapsed .nav-item:hover, .admin-sidebar.collapsed .nav-item.active {
  border-left: none;
  background-color: #374151;
}
</style>
