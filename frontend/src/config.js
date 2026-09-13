const fallbackAcm = import.meta.env.PROD
  ? 'https://l2website-acm.onrender.com'
  : 'http://localhost/acm';

const fallbackApi = import.meta.env.PROD
  ? 'https://l2website-api.onrender.com'
  : '';

export const ACM_URL = String(import.meta.env.VITE_ACM_URL || fallbackAcm).replace(/\/$/, '');
export const ACM_LOGIN_URL = `${ACM_URL}/en/login`;
export const API_BASE = String(
  import.meta.env.VITE_API_BASE || import.meta.env.VITE_API_URL || fallbackApi
).replace(/\/$/, '');
