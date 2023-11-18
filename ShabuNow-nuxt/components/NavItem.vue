<template>
  <li>
    <button
        :class="['block px-3 py-2 rounded-md hover:bg-slate-500', isActive ? 'bg-black text-white' : 'bg-slate-200']"
    >
      <slot></slot>
    </button>
  </li>
</template>

<script setup>
defineProps({
  isActive: Boolean,
})
</script>