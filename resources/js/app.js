import './bootstrap';
import Search from './live-search'
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

if (document.querySelector(".header-search-icon")) {
    new Search();
}
