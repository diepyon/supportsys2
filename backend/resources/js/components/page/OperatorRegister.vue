<template>
    <div>
        <v-app>
            <v-card>
                <v-card-title>
                    <h1 class="display-1">オペレーター登録</h1>
                </v-card-title>
                <v-card-text>
                    <v-form ref="test_form">
                        <v-text-field v-model="postData.email" prepend-icon="mdi-at" label="メールアドレス"
                            :rules="[rules.required,rules.email]" />
                        <v-text-field v-model="postData.password" v-bind:type="showPassword ? 'text' : 'password'"
                            prepend-icon="mdi-lock" v-bind:append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            label="パスワード" required :rules="[rules.required,rules.password]"
                            @click:append="showPassword = !showPassword" />

                        <v-text-field v-model="postData.passwordConfirm"
                            v-bind:type="showPasswordConfirm ? 'text' : 'password'" prepend-icon="mdi-lock"
                            v-bind:append-icon="showPasswordConfirm ? 'mdi-eye' : 'mdi-eye-off'" label="パスワード再入力"
                            required
                            :rules="[rules.required,rules.password,(postData.password === postData.passwordConfirm) || 'パスワードが一致しません。']"
                            @click:append="showPasswordConfirm = !showPasswordConfirm" />
                        <v-btn class="info" @click="submit">新規登録</v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-app>

        <div class="text-center ma-2">
            <v-snackbar v-model="centerSnackbar.snackbar" :timeout="centerSnackbar.timeout">
                {{ centerSnackbar.text }}
                <template v-slot:action="{ attrs }">
                    <v-btn v-if="centerSnackbar.close" color="pink" text v-bind="attrs"
                        @click="snackbarDefaultStatus()">
                        閉じる
                    </v-btn>
                </template>
            </v-snackbar>
        </div>


    </div>
</template>
<script>
    export default {
        name: 'OperatorRegister',

        data: () => ({
            showPassword: false,
            showPasswordConfirm: false,
            postData: {
                email: null,
                password: null,
                passwordConfirm: null,
            },
            rules: {
                required: v => !!v || "必須",
                email: v => {
                    const pattern =
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return pattern.test(v) || 'メールアドレス形式で入力してください。'
                },
                password: v => {
                    const pattern = /^[ -~]+$/
                    return (
                        pattern.test(v) ||
                        v == "" ||
                        "半角英数記号だけが使えます。"
                    );
                },
            },
            centerSnackbar: {
                snackbar: false,
                text: "",
                close: true,
                timeout: 20000,
            },
            centerSnackbarDefault: {
                snackbar: false,
                text: "",
                close: true,
                timeout: 20000,
            },
        }),

        mounted() {
            //passsword表示ボタンはタブキーでの移動から除外
            const elements = document.getElementsByClassName('v-icon--link');
            elements[0].tabIndex = -1;
            elements[1].tabIndex = -1;
        },

        components: {

        },
        methods: {
            submit() {
                if (this.$refs.test_form.validate()) {
                    axios.post('/api/operatorregister', this.postData)
                        .then(response => {
                            console.log(response.data)
                            this.centerSnackbar.text = this.postData.email + "を新規登録しました。"
                            this.centerSnackbar.snackbar = true //スナックバーを表示
                            this.$refs.test_form.reset();
                        })
                        .catch(error => {
                            if (error.response.data.errors.email[0] == 'The email has already been taken.') {
                                this.centerSnackbar.text = "このメールアドレスはすでに登録されています。"
                                this.centerSnackbar.snackbar = true //スナックバーを表示
                            } else {
                                console.log(error.response.data.errors.email[0])
                                this.centerSnackbar.text = "その他のエラー"
                                this.centerSnackbar.snackbar = true //スナックバーを表示
                            }
                        })
                } else {
                    console.log('ng')
                }
            },
            snackbarDefaultStatus() {
                this.centerSnackbar = this.centerSnackbarDefault
            },

        }
    }

</script>
