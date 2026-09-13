import { API_BASE } from './config';

async function getJson(path) {
  const response = await fetch(`${API_BASE}${path}`);
  if (!response.ok) {
    throw new Error(`Request failed: ${path}`);
  }
  return response.json();
}

export function fetchHealth() {
  return getJson('/api/health');
}

export function fetchStats() {
  return getJson('/api/stats');
}

export function fetchPvp() {
  return getJson('/api/rankings/pvp');
}

export function fetchPk() {
  return getJson('/api/rankings/pk');
}

export function fetchCastles() {
  return getJson('/api/castles');
}

export function fetchRaidBosses() {
  return getJson('/api/raid-bosses');
}
