<template>
  <!-- FOOTER -->
  <footer>
    <div class="footer-main">
      <div class="footer-brand">
        <div class="footer-logo-row">
          <div class="footer-logo-mark"><i class="ti ti-hammer" aria-hidden="true"></i></div>
          <span class="footer-brand-text">PT. Cahaya Sentosa Abadi</span>
        </div>
        <p class="footer-tagline">Produsen komponen stamping logam presisi untuk industri otomotif, elektronik, dan manufaktur. Berkomitmen pada kualitas dan ketepatan waktu sejak 1998.</p>
        <div class="footer-cert-row">
          <span class="footer-cert">ISO 9001:2015</span>
          <span class="footer-cert">IATF 16949</span>
          <span class="footer-cert">ISO 14001</span>
        </div>
      </div>
      <div>
        <div class="footer-col-title">NAVIGASI</div>
        <div class="footer-links">
          <router-link to="/">Home</router-link>
          <router-link to="/about">Tentang Kami</router-link>
          <router-link to="/products">Produk</router-link>
          <router-link to="/machines">Mesin</router-link>
          <router-link to="/quality">Kualitas</router-link>
          <router-link to="/clients">Klien</router-link>
          <router-link to="/contact">Kontak</router-link>
        </div>
      </div>
      <div>
        <div class="footer-col-title">LAYANAN</div>
        <div class="footer-links">
          <router-link to="/products">Progressive Stamping</router-link>
          <router-link to="/products">Deep Drawing</router-link>
          <router-link to="/products">Fine Blanking</router-link>
          <router-link to="/products">Transfer Stamping</router-link>
          <router-link to="/products">Tool & Die Making</router-link>
          <router-link to="/products">Surface Treatment</router-link>
        </div>
      </div>
      <div>
        <div class="footer-col-title">KONTAK</div>
        <div class="footer-links">
          <a href="tel:+622189901234">{{ content.contact_telp || '+62 21 8990 1234' }}</a>
          <a href="mailto:marketing@cahayasentosa.co.id">{{ content.contact_email_1 || 'marketing@cahayasentosa.co.id' }}</a>
          <router-link to="/contact">MM2100, Cikarang Barat</router-link>
          <router-link to="/contact">Bekasi, Jawa Barat</router-link>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-bottom-inner">
        <span class="footer-copy">© 2025 PT. Cahaya Sentosa Abadi. All rights reserved.</span>
        <span class="footer-copy">Precision Metal Stamping Solutions</span>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  }
};
</script>
