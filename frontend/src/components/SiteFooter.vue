<template>
  <footer class="footer">
    <div class="wrap footer-inner">
      <div>
        <p class="display">{{ stats?.serverName || 'L2 Army' }}</p>
        <p class="fine">Independent High Five private server. Our goal is to recreate a retail like server. Normal rates and drops. Never pay for power!</p>
      </div>
      <div class="footer-links">
        <a href="/#rankings">Rankings</a>
        <a href="/#castles">Castles</a>
        <a href="/#raid-bosses">Raids</a>
        <a :href="acmLoginUrl">Create account</a>
        <a :href="stats?.discordUrl || 'https://discord.com'" target="_blank" rel="noreferrer">Discord</a>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ACM_LOGIN_URL } from '../config';

defineProps({
  stats: { type: Object, default: null },
});

const acmLoginUrl = ACM_LOGIN_URL;
</script>

<style scoped>
.footer {
  background: var(--navy-deep);
  color: #d5e8d9;
  padding: 48px 0;
}

.footer-inner {
  display: flex;
  justify-content: space-between;
  gap: 32px;
}

.display {
  color: white;
  font-size: 1.8rem;
  margin-bottom: 8px;
}

.fine {
  max-width: 420px;
  color: #8eab96;
  line-height: 1.6;
  font-size: 0.92rem;
}

.footer-links {
  display: flex;
  gap: 18px;
  align-items: center;
}

@media (max-width: 720px) {
  .footer-inner,
  .footer-links {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
