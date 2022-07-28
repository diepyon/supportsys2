<template>
    <div>
        <v-app>
            <v-card>
                <v-card-title>
                    <h1 class="display-1">ログイン</h1>
                </v-card-title>
                <v-card-text>
                    <v-form ref="test_form">
                        <v-text-field v-model="postData.email" prepend-icon="mdi-at" label="メールアドレス"
                            :rules="[rules.required,rules.email]" />
                        <v-text-field v-model="postData.password" v-bind:type="showPassword ? 'text' : 'password'"
                            prepend-icon="mdi-lock" v-bind:append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            label="パスワード" required :rules="[rules.required,rules.password]"
                            @click:append="showPassword = !showPassword" />

                        <template>
                            <v-container class="px-0" fluid>
                                <v-checkbox v-model="remember" label="ログイン情報を記憶する">
                                </v-checkbox>
                            </v-container>
                        </template>

                        <v-btn class="info" @click="submit">ログイン</v-btn>
                    </v-form>
                    <p>新規登録にも誘導したい</p>
                    <v-btn v-if="loggedIn" @click="getUser">ユーザー情報取得</v-btn>
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
            loggedIn: false,
            showPassword: false,
            showPasswordConfirm: false,
            postData: {
                email: null,
                password: null,
            },
            remember: false,
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

            //ログイン情報に必要なクッキーを取得してthis.emailとthis.passswordに格納したい
            console.log(this.cookie)
            
        },

        components: {

        },
        methods: {
            submit() {
                if (this.$refs.test_form.validate()) {
                    axios.post('/api/login', this.postData)
                        .then(response => {
                            console.log(response)
                            this.loggedIn = true;

                            if (this.remember) {
                                //ログイン情報を保存する場合
                                document.cookie = "email=" + this.postData.email
                                document.cookie = "password=" + this.postData.password
                            }else{
                                //保存しない場合
                                //クッキーを削除
                                document.cookie = "email=; expires=0"
                                document.cookie = "password=; expires=0"
                            }





                            //this.$router.push('/')

                        })
                        .catch(error => {
                            console.log(error.response.data)

                            if (error.response.data == 'User Not Found.') {
                                this.centerSnackbar.text = "ユーザー名またはパスワードが違います。"
                                this.centerSnackbar.snackbar = true //スナックバーを表示
                            } else {
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
            getUser() {
                axios.get("/api/user")
                    .then(response => {
                        console.log(response)
                        let currentUser = response.data
                        console.log(currentUser)
                        this.user.name = currentUser.name
                        this.user.email = currentUser.email

                        this.user.name = this.user.name
                        this.user.email = this.user.email
                        this.user.id = response.data.id //カレントユーザーのIDを取得

                        //vuexでリアルタイムにユーザーの情報を更新（ヘッダーが変化を監視）
                        let userInfo = {
                            name: this.user.name,
                            email: this.user.name,
                            //アイコンの情報もヘッダーに表示したいなら必要かも
                        }
                        this.$store.commit("checkLogin", userInfo)



                    })
                    .catch(error => {
                        console.log(error)
                    })
            }

        }
    }

</script>
