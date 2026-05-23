<template>
  <header class="admin-header">
    <div class="header-left">
      <button class="toggle-btn" @click="$emit('toggle-sidebar')">
        <i class="ti ti-menu-2"></i>
      </button>
    </div>
    
    <div class="header-right">
      <div class="user-profile">
        <div class="avatar"><i class="ti ti-user"></i></div>
        <span class="username">{{ user?.name || 'Admin' }}</span>
      </div>
      <button class="logout-btn" @click="handleLogout" :disabled="isLoggingOut">
        <i class="ti ti-logout"></i> <span class="d-none d-sm-inline">Logout</span>
      </button>
    </div>
  </header>
</template>

<script>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { authState, logout } from '../../store/auth';

export default {
  name: 'Header',
  emits: ['toggle-sidebar'],
  setup() {
    const router = useRouter();
    const isLoggingOut = ref(false);

    const user = computed(() => authState.user);

    const handleLogout = async () => {
      isLoggingOut.value = true;
      try {
        await logout();
        router.push('/admin/login');
      } catch (error) {
        console.error('Logout failed', error);
      } finally {
        isLoggingOut.value = false;
      }
    };

    return {
      user,
      handleLogout,
      isLoggingOut
    };
  }
}
</script>

<style scoped>
.admin-header {
  height: 60px;
  background-color: #fff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  position: sticky;
  top: 0;
  z-index: 90;
}

.toggle-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #4b5563;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  transition: background 0.2s;
}

.toggle-btn:hover {
  background: #f3f4f6;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 10px;
}

.avatar {
  width: 35px;
  height: 35px;
  background-color: #e5e7eb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6b7280;
  font-size: 1.2rem;
}

.username {
  font-weight: 500;
  color: #374151;
}

.logout-btn {
  background-color: #fee2e2;
  color: #ef4444;
  border: none;
  padding: 8px 15px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: background 0.2s;
}

.logout-btn:hover:not(:disabled) {
  background-color: #fca5a5;
}

.logout-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 576px) {
  .username {
    display: none;
  }
}
</style>
