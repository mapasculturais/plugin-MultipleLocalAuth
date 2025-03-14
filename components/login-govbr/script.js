app.component('login-govbr', {
    template: $TEMPLATES['login-govbr'],

    setup() {
    },

    data() {
    },

    props: {
        config: {
            type: String,
            required: true
        }
    },

    mounted() {
    },

    computed: {
        configs() {
            return JSON.parse(this.config);
        },
    },

    methods: {
    },
});
