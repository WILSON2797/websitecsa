<template>
  <!-- CONTACT -->
  <section id="contact" class="section">
    <div class="section-inner">
      <div class="section-header" data-aos="fade-down">
        <div class="section-eyebrow">{{ content.contact_eyebrow || 'KONTAK' }}</div>
        <h2 class="section-title">{{ content.contact_title || 'Hubungi Kami' }}</h2>
        <p class="section-sub">{{ content.contact_sub || 'Tim kami siap membantu kebutuhan stamping Anda. Kirimkan pertanyaan atau request quotation.' }}</p>
      </div>
      <div class="contact-grid">
        
        <!-- Info Column (Left) -->
        <div class="contact-info" data-aos="fade-up" data-aos-duration="1000">
          <div class="contact-item">
            <div class="contact-icon"><i class="ti ti-map-pin" aria-hidden="true"></i></div>
            <div>
              <div class="contact-label">ALAMAT</div>
              <div class="contact-value">{{ content.contact_alamat || 'Kawasan Industri MM2100' }}</div>
              <div class="contact-value-sub" v-html="addressSubHtml"></div>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon"><i class="ti ti-phone" aria-hidden="true"></i></div>
            <div>
              <div class="contact-label">TELEPON</div>
              <div class="contact-value">{{ content.contact_telp || '+62 21 8990 1234' }}</div>
              <div class="contact-value-sub">{{ content.contact_fax || '+62 21 8990 1235 (Fax)' }}</div>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon"><i class="ti ti-mail" aria-hidden="true"></i></div>
            <div>
              <div class="contact-label">EMAIL</div>
              <div class="contact-value">{{ content.contact_email_1 || 'marketing@cahayasentosa.co.id' }}</div>
              <div class="contact-value-sub">{{ content.contact_email_2 || 'info@cahayasentosa.co.id' }}</div>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon"><i class="ti ti-clock" aria-hidden="true"></i></div>
            <div>
              <div class="contact-label">JAM OPERASIONAL</div>
              <div class="contact-value">{{ content.contact_jam_1 || 'Senin — Jumat: 08.00 — 17.00' }}</div>
              <div class="contact-value-sub">{{ content.contact_jam_2 || 'Sabtu: 08.00 — 13.00 WIB' }}</div>
            </div>
          </div>
          
          <!-- Social Media Icons -->
          <div class="social-media-bar" style="margin-top: 32px; display: flex; gap: 16px; align-items: center;">
            <a v-if="content.social_fb" :href="content.social_fb" target="_blank" class="social-icon-btn" style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:#1877F2; color:white; font-size:20px; transition:transform 0.2s; text-decoration:none;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'"><i class="ti ti-brand-facebook"></i></a>
            
            <a v-if="content.social_ig" :href="content.social_ig" target="_blank" class="social-icon-btn" style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color:white; font-size:20px; transition:transform 0.2s; text-decoration:none;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'"><i class="ti ti-brand-instagram"></i></a>
            
            <a v-if="content.social_tiktok" :href="content.social_tiktok" target="_blank" class="social-icon-btn" style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:#000000; color:white; font-size:20px; transition:transform 0.2s; text-decoration:none;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'"><i class="ti ti-brand-tiktok"></i></a>
            
            <a v-if="content.social_wa" :href="'https://wa.me/' + content.social_wa" target="_blank" class="social-icon-btn" style="display:flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:50%; background:#25D366; color:white; font-size:20px; transition:transform 0.2s; text-decoration:none;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'"><i class="ti ti-brand-whatsapp"></i></a>
          </div>

          <!-- Google Maps Embed -->
          <div class="contact-map" style="margin-top: 24px; border-radius: 8px; overflow: hidden; height: 180px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid var(--gray100);">
            <iframe 
              :src="content.contact_map_embed || 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.748644503706!2d107.0863004147696!3d-6.296683895442533!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698fd447af7733%3A0xc6ed7ed9ddc8f2ba!2sKawasan%20Industri%20MM2100!5e0!3m2!1sen!2sid!4v1655444002621!5m2!1sen!2sid'" 
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
        
        <!-- Quote Form Column (Right) -->
        <div class="contact-form" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
          <div class="form-title">Request Quotation</div>
          
          <!-- Success Alert -->
          <div v-if="success" class="alert alert-success p-3 rounded-lg mb-4" style="background-color: #d1e7dd; border: 1px solid #badbcc; color: #0f5132; font-size: 14px;">
            <i class="ti ti-circle-check me-1"></i> {{ successMessage }}
          </div>
          
          <!-- Error Alert -->
          <div v-if="error" class="alert alert-danger p-3 rounded-lg mb-4" style="background-color: #f8d7da; border: 1px solid #f5c2c7; color: #842029; font-size: 14px;">
            <i class="ti ti-alert-triangle me-1"></i> {{ errorMessage }}
          </div>

          <form @submit.prevent="handleSubmit">
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label" for="nama">Nama Lengkap *</label>
                <input class="form-input" type="text" id="nama" v-model="form.nama" placeholder="Nama Anda" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="perusahaan">Perusahaan</label>
                <input class="form-input" type="text" id="perusahaan" v-model="form.perusahaan" placeholder="Nama perusahaan">
              </div>
              <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input class="form-input" type="email" id="email" v-model="form.email" placeholder="email@perusahaan.com" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="telp">No. Telepon</label>
                <input class="form-input" type="tel" id="telp" v-model="form.telp" placeholder="+62 xxx xxxx xxxx">
              </div>
              <div class="form-group full">
                <label class="form-label" for="layanan">Layanan yang Dibutuhkan</label>
                <select class="form-select" id="layanan" v-model="form.layanan">
                  <option value="">-- Pilih Layanan --</option>
                  <option value="Progressive Stamping">Progressive Stamping</option>
                  <option value="Deep Drawing">Deep Drawing</option>
                  <option value="Fine Blanking">Fine Blanking</option>
                  <option value="Transfer Stamping">Transfer Stamping</option>
                  <option value="Tool & Die Making">Tool & Die Making</option>
                  <option value="Surface Treatment">Surface Treatment</option>
                </select>
              </div>
              <div class="form-group full">
                <label class="form-label" for="pesan">Detail Kebutuhan *</label>
                <textarea class="form-textarea" id="pesan" v-model="form.pesan" placeholder="Jelaskan spesifikasi material, dimensi, volume, dan kebutuhan Anda..." required></textarea>
              </div>
            </div>
            <button type="submit" class="form-submit" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" style="width: 14px; height: 14px; border: 2px solid #fff; border-right-color: transparent; border-radius: 50%; display: inline-block; animation: spin 0.75s linear infinite;"></span>
              <i v-else class="ti ti-send" aria-hidden="true"></i>
              Kirim Permintaan
            </button>
          </form>
        </div>
        
      </div>
    </div>
  </section>
