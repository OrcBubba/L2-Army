<template>
  <section id="raid-bosses" class="section raid-section">
    <div class="wrap">
      <div class="section-head">
        <div>
          <p class="eyebrow">World Bosses</p>
          <h2>Raid Boss Status</h2>
        </div>
        <p>
          <span class="legend"><i class="alive"></i> Alive</span>
          <span class="legend"><i class="dead"></i> Dead</span>
        </p>
      </div>

      <div class="card pack">
        <h3>Epic Bosses</h3>
        <ul>
          <li v-for="boss in epics" :key="boss.bossId">
            <i :class="boss.alive ? 'alive' : 'dead'" :title="boss.alive ? 'Alive' : 'Dead'"></i>
            <span>{{ boss.name }}</span>
            <em v-if="boss.level && boss.level < 999">Lv {{ boss.level }}</em>
          </li>
        </ul>
      </div>

      <div class="card pack raids">
        <h3>Raid Bosses</h3>
        <ul>
          <li v-for="boss in raids" :key="boss.bossId">
            <i :class="boss.alive ? 'alive' : 'dead'" :title="boss.alive ? 'Alive' : 'Dead'"></i>
            <span>{{ boss.name }}</span>
            <em v-if="boss.level && boss.level < 999">Lv {{ boss.level }}</em>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, inject } from 'vue';

const bosses = inject('raidBosses');

const epics = computed(() => (bosses?.value || []).filter((boss) => boss.type === 'epic'));
const raids = computed(() => (bosses?.value || []).filter((boss) => boss.type !== 'epic'));
</script>

<style scoped>
.raid-section {
  padding-bottom: 72px;
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

h3 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.45rem;
  margin-bottom: 14px;
  color: var(--navy);
}

.legend {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-left: 16px;
  color: var(--muted);
  font-size: 0.9rem;
}

.legend i,
li i {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  flex-shrink: 0;
}

.alive {
  background: #2f9e4f;
  box-shadow: 0 0 0 4px rgba(47, 158, 79, 0.18);
}

.dead {
  background: #c62828;
  box-shadow: 0 0 0 4px rgba(198, 40, 40, 0.16);
}

.pack {
  padding: 22px 24px 10px;
  margin-bottom: 16px;
}

.pack.raids ul {
  max-height: 360px;
  overflow: auto;
}

ul {
  list-style: none;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 4px 18px;
  padding-bottom: 12px;
}

li {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  border-bottom: 1px solid var(--line);
  font-size: 0.92rem;
}

li span {
  flex: 1;
}

li em {
  font-style: normal;
  color: var(--muted);
  font-size: 0.78rem;
  white-space: nowrap;
}

@media (max-width: 860px) {
  ul {
    grid-template-columns: 1fr 1fr;
  }

  .section-head {
    flex-direction: column;
    align-items: flex-start;
  }
}

@media (max-width: 540px) {
  ul {
    grid-template-columns: 1fr;
  }
}
</style>
