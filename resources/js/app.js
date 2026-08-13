import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// Helper to refresh icons on dynamic Alpine content change
window.refreshLucideIcons = () => {
    setTimeout(() => createIcons({ icons }), 50);
};
