app.component('login-govbr', {
    template: $TEMPLATES['login-govbr'],

    props: {
        config: {
            type: String,
            required: true
        }
    },

    computed: {
        configs() {
            return JSON.parse(this.config);
        },
    },
});
