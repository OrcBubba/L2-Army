<template>
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="wrap hero-grid">
      <div class="hero-copy">
        <p class="eyebrow">L2 Army · CT 2.6 High Five</p>
        <h1>A quiet kingdom.<br />A loud battlefield.</h1>
        <p class="lead">
          Classic High Five sieges, castle politics, and ranked PvP — presented as a clean private-server home rather than a storefront clone.
        </p>

        <div class="cta-row">
          <a class="btn btn-magenta" :href="acmLoginUrl">Register</a>
          <a class="btn btn-navy" :href="stats?.downloadUrl || '#'">Download client</a>
          <a class="btn btn-ghost" :href="stats?.discordUrl || 'https://discord.com'" target="_blank" rel="noreferrer">Community</a>
        </div>

        <div class="rate-row">
          <span>XP x{{ stats?.rates?.xp ?? '—' }}</span>
          <span>SP x{{ stats?.rates?.sp ?? '—' }}</span>
          <span>Adena x{{ stats?.rates?.adena ?? '—' }}</span>
          <span>Drop x{{ stats?.rates?.drop ?? '—' }}</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ACM_LOGIN_URL } from '../config';

defineProps({
  stats: { type: Object, default: null },
});

const acmLoginUrl = ACM_LOGIN_URL;
</script>

<style scoped>
.hero {
  position: relative;
  overflow: hidden;
  min-height: 760px;
  background: linear-gradient(180deg, #e4f0e6 0%, #f4f8f4 70%);
}

.hero-bg {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(90deg, rgba(244, 248, 244, 0.94) 18%, rgba(228, 240, 230, 0.4) 55%, rgba(11, 24, 17, 0.12) 100%),
    url('/placeholders/hero-bg.png') center / cover no-repeat;
  opacity: 0.9;
}

.hero-grid {
  position: relative;
  display: grid;
  grid-template-columns: 1.05fr 0.95fr;
  align-items: end;
  min-height: 760px;
  padding-top: 48px;
}

.hero-copy {
  padding-bottom: 90px;
  max-width: 560px;
}

h1 {
  font-size: clamp(3rem, 6vw, 5.3rem);
  line-height: 0.92;
  color: var(--navy);
  margin: 14px 0 18px;
}

.lead {
  font-size: 1.08rem;
  color: var(--muted);
  line-height: 1.7;
  margin-bottom: 28px;
}

.cta-row {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 22px;
}

.rate-row {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.rate-row span {
  background: rgba(255, 255, 255, 0.72);
  border: 1px solid var(--line);
  border-radius: 999px;
  padding: 7px 12px;
  font-size: 0.82rem;
  color: var(--navy);
}

.hero-art {
  display: flex;
  justify-content: center;
}

.hero-art img {
  height: 700px;
  width: auto;
  object-fit: contain;
  filter: drop-shadow(0 30px 40px rgba(11, 24, 17, 0.28));
}

@media (max-width: 980px) {
  .hero,
  .hero-grid {
    min-height: auto;
  }

  .hero-grid {
    grid-template-columns: 1fr;
    padding-top: 36px;
  }

  .hero-copy {
    padding-bottom: 20px;
  }

  .hero-art img {
    height: 460px;
  }
}
</style>
