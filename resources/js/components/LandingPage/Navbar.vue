<template>
  <!-- NAVBAR -->
  <nav id="navbar" :class="{ 'scrolled': isScrolled }">
    <div class="nav-inner">
      <div class="logo">
        <img :src="'/LogoCSA2.png'" alt="CSA Logo" class="nav-logo-img" />
        <div class="logo-text">
          <span class="logo-name">CAHAYA SENTOSA ABADI</span>
        </div>
      </div>
      <div class="nav-links">
        <router-link to="/" :class="{ 'active': activeSection === 'hero' }">Home</router-link>
        <router-link to="/about" :class="{ 'active': activeSection === 'about' }">About Us</router-link>
        <router-link to="/products" :class="{ 'active': activeSection === 'products' }">Products</router-link>
        <router-link to="/machines" :class="{ 'active': activeSection === 'machines' }">Machines</router-link>
        <router-link to="/facilities" :class="{ 'active': activeSection === 'facilities' }">Facilities</router-link>
        <router-link to="/quality" :class="{ 'active': activeSection === 'quality' }">Quality</router-link>
        <router-link to="/clients" :class="{ 'active': activeSection === 'clients' }">Clients</router-link>
        <router-link to="/documentation" :class="{ 'active': activeSection === 'documentation' }">Documentation</router-link>
        <router-link to="/contact" :class="{ 'active': activeSection === 'contact' }">Contact</router-link>
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

<style scoped>
.nav-logo-img {
  height: 40px;
  width: auto;
  object-fit: contain;
  border-radius: 4px;
}
</style>
