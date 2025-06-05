app.component('resent-email-validation', {
    template: $TEMPLATES['resent-email-validation'],

    props: {
        entity: {
            type: Entity,
            required: true
        },
    },

    setup() {
        const messages = useMessages();
        const text = Utils.getTexts('resent-email-validation')
        return { text, messages }
    },

    data() {
        return {
            accountIsActive: this.entity.accountIsActive || false,
            emailSent: false,
            storageKey: `user-${this.entity.id}-emailValidationSent`
        }
    },

    mounted() {
        if (this.entity.accountIsActive == true) {
            localStorage.removeItem(this.storageKey);
        }

        if (localStorage.getItem(this.storageKey) === 'true') {
            this.setEmailSent();
        }
    },

    computed: {
        checkEmailWasSent() {
            return this.emailSent;
        }
    },

    methods: {
        async resendEmailValidation() {
            const api = new API();
            await api.POST($MAPAS.baseURL + 'autenticacao/resend-email-validation', {
                userId: this.entity.id
            }).then(response => response.json().then(dataReturn => {
                if (dataReturn.error) {
                    this.throwErrors(dataReturn.data);
                } else {
                    this.setEmailSent();
                }
            }));
        },

        setEmailSent() {
            this.emailSent = true;
            localStorage.setItem(this.storageKey, 'true');
            setTimeout(() => {
                localStorage.removeItem(this.storageKey);
                this.emailSent = false;
            }, 3000);
        },
    }
});