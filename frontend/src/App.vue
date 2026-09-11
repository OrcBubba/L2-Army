<template>
  <div class="app-shell">
    <SiteHeader :stats="stats" />
    <p v-if="usingMock" class="mock-banner">
      Showing tutorial mock data — start MySQL and point backend/.env at your HighFive game database to load live rankings.
    </p>
    <router-view :stats="stats" @refresh="loadAll" />
    <SiteFooter :stats="stats" />
  </div>
</template>

<script setup>
import { onMounted, provide, ref } from 'vue';
import SiteHeader from './components/SiteHeader.vue';
import SiteFooter from './components/SiteFooter.vue';
import { fetchCastles, fetchHealth, fetchPk, fetchPvp, fetchRaidBosses, fetchStats } from './api';

const stats = ref(null);
const pvp = ref([]);
const pk = ref([]);
const castles = ref([]);
const raidBosses = ref([]);
const usingMock = ref(false);

provide('pvp', pvp);
provide('pk', pk);
provide('castles', castles);
provide('raidBosses', raidBosses);

async function loadAll() {
  try {
    const [healthData, statsData, pvpData, pkData, castleData, raidData] = await Promise.all([
      fetchHealth().catch(() => ({ usingMockData: true })),
      fetchStats(),
      fetchPvp(),
      fetchPk(),
      fetchCastles(),
      fetchRaidBosses().catch(() => ({ bosses: [], usingMockData: true })),
    ]);

    stats.value = statsData;
    pvp.value = pvpData.players || [];
    pk.value = pkData.players || [];
    castles.value = castleData.castles || [];
    raidBosses.value = raidData.bosses || [];
    usingMock.value = Boolean(
      healthData.usingMockData || statsData.usingMockData || pvpData.usingMockData || raidData.usingMockData
    );
  } catch (error) {
    console.error('API unavailable', error);
    usingMock.value = true;
  }
}

onMounted(loadAll);
</script>