</template>

<script>
import { ref, computed } from 'vue';
import axios from 'axios';

export default {
  name: 'Contact',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const form = ref({
      nama: '',
      perusahaan: '',
      email: '',
      telp: '',
      layanan: '',
      pesan: ''
    });

    const loading = ref(false);
    const success = ref(false);
    const successMessage = ref('');
    const error = ref(false);
    const errorMessage = ref('');

    const addressSubHtml = computed(() => {
      const rawSub = props.content.contact_alamat_sub || 'Jl. Irian Blok PP-1, Cikarang Barat<br>Bekasi, Jawa Barat 17520';
      return rawSub;
    });

    const handleSubmit = async () => {
      loading.value = true;
      success.value = false;
      error.value = false;

      try {
        const response = await axios.post('/api/contact', form.value);
        if (response.data.success) {
          success.value = true;
          successMessage.value = response.data.message;
          // Reset form inputs
          form.value = {
            nama: '',
            perusahaan: '',
            email: '',
            telp: '',
            layanan: '',
            pesan: ''
          };
        } else {
          error.value = true;
          errorMessage.value = response.data.message || 'Gagal mengirim permintaan.';
        }
      } catch (err) {
        console.error(err);
        error.value = true;
        if (err.response && err.response.data && err.response.data.errors) {
          const firstErrKey = Object.keys(err.response.data.errors)[0];
          errorMessage.value = err.response.data.errors[firstErrKey][0];
        } else if (err.response && err.response.data && err.response.data.message) {
          errorMessage.value = err.response.data.message;
        } else {
          errorMessage.value = 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      success,
      successMessage,
      error,
      errorMessage,
      addressSubHtml,
      handleSubmit
    };
  }
};
</script>

<style scoped>
@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
