const fallbackAcm = import.meta.env.PROD
  ? 'https://l2website-acm.onrender.com'
  : 'http://localhost/acm';

export const ACM_URL = String(import.meta.env.VITE_ACM_URL || fallbackAcm).replace(/\/$/, '');
export const ACM_LOGIN_URL = `${ACM_URL}/en/login`;
