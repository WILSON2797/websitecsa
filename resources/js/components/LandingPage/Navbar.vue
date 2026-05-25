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
      <div class="nav-actions">
        <router-link class="nav-quote" to="/contact">Request Quote</router-link>
        <button class="hamburger-btn" @click="toggleMobileMenu" aria-label="Toggle menu">
          <i :class="isMobileMenuOpen ? 'ti ti-x' : 'ti ti-menu-2'"></i>
        </button>
      </div>
    </div>
    
    <!-- Mobile Menu -->
    <transition name="slide-down">
      <div v-if="isMobileMenuOpen" class="mobile-menu">
        <router-link to="/" @click="closeMobileMenu" :class="{ 'active': activeSection === 'hero' }">Home</router-link>
        <router-link to="/about" @click="closeMobileMenu" :class="{ 'active': activeSection === 'about' }">About Us</router-link>
        <router-link to="/products" @click="closeMobileMenu" :class="{ 'active': activeSection === 'products' }">Products</router-link>
        <router-link to="/machines" @click="closeMobileMenu" :class="{ 'active': activeSection === 'machines' }">Machines</router-link>
        <router-link to="/facilities" @click="closeMobileMenu" :class="{ 'active': activeSection === 'facilities' }">Facilities</router-link>
        <router-link to="/quality" @click="closeMobileMenu" :class="{ 'active': activeSection === 'quality' }">Quality</router-link>
        <router-link to="/clients" @click="closeMobileMenu" :class="{ 'active': activeSection === 'clients' }">Clients</router-link>
        <router-link to="/documentation" @click="closeMobileMenu" :class="{ 'active': activeSection === 'documentation' }">Documentation</router-link>
        <router-link to="/contact" @click="closeMobileMenu" :class="{ 'active': activeSection === 'contact' }">Contact</router-link>
        <router-link class="mobile-nav-quote" to="/contact" @click="closeMobileMenu">Request Quote</router-link>
      </div>
    </transition>
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
    const isMobileMenuOpen = ref(false);

    const handleScroll = () => {
      isScrolled.value = window.scrollY > 20;
    };

    const toggleMobileMenu = () => {
      isMobileMenuOpen.value = !isMobileMenuOpen.value;
    };

    const closeMobileMenu = () => {
      isMobileMenuOpen.value = false;
    };

    onMounted(() => {
      window.addEventListener('scroll', handleScroll);
      handleScroll(); // Check immediately on mount
    });

    onUnmounted(() => {
      window.removeEventListener('scroll', handleScroll);
    });

    return {
      isScrolled,
      isMobileMenuOpen,
      toggleMobileMenu,
      closeMobileMenu
    };
  }
};
</script>

<style scoped>
.nav-logo-img {
  height: 40px;
  width: auto;
  object-fit: contain;
  /* border-radius: 6px;
  background-color: #f1f1f1; */
  padding: 4px 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border: 0.1px solid #f1f1f1d2;
  border-radius: 8px;
  
}
</style>  
