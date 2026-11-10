import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('form', {
    submitted: false,
});

Alpine.start();