import { reactive } from 'vue';
import axios from 'axios';

export const authState = reactive({
    user: null,
    isAuthenticated: false,
    loading: true
});

export const checkAuth = async () => {
    try {
        const response = await axios.get('/api/admin/user');
        authState.user = response.data;
        authState.isAuthenticated = true;
    } catch (error) {
        authState.user = null;
        authState.isAuthenticated = false;
    } finally {
        authState.loading = false;
    }
};

export const login = async (credentials) => {
    // CSRF protection for Laravel
    await axios.get('/sanctum/csrf-cookie');
    const response = await axios.post('/api/admin/login', credentials);
    authState.user = response.data.user;
    authState.isAuthenticated = true;
    return response;
};

export const logout = async () => {
    await axios.post('/api/admin/logout');
    authState.user = null;
    authState.isAuthenticated = false;
};
