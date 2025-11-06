import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'bootstrap/dist/css/bootstrap.min.css';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'dropify/dist/js/dropify.min.js';
import 'dropify/dist/css/dropify.min.css';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Inisialisasi Dropify
$(function () {
    $('.dropify').dropify();
    // $('.dropify2').dropify();
});
