<template>
  <div class="admin-page">
    <div class="page-header">
      <h2 class="page-title">Pesan Masuk (Inquiries)</h2>
    </div>

    <div class="table-container">
      <div v-if="loading" class="text-center p-4">Loading...</div>
      <table v-else class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Perusahaan</th>
            <th>Pesan</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in inquiries" :key="item.id">
            <td>{{ item.id }}</td>
            <td><strong>{{ item.name }}</strong></td>
            <td>{{ item.email }}</td>
            <td>{{ item.company }}</td>
            <td><p class="message-text">{{ item.message }}</p></td>
            <td>{{ new Date(item.created_at).toLocaleDateString('id-ID') }}</td>
          </tr>
          <tr v-if="inquiries.length === 0">
            <td colspan="6" class="text-center p-4">Belum ada pesan masuk.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'AdminInquiries',
  setup() {
    const inquiries = ref([]);
    const loading = ref(true);

    const loadData = async () => {
      loading.value = true;
      try {
        const res = await axios.get('/api/admin/inquiries');
        inquiries.value = res.data;
      } catch (err) {
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      loadData();
    });

    return {
      inquiries, loading
    };
  }
};
</script>

<style scoped>
.message-text {
  max-width: 300px;
  white-space: pre-wrap;
  word-break: break-word;
  font-size: 0.9rem;
  line-height: 1.4;
}
</style>
