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

    methods: {
        setCookie(params) {
            Utils.cookies.set('errorRedirectLocation', JSON.stringify(params), {path: '/'});
        },
        
        govBrClick(event) {
            const params = JSON.parse(event.currentTarget.getAttribute('data-params'));
            
            this.setCookie(params);

            window.location.href = event.currentTarget.getAttribute('href');
        }
    }
});
