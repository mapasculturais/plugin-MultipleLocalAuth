app.component('login-govbr', {
    template: $TEMPLATES['login-govbr'],

    props: {
        config: {
            type: String,
            required: true
        },
        small: Boolean,
        large: Boolean,
    },

    computed: {
        configs() {
            return JSON.parse(this.config);
        },
    },
});
