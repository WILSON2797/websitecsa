<template>
  <div class="intro-loader-wrapper">
    <!-- Blueprint Grid Background -->
    <div class="intro-blueprint-grid"></div>
    
    <!-- Central Animation Container -->
    <div class="intro-center-stage">
      
      <!-- SVG Robotic Gear & Scanner -->
      <div class="intro-graphic">
        <!-- Outer Rotating Gear -->
        <svg class="gear-svg animate-spin-slow" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="none" stroke="var(--accent)" stroke-width="2" stroke-dasharray="4 8" d="M50 10 A40 40 0 1 1 49.9 10" />
          <circle cx="50" cy="50" r="35" fill="none" stroke="var(--blue)" stroke-width="1" opacity="0.5" />
          <!-- Gear teeth -->
          <path fill="var(--accent)" opacity="0.8" d="M46 15 h8 v5 h-8 z M46 80 h8 v5 h-8 z M15 46 h5 v8 h-5 z M80 46 h5 v8 h-5 z" />
          <path fill="var(--accent)" opacity="0.8" transform="rotate(45 50 50)" d="M46 15 h8 v5 h-8 z M46 80 h8 v5 h-8 z M15 46 h5 v8 h-5 z M80 46 h5 v8 h-5 z" />
        </svg>

        <!-- Inner Core (Pulsing) -->
        <div class="inner-core pulse-glow">
          <span class="core-text">CSA</span>
        </div>

        <!-- Laser Scanner Line -->
        <div class="scanner-laser"></div>
      </div>

      <h2 class="company-name-title">CAHAYA SENTOSA ABADI</h2>

      <!-- Typewriter Status Text -->
      <div class="intro-status">
        <div class="status-bar-container">
          <div class="status-bar-fill"></div>
        </div>
        <p class="typewriter-text">{{ loadingText }}</p>
      </div>

    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'IntroLoader',
  setup() {
    const loadingText = ref('INITIALIZING SYSTEMS...');
    
    onMounted(() => {
      const messages = [
        'INITIALIZING SYSTEMS...',
        'CONNECTING TO INFRASTRUCTURE...',
        'LOADING ENGINEERING MODULES...',
        'CALIBRATING PRECISION TOOLS...',
        'SYSTEM READY.'
      ];
      
      let step = 0;
      const interval = setInterval(() => {
        step++;
        if (step < messages.length) {
          loadingText.value = messages[step];
        } else {
          clearInterval(interval);
        }
      }, 500); // Update text every 500ms
    });

    return {
      loadingText
    };
  }
};
</script>

<style scoped>
.intro-loader-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: var(--navy, #0A192F);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  font-family: 'Inter', monospace;
}

/* Blueprint Grid */
.intro-blueprint-grid {
  position: absolute;
  inset: 0;
  opacity: 0.15;
  background-image: 
    linear-gradient(rgba(74, 143, 204, 0.4) 1px, transparent 1px),
    linear-gradient(90deg, rgba(74, 143, 204, 0.4) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
}

.intro-blueprint-grid::after {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at center, transparent 0%, var(--navy) 80%);
}

.intro-center-stage {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Graphic Container */
.intro-graphic {
  position: relative;
  width: 200px;
  height: 200px;
  margin-bottom: 40px;
}

/* Gear SVG */
.gear-svg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.animate-spin-slow {
  animation: spin 8s linear infinite;
}

/* Inner Core */
.inner-core {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: rgba(15, 42, 66, 0.8);
  border: 2px solid var(--accent, #3b82f6);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 20px rgba(59, 130, 246, 0.4) inset;
  overflow: hidden;
}

.pulse-glow {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.core-text {
  color: var(--white, #ffffff);
  font-family: var(--font-head, 'Inter', sans-serif);
  font-size: 28px;
  font-weight: 800;
  letter-spacing: 2px;
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

.company-name-title {
  color: var(--white, #ffffff);
  font-family: var(--font-head, 'Inter', sans-serif);
  font-size: 20px;
  font-weight: 700;
  letter-spacing: 4px;
  margin-bottom: 24px;
  text-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
}

/* Scanner Laser */
.scanner-laser {
  position: absolute;
  top: 0;
  left: -20%;
  width: 140%;
  height: 2px;
  background: var(--accent, #3b82f6);
  box-shadow: 0 0 15px 5px rgba(59, 130, 246, 0.5);
  animation: scan 2s ease-in-out infinite alternate;
  z-index: 3;
}

/* Status Area */
.intro-status {
  width: 280px;
  text-align: center;
}

.status-bar-container {
  width: 100%;
  height: 4px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 2px;
  margin-bottom: 15px;
  overflow: hidden;
  position: relative;
}

.status-bar-fill {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  background: var(--accent, #3b82f6);
  box-shadow: 0 0 10px var(--accent);
  animation: progress 2.5s ease-out forwards;
}

.typewriter-text {
  color: var(--accent, #3b82f6);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 2px;
  margin: 0;
  text-transform: uppercase;
  font-family: monospace;
}

/* Animations */
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; box-shadow: 0 0 20px rgba(59, 130, 246, 0.4) inset, 0 0 10px rgba(59, 130, 246, 0.2); }
  50% { opacity: 0.8; box-shadow: 0 0 30px rgba(59, 130, 246, 0.8) inset, 0 0 20px rgba(59, 130, 246, 0.6); }
}

@keyframes scan {
  0% { top: 10%; opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { top: 90%; opacity: 0; }
}

@keyframes progress {
  0% { width: 0%; }
  20% { width: 30%; }
  50% { width: 60%; }
  80% { width: 90%; }
  100% { width: 100%; }
}
</style>
