import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${d.toUTCString()}; path=/; SameSite=Lax`;
}
function getCookie(name) {
    const m = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : null;
}

const banner = document.getElementById('cookie-banner');
const btnAccept = document.getElementById('cookie-accept');
const btnDeny = document.getElementById('cookie-deny');

const consent = getCookie('cookie_consent');

if (!consent) banner.classList.remove('hidden');

function applyConsent(value) {
    setCookie('cookie_consent', value, 180);
    banner.classList.add('hidden');

    if (value === 'accepted') {
        window.dispatchEvent(new Event('cookies-accepted'));
    }
}

btnAccept?.addEventListener('click', () => applyConsent('accepted'));
btnDeny?.addEventListener('click', () => applyConsent('denied'));

if (consent === 'accepted') {
    window.dispatchEvent(new Event('cookies-accepted'));
}
