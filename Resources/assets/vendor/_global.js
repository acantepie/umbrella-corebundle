// jquery
import jQuery from 'jquery'
window.jQuery = jQuery;
window.$ = jQuery;

// mustache
import mustache from 'mustache';
window.mustache = mustache;
mustache.tags = [ '[[', ']]' ];