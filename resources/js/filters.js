import Vue from 'vue';

Vue.mixin(
    {
        filters: {
            firstWord: function (value) {
                if (!value) return '';
                value = value.toString();
                return value.split(' ')[0];
            },
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        }
    }
);