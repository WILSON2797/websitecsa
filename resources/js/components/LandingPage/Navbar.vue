<template>
  <!-- NAVBAR -->
  <nav id="navbar" :class="{ 'scrolled': isScrolled }">
    <div class="nav-inner">
      <div class="logo">
        <div class="logo-mark"><i class="ti ti-hammer" aria-hidden="true"></i></div>
        <div class="logo-text">
          <span class="logo-pt">PT.</span>
          <span class="logo-name">CAHAYA SENTOSA ABADI</span>
        </div>
      </div>
      <div class="nav-links">
        <router-link to="/" :class="{ 'active': activeSection === 'hero' }">Home</router-link>
        <router-link to="/about" :class="{ 'active': activeSection === 'about' }">Tentang Kami</router-link>
        <router-link to="/products" :class="{ 'active': activeSection === 'products' }">Produk</router-link>
        <router-link to="/machines" :class="{ 'active': activeSection === 'machines' }">Mesin</router-link>
        <router-link to="/facilities" :class="{ 'active': activeSection === 'facilities' }">Fasilitas</router-link>
        <router-link to="/quality" :class="{ 'active': activeSection === 'quality' }">Kualitas</router-link>
        <router-link to="/clients" :class="{ 'active': activeSection === 'clients' }">Klien</router-link>
        <router-link to="/contact" :class="{ 'active': activeSection === 'contact' }">Kontak</router-link>
      </div>
      <router-link class="nav-quote" to="/contact">Request Quote</router-link>
    </div>
  </nav>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
  name: 'Navbar',
  props: {
    activeSection: {
      type: String,
      default: 'hero'
    }
  },
  setup() {
    const isScrolled = ref(false);

    const handleScroll = () => {
      isScrolled.value = window.scrollY > 20;
    };

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
      handleScroll(); // Check immediately on mount
    });

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll);
    });

    return {
      isScrolled
    };
  }
};
</script>
