<template>
  <section id="features" class="section">
    <div class="wrap">
      <p class="eyebrow">Why play</p>
      <h2>Built for High Five</h2>
      <div class="feature-grid">
        <article class="card feature" v-for="item in items" :key="item.title">
          <span>{{ item.kicker }}</span>
          <h3>{{ item.title }}</h3>
          <p>{{ item.text }}</p>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
const items = [
  {
    kicker: '01',
    title: 'Castle politics',
    text: 'Nine High Five castles, tax control, and siege registration through the L2J Mobius game database.',
  },
  {
    kicker: '02',
    title: 'Fair PvP board',
    text: 'Public rankings hide GameMasters and characters queued for deletion so the ladder stays honest.',
  },
  {
    kicker: '03',
    title: 'Classic chronicle',
    text: 'CT 2.6 High Five content: vitality, Freya, territory flavor, and a level 85 cap.',
  },
  {
    kicker: '04',
    title: 'Beginner-friendly stack',
    text: 'Vue 3, Express, and mysql2 with mock fallback so you can design the site before the SQL pack is imported.',
  },
];
</script>

<style scoped>
h2 {
  font-size: 3rem;
  color: var(--navy);
  margin: 8px 0 28px;
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.feature {
  padding: 24px;
  min-height: 230px;
}

.feature span {
  color: var(--magenta);
  font-weight: 700;
}

h3 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.6rem;
  margin: 12px 0 10px;
}

p {
  color: var(--muted);
  line-height: 1.6;
}

@media (max-width: 980px) {
  .feature-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 620px) {
  .feature-grid {
    grid-template-columns: 1fr;
  }
}
</style>
