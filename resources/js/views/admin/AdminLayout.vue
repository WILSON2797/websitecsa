<template>
  <div class="admin-layout">
    <Sidebar :collapsed="sidebarCollapsed" />
    
    <div :class="['main-wrapper', { 'sidebar-collapsed': sidebarCollapsed }]">
      <Header @toggle-sidebar="toggleSidebar" />
      
      <main class="content-area">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import Sidebar from '../../components/admin/Sidebar.vue';
import Header from '../../components/admin/Header.vue';

export default {
  name: 'AdminLayout',
  components: {
    Sidebar,
    Header
  },
  setup() {
    // Check local storage for sidebar preference or default to false (open on desktop)
    const sidebarCollapsed = ref(window.innerWidth < 992);

    const toggleSidebar = () => {
      sidebarCollapsed.value = !sidebarCollapsed.value;
    };

    return {
      sidebarCollapsed,
      toggleSidebar
    };
  }
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f3f4f6;
  font-family: 'Inter', sans-serif;
}

.main-wrapper {
  flex: 1;
  margin-left: 250px;
  transition: margin-left 0.3s ease;
  min-width: 0; /* Important for flex children to allow shrinking */
  display: flex;
  flex-direction: column;
}

.main-wrapper.sidebar-collapsed {
  margin-left: 70px;
}

.content-area {
  padding: 24px;
  flex: 1;
}

@media (max-width: 991px) {
  .main-wrapper {
    margin-left: 70px; /* Default collapsed on smaller screens */
  }
  
  .main-wrapper:not(.sidebar-collapsed) {
    margin-left: 250px;
  }
}

@media (max-width: 576px) {
  .main-wrapper, .main-wrapper.sidebar-collapsed {
    margin-left: 0; /* Hide sidebar completely on mobile? Let's just make it overlap */
  }
  
  /* We might need a backdrop for mobile, but for now we let it slide over or we hide it */
}
</style>

<style>
/* Global Admin Table Styles */
.admin-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  padding: 20px;
  margin-bottom: 24px;
}

.admin-page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-danger {
  background-color: #ef4444;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.btn-danger:hover {
  background-color: #dc2626;
}

.btn-edit {
  background-color: #f59e0b;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
}

.btn-edit:hover {
  background-color: #d97706;
}

.admin-table-container {
  overflow-x: auto;
}

.admin-table {
  width: 100%;
  border-collapse: collapse;
}

.admin-table th, .admin-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.admin-table th {
  background-color: #f9fafb;
  font-weight: 600;
  color: #4b5563;
}

.admin-table tbody tr:hover {
  background-color: #f9fafb;
}

.action-buttons {
  display: flex;
  gap: 8px;
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
