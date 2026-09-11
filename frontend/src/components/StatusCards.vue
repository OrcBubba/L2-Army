<template>
  <section id="status" class="status-band">
    <div class="wrap status-grid">
      <article class="status-card live">
        <p class="eyebrow">Players online</p>
        <h2>{{ stats?.onlinePlayers ?? '—' }}</h2>
        <p>of {{ stats?.maxOnline ?? '—' }} advertised slots</p>
        <span class="chip" :class="stats?.serverStatus === 'online' ? 'ok' : 'off'">
          Server {{ stats?.serverStatus || 'unknown' }}
        </span>
      </article>

      <article class="status-card">
        <p class="eyebrow">Next siege</p>
        <h2>{{ siegeLabel }}</h2>
        <p>{{ siegeDetail }}</p>
      </article>

      <article class="status-card">
        <p class="eyebrow">World</p>
        <h2>{{ stats?.totalCharacters ?? '—' }}</h2>
        <p>{{ stats?.maxLevelCharacters ?? 0 }} characters at level 85</p>
      </article>
    </div>
  </section>
</template>

<script setup>
import { computed, inject } from 'vue';

const props = defineProps({
  stats: { type: Object, default: null },
});

const castles = inject('castles');

const nextSiege = computed(() => {
  const list = castles?.value || [];
  const upcoming = list
    .map((castle) => ({ ...castle, ts: Number(castle.siegeDate) || 0 }))
    .filter((castle) => castle.ts > Date.now())
    .sort((a, b) => a.ts - b.ts)[0];
  return upcoming || list[0] || null;
});

const siegeLabel = computed(() => {
  if (!nextSiege.value?.nextSiegeIso) {
    return 'TBA';
  }
  return new Date(nextSiege.value.nextSiegeIso).toLocaleString(undefined, {
    weekday: 'short',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
});

const siegeDetail = computed(() => {
  if (!nextSiege.value) {
    return 'Castle calendar loads from the HighFive castle table.';
  }
  const owner = nextSiege.value.ownerClan ? `held by ${nextSiege.value.ownerClan}` : 'Unclaimed';
  return `${nextSiege.value.name} · ${owner}`;
});
</script>

<style scoped>
.status-band {
  margin-top: -48px;
  position: relative;
  z-index: 2;
  padding-bottom: 20px;
}

.status-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}

.status-card {
  background: white;
  border-radius: 24px;
  padding: 26px;
  box-shadow: var(--shadow);
  border: 1px solid var(--line);
}

.status-card.live {
  background: linear-gradient(180deg, #1a3d28, #0f2418);
  color: #dceee1;
}

.status-card.live h2,
.status-card.live .eyebrow {
  color: white;
}

.status-card.live .eyebrow {
  color: #8fd4a3;
}

h2 {
  font-size: 2.4rem;
  margin: 8px 0 6px;
}

.chip {
  display: inline-flex;
  margin-top: 14px;
  border-radius: 999px;
  padding: 6px 10px;
  font-size: 0.78rem;
}

.chip.ok {
  background: rgba(60, 179, 113, 0.2);
  color: #9ee6b4;
}

.chip.off {
  background: rgba(255, 255, 255, 0.08);
  color: #f3c77a;
}

@media (max-width: 860px) {
  .status-grid {
    grid-template-columns: 1fr;
  }
}
</style>
