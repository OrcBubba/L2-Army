<template>
  <main class="section register-page">
    <div class="wrap reg-layout">

      <div class="info-panel card">
        <div class="info-copy">
          <p class="eyebrow">New adventurer</p>
          <h1>Create Your Account</h1>
          <p class="lead">
            Your L2 Army account gives you access to the game, the account panel, and your character history.
          </p>
          <div class="server-info">
            <span>XP x{{ stats?.rates?.xp ?? 1 }}</span>
            <span>SP x{{ stats?.rates?.sp ?? 1 }}</span>
            <span>Adena x{{ stats?.rates?.adena ?? 1 }}</span>
            <span>Drop x{{ stats?.rates?.drop ?? 1 }}</span>
          </div>
        </div>

        <ul class="perks">
          <li>
            <span class="perk-icon">⚔️</span>
            <div>
              <strong>In-Game Login</strong>
              <p>Log in to Lineage 2 with your new credentials immediately.</p>
            </div>
          </li>
          <li>
            <span class="perk-icon">🏰</span>
            <div>
              <strong>Character Panel</strong>
              <p>Track your characters, PvP stats, and online time.</p>
            </div>
          </li>
          <li>
            <span class="perk-icon">🛡️</span>
            <div>
              <strong>Account Security</strong>
              <p>Change your password or email at any time from the account panel.</p>
            </div>
          </li>
        </ul>
      </div>

      <div class="acm-panel card">
        <div class="acm-header">
          <p class="eyebrow">Account Manager</p>
          <h2>Register &amp; Manage Your Account</h2>
        </div>

        <div class="acm-frame-wrap">
          <iframe
            :src="acmEmbedUrl"
            title="L2 Army Account Manager"
            class="acm-frame"
            allow="forms"
            scrolling="no"
          ></iframe>
        </div>
      </div>

    </div>
  </main>
</template>

<script setup>
import { ACM_URL } from '../config';

const acmEmbedUrl = `${ACM_URL}/en/login?embed=1`;

defineProps({
  stats: { type: Object, default: null },
});
</script>

<style scoped>
.register-page {
  padding-top: 28px;
  padding-bottom: 48px;
}

.reg-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  padding: 0 0 24px;
}

.info-panel {
  display: grid;
  grid-template-columns: minmax(240px, 0.9fr) 1.6fr;
  gap: 28px 36px;
  padding: 28px 32px;
  align-items: center;
}

h1 {
  font-size: 2.2rem;
  margin: 4px 0 10px;
  color: var(--navy);
}

h2 {
  font-size: 1.5rem;
  margin: 4px 0 0;
  color: var(--navy);
}

.lead {
  color: var(--muted);
  line-height: 1.6;
  margin-bottom: 16px;
  max-width: 38ch;
}

.perks {
  list-style: none;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin: 0;
}

.perks li {
  display: flex;
  gap: 12px;
  align-items: flex-start;
}

.perk-icon {
  font-size: 1.25rem;
  flex-shrink: 0;
  width: 38px;
  height: 38px;
  display: grid;
  place-items: center;
  background: var(--lavender);
  border-radius: 12px;
}

.perks strong {
  display: block;
  margin-bottom: 2px;
}

.perks p {
  color: var(--muted);
  font-size: 0.86rem;
  line-height: 1.45;
  margin: 0;
}

.server-info {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.server-info span {
  background: var(--lavender);
  border: 1px solid var(--line);
  border-radius: 999px;
  padding: 6px 12px;
  font-size: 0.82rem;
}

.acm-panel {
  padding: 20px 22px 22px;
  overflow: hidden;
}

.acm-header {
  margin-bottom: 14px;
}

.acm-frame-wrap {
  border: 1px solid var(--line);
  border-radius: 16px;
  overflow: hidden;
  background: #1c4a2e;
}

.acm-frame {
  width: 100%;
  height: 640px;
  border: 0;
  display: block;
  overflow: hidden;
}

@media (max-width: 980px) {
  .info-panel {
    grid-template-columns: 1fr;
  }

  .lead {
    max-width: none;
  }

  .perks {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 700px) {
  .acm-frame {
    height: 720px;
  }
}
</style>
