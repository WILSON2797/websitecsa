<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="logo">CSA</div>
        <h2>Admin Panel</h2>
        <p>Login to manage website content</p>
      </div>
      
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrapper">
            <i class="ti ti-mail"></i>
            <input type="email" id="email" v-model="form.email" placeholder="admin@csa.com" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class="ti ti-lock"></i>
            <input type="password" id="password" v-model="form.password" placeholder="••••••••" required>
          </div>
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>
        
        <button type="submit" class="btn-login" :disabled="loading">
          <span v-if="loading">Signing in...</span>
          <span v-else>Sign In</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { login } from '../../store/auth';

export default {
  name: 'AdminLogin',
  setup() {
    const router = useRouter();
    const form = reactive({
      email: '',
      password: ''
    });
    const loading = ref(false);
    const error = ref('');

    const handleLogin = async () => {
      loading.value = true;
      error.value = '';
      
      try {
        await login(form);
        router.push('/admin');
      } catch (err) {
        if (err.response && err.response.data && err.response.data.message) {
          error.value = err.response.data.message;
        } else {
          error.value = 'An error occurred during login.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      error,
      handleLogin
    };
  }
}
</script>

<style scoped>
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f3f4f6;
  font-family: 'Inter', sans-serif;
}

.login-card {
  background: white;
  width: 100%;
  max-width: 400px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  padding: 40px;
}

.login-header {
  text-align: center;
  margin-bottom: 30px;
}

.logo {
  width: 60px;
  height: 60px;
  background: #3b82f6;
  color: white;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 800;
  margin: 0 auto 15px;
}

.login-header h2 {
  font-size: 1.5rem;
  color: #111827;
  margin: 0 0 5px;
  font-weight: 700;
}

.login-header p {
  color: #6b7280;
  margin: 0;
  font-size: 0.95rem;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 500;
  color: #374151;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.input-wrapper {
  position: relative;
}

.input-wrapper i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  font-size: 1.1rem;
}

.input-wrapper input {
  width: 100%;
  padding: 12px 12px 12px 40px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
  box-sizing: border-box;
}

.input-wrapper input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-login {
  width: 100%;
  padding: 12px;
  background-color: #3b82f6;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
  margin-top: 10px;
}

.btn-login:hover:not(:disabled) {
  background-color: #2563eb;
}

.btn-login:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.error-message {
  background-color: #fee2e2;
  color: #ef4444;
  padding: 10px;
  border-radius: 6px;
  font-size: 0.9rem;
  margin-bottom: 20px;
  text-align: center;
}
</style>
