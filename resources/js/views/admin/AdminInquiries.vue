<template>
  <div class="admin-page">
    <div class="page-header">
      <h2 class="page-title">Pesan Masuk (Inquiries)</h2>
    </div>

    <div class="table-container">
      <DataTable
        :data="inquiries"
        :columns="columns"
        :pagination="pagination"
        :loading="loading"
        @page-change="loadData"
      />
    </div>
  </div>
</template>

<script>
import { ref, onMounted, h } from 'vue';
import axios from 'axios';
import DataTable from '../../components/admin/DataTable.vue';

export default {
  name: 'AdminInquiries',
  components: {
    DataTable
  },
  setup() {
    const inquiries = ref([]);
    const pagination = ref(null);
    const loading = ref(true);

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
        accessorKey: 'name',
        header: 'Nama',
        cell: ({ row }) => h('strong', {}, row.original.name)
      },
      {
        accessorKey: 'email',
        header: 'Email'
      },
      {
        accessorKey: 'company',
        header: 'Perusahaan'
      },
      {
        accessorKey: 'message',
        header: 'Pesan',
        cell: ({ row }) => h('p', { class: 'message-text' }, row.original.message)
      },
      {
        accessorKey: 'created_at',
        header: 'Tanggal',
        cell: ({ row }) => new Date(row.original.created_at).toLocaleDateString('id-ID')
      }
    ];

    const loadData = async (page = 1) => {
      loading.value = true;
      try {
        const res = await axios.get(`/api/admin/inquiries?page=${page}`);
        inquiries.value = res.data.data;
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

    onMounted(() => {
      loadData();
    });

    return {
      inquiries, loading, columns, pagination
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
