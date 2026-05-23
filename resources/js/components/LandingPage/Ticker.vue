<template>
  <!-- TICKER -->
  <div class="ticker">
    <div class="ticker-inner" aria-hidden="true">
      <!-- Render original list items twice to ensure endless looping gapless effect -->
      <span v-for="(item, idx) in loopItems" :key="idx" class="ticker-item">
        <span class="ticker-dot"></span>{{ item }}
      </span>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';

export default {
  name: 'Ticker',
  props: {
    content: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const originalItems = computed(() => {
      if (props.content.ticker_items) {
        try {
          return typeof props.content.ticker_items === 'string'
            ? JSON.parse(props.content.ticker_items)
            : props.content.ticker_items;
        } catch (e) {
          console.error('Failed to parse ticker_items:', e);
        }
      }
      return [
        'Progressive Die Stamping',
        'Transfer Die Stamping',
        'Deep Drawing',
        'Fine Blanking',
        'Tool & Die Making',
        'Roll Forming',
        'Compound Die Stamping',
        'Surface Treatment'
      ];
    });

    // Loop twice to construct the infinite seamless scroll ticker
    const loopItems = computed(() => {
      const items = originalItems.value;
      return [...items, ...items];
    });

    return {
      loopItems
    };
  }
};
</script>
