app.component('link-govbr-modal', {
    template: $TEMPLATES['link-govbr-modal'],

    props: {
        config: {
            type: String,
            required: true
        },
    },

    computed: {
        configs() {
            return JSON.parse(this.config);
        },
    },

    methods: {
        disableModal(event, redirect = true) {
            let url = Utils.createUrl('site/desabilitar-modal', '');
            let api = new API();
            let data = { 
                agentId: $MAPAS.user.profile.id,
            };

            const redirectUrl = event.currentTarget ? event.currentTarget.getAttribute('href') : null;
            api.POST(url, data).then(res => res.json()).then(data => {
                if(redirect) {
                    window.location.href = redirectUrl;
                }
            })
        }
    },

    mounted() {
        this.$nextTick(() => {
            this.$refs.linkGovbrModal.open();
        });
    }
});
