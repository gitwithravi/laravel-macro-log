import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add user's timezone to all requests
try {
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    if (timezone) {
        window.axios.defaults.headers.common['X-User-Timezone'] = timezone;
    }
} catch (e) {
    // Silently fail if timezone detection isn't supported
}
