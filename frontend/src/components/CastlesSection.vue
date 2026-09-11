<template>
  <section id="castles" class="section castle-section">
    <div class="wrap">
      <div class="section-head">
        <div>
          <p class="eyebrow">Territory</p>
          <h2>Castle Ownership</h2>
        </div>
      </div>

      <div class="castle-grid">
        <article v-for="castle in castles" :key="castle.id" class="card castle-card">
          <img src="/placeholders/castle-placeholder.png" :alt="`${castle.name} castle placeholder`" />
          <div class="body">
            <h3>{{ castle.name }}</h3>
            <p class="owner">{{ castle.ownerClan || 'Unclaimed' }}</p>
            <dl>
              <div>
                <dt>Tax</dt>
                <dd>{{ castle.taxPercent }}%</dd>
              </div>
              <div>
                <dt>Siege</dt>
                <dd>{{ formatSiege(castle.nextSiegeIso) }}</dd>
              </div>
            </dl>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { inject } from 'vue';

const castles = inject('castles');

function formatSiege(iso) {
  if (!iso) {
    return 'Unset';
  }
  return new Date(iso).toLocaleDateString(undefined, {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}
</script>

<style scoped>
.castle-section {
  background: linear-gradient(180deg, #e4f0e6, #f4f8f4);
}

.section-head {
  display: flex;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 28px;
  align-items: end;
}

h2 {
  font-size: 3rem;
  color: var(--navy);
}

.section-head p {
  max-width: 380px;
  color: var(--muted);
}

.castle-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}

.castle-card {
  overflow: hidden;
}

.castle-card img {
  height: 150px;
  width: 100%;
  object-fit: cover;
}

.body {
  padding: 16px 18px 20px;
}

h3 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.5rem;
}

.owner {
  color: var(--magenta);
  font-weight: 600;
  margin: 4px 0 12px;
}

dl {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
}

dt {
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--muted);
}

dd {
  font-weight: 600;
}

@media (max-width: 900px) {
  .castle-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 620px) {
  .castle-grid,
  .section-head {
    grid-template-columns: 1fr;
    display: grid;
  }
}
</style>
