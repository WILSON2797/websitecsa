<template>
  <!-- FOOTER -->
  <footer>
    <div class="footer-main">
      <div class="footer-brand">
        <div class="footer-logo-row">
          <div class="footer-logo-mark"><i class="ti ti-hammer" aria-hidden="true"></i></div>
          <span class="footer-brand-text">{{ content.footer_brand_name || 'PT. Cahaya Sentosa Abadi' }}</span>
        </div>
        <p class="footer-tagline">{{ content.footer_tagline || 'Produsen komponen stamping logam presisi untuk industri otomotif, elektronik, dan manufaktur. Berkomitmen pada kualitas dan ketepatan waktu sejak 1998.' }}</p>
        <div class="footer-cert-row" v-if="certBadges && certBadges.length">
          <span v-for="cert in certBadges" :key="cert.id || cert.name" class="footer-cert">{{ cert.name }}</span>
        </div>
      </div>
      <div>
        <div class="footer-col-title">NAVIGATION</div>
        <div class="footer-links">
          <router-link to="/">Home</router-link>
          <router-link to="/about">About Us</router-link>
          <router-link to="/products">Products</router-link>
          <router-link to="/machines">Machines</router-link>
          <router-link to="/quality">Quality</router-link>
          <router-link to="/clients">Clients</router-link>
          <router-link to="/contact">Contact</router-link>
        </div>
      </div>
      <div>
        <div class="footer-col-title">SERVICES</div>
        <div class="footer-links">
          <template v-if="footerServices && footerServices.length">
            <router-link v-for="svc in footerServices" :key="svc" to="/products">{{ svc }}</router-link>
          </template>
          <template v-else>
            <router-link to="/products">Progressive Stamping</router-link>
            <router-link to="/products">Deep Drawing</router-link>
            <router-link to="/products">Fine Blanking</router-link>
            <router-link to="/products">Transfer Stamping</router-link>
            <router-link to="/products">Tool &amp; Die Making</router-link>
            <router-link to="/products">Surface Treatment</router-link>
          </template>
        </div>
      </div>
      <div>
        <div class="footer-col-title">CONTACT</div>
        <div class="footer-links">
          <a :href="'tel:' + (content.contact_telp || '+622189901234').replace(/\s/g, '')">{{ content.contact_telp || '+62 21 8990 1234' }}</a>
          <a :href="'mailto:' + (content.contact_email_1 || 'marketing@cahayasentosa.co.id')">{{ content.contact_email_1 || 'marketing@cahayasentosa.co.id' }}</a>
          <router-link to="/contact">{{ content.contact_alamat || 'MM2100, Cikarang Barat' }}</router-link>
          <router-link to="/contact">{{ content.contact_alamat_sub || 'Bekasi, Jawa Barat' }}</router-link>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-bottom-inner">
        <span class="footer-copy">© {{ currentYear }} {{ content.footer_company_name || 'PT. Cahaya Sentosa Abadi. All rights reserved.' }}</span>
        <span class="footer-copy">{{ content.footer_subcopy || 'Precision Metal Stamping Solutions' }}</span>
      </div>
    </div>
  </footer>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'Footer',
  props: {
    content: {
      type: Object,
      default: () => ({})
    },
    certificates: {
      type: Array,
      default: () => []
    }
  },
  setup(props) {
    // Ambil tahun berjalan secara otomatis
    const currentYear = new Date().getFullYear();

    // Badge sertifikasi langsung dari data certificates di database
    const certBadges = computed(() => props.certificates || []);

    // Daftar layanan dari CMS
    const footerServices = computed(() => {
      if (!props.content.footer_services) return null;
      try { return JSON.parse(props.content.footer_services); } catch { return null; }
    });

    return { currentYear, certBadges, footerServices };
  }
};
</script>
