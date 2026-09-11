<template>
  <section id="rankings" class="section">
    <div class="wrap">
      <div class="section-head">
        <div>
          <p class="eyebrow">Live board</p>
          <h2>Rankings</h2>
        </div>
      </div>

      <div class="boards">
        <article class="card board">
          <header>
            <h3>Top PvP</h3>
            <span>PvP Count</span>
          </header>
          <ol>
            <li v-for="(player, index) in pvp" :key="player.charId || index">
              <b>{{ index + 1 }}</b>
              <div>
                <strong>{{ player.char_name }}</strong>
                <small>{{ player.clan_name || 'No clan' }} · {{ player.class_name || 'Unknown' }} · Lv {{ player.level }}</small>
              </div>
              <em>{{ player.pvpkills }}</em>
            </li>
          </ol>
        </article>

        <article class="card board pk">
          <header>
            <h3>Top PK</h3>
            <span>PK Count</span>
          </header>
          <ol>
            <li v-for="(player, index) in pk" :key="player.charId || index">
              <b>{{ index + 1 }}</b>
              <div>
                <strong>{{ player.char_name }}</strong>
                <small>{{ player.clan_name || 'No clan' }} · {{ player.class_name || 'Unknown' }} · Lv {{ player.level }}</small>
              </div>
              <em>{{ player.pkkills }}</em>
            </li>
          </ol>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { inject } from 'vue';

const pvp = inject('pvp');
const pk = inject('pk');
</script>

<style scoped>
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
  max-width: 360px;
  color: var(--muted);
}

.boards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.board {
  padding: 8px 8px 16px;
  overflow: hidden;
}

header {
  display: flex;
  justify-content: space-between;
  padding: 18px 18px 10px;
}

h3 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.7rem;
}

header span {
  color: var(--muted);
  font-size: 0.8rem;
}

ol {
  list-style: none;
}

li {
  display: grid;
  grid-template-columns: 36px 1fr auto;
  gap: 12px;
  align-items: center;
  padding: 10px 16px;
  border-top: 1px solid var(--line);
}

li b {
  color: var(--magenta);
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.2rem;
}

li small {
  display: block;
  color: var(--muted);
  font-size: 0.78rem;
}

li em {
  font-style: normal;
  font-weight: 700;
}

.pk li b {
  color: var(--navy);
}

@media (max-width: 860px) {
  .boards,
  .section-head {
    grid-template-columns: 1fr;
    display: grid;
  }
}
</style>
