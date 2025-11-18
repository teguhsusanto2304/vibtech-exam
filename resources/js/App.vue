<template>
  <div id="app" class="min-h-screen bg-gray-50 text-gray-900">
    <header class="p-4 bg-blue-600 text-white text-lg font-semibold">
      Examination Portal
    </header>

    <main class="p-6">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
// This file serves as the main Vue component.
// The <router-view> dynamically loads the current page from router/index.js.
</script>

<style>
body {
  margin: 0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}
</style>
