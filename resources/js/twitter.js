Statamic.booting(Statamic => {
    Statamic.component('twitter-related-item', require('./components/TwitterRelationshipItem.vue'));
    Statamic.component('twitter-fieldtype-index', require('./components/TwitterFieldtypeIndex.vue'));
});
