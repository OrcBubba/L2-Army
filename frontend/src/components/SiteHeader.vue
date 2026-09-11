<template>
  <header class="header">
    <div class="wrap header-inner">
      <router-link to="/" class="brand">
        <span class="brand-mark">L2A</span>
        
        <span>
          <strong>{{ stats?.serverName || 'L2 Army' }}</strong>
          <small>High Five · CT 2.6</small>
        </span>
      </router-link>

      <nav class="nav">
        <a href="/#rankings">Rankings</a>
        <a href="/#castles">Castles</a>
        <a href="/#raid-bosses">Raids</a>
        <a href="/#features">Features</a>
        <router-link to="/register">Register</router-link>
      </nav>

      <div class="header-actions">
        <a class="online-pill" href="/#status">
          <i></i>
          {{ stats?.onlinePlayers ?? '—' }} online
        </a>
        <a class="btn btn-magenta" :href="stats?.downloadUrl || '#'">Download</a>
      </div>
    </div>
  </header>
</template>

<script setup>
defineProps({
  stats: { type: Object, default: null },
});
</script>

<style scoped>
.header {
  position: sticky;
  top: 0;
  z-index: 20;
  backdrop-filter: blur(16px);
  background: rgba(244, 248, 244, 0.88);
  border-bottom: 1px solid var(--line);
}

.header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 76px;
  gap: 20px;
}

.brand {
  display: flex;
  align-items: center;
  gap: 12px;
}

.brand-mark {
  width: 42px;
  height: 42px;
  border-radius: 14px;
  display: grid;
  place-items: center;
  background: var(--navy);
  color: white;
  font-family: 'Cormorant Garamond', serif;
  font-weight: 700;
}

.brand strong {
  display: block;
  font-size: 1.02rem;
}

.brand small {
  color: var(--muted);
  font-size: 0.75rem;
}

.nav {
  display: flex;
  gap: 22px;
  color: var(--navy-soft);
  font-weight: 500;
}

.nav a:hover {
  color: var(--magenta);
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.online-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: white;
  border: 1px solid var(--line);
  border-radius: 999px;
  padding: 8px 12px;
  font-size: 0.86rem;
}

.online-pill i {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #3cb371;
  box-shadow: 0 0 0 4px rgba(47, 158, 79, 0.22);
}

@media (max-width: 860px) {
  .nav {
    display: none;
  }
}
</style>
