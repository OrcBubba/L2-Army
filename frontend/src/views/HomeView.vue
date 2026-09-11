<template>
  <main>
    <HeroSection :stats="stats" />
    <StatusCards :stats="stats" />
    <RankingsSection />
    <CastlesSection />
    <FeaturesSection />
    <RaidBossSection />
  </main>
</template>

<script setup>
import HeroSection from '../components/HeroSection.vue';
import StatusCards from '../components/StatusCards.vue';
import RankingsSection from '../components/RankingsSection.vue';
import CastlesSection from '../components/CastlesSection.vue';
import FeaturesSection from '../components/FeaturesSection.vue';
import RaidBossSection from '../components/RaidBossSection.vue';

defineProps({
  stats: { type: Object, default: null },
});
</script>
