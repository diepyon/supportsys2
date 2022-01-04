<template>
    <div style="padding-top:16px;">
        <v-form ref="test_form" v-model="valid" lazy-validation>
            <v-autocomplete v-model="value.type" :items="items" dense filled label="機種名" required
                :rules="[rules.required]" no-data-text="該当なし"></v-autocomplete>
            <v-text-field v-model="value.dealer" label="販売店"></v-text-field>
            <v-row justify="start">
                <v-col cols="5">
                    <v-text-field v-model="value.questioner" label="問い合わせ者"></v-text-field>
                </v-col>
                <v-col cols="5">
                    <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber" :counter="13"
                        label="TEL(「 - 」有無どちらでも可)" :rules="[rules.tel]">
                    </v-text-field>
                </v-col>
            </v-row>

            <v-text-field v-model="value.customer" label="ユーザー"></v-text-field>

            <v-container fluid>
                <v-radio-group v-model="value.kinds" row>
                    <v-radio label="設定" value="設定"></v-radio>
                    <v-radio label="障害" value="障害"></v-radio>
                    <v-radio label="トラブル" value="トラブル"></v-radio>
                    <v-radio label="故障" value="故障"></v-radio>
                    <v-radio label="購入前" value="購入前"></v-radio>
                    <v-radio label="クレーム" value="クレーム"></v-radio>
                </v-radio-group>
            </v-container>

            <v-textarea v-model="value.question" label="問い合わせ内容" required :rules="[rules.required]">
            </v-textarea>
            <v-textarea v-model="value.answer" label="回答内容" required :rules="[rules.required]"></v-textarea>

            <v-container fluid>
                <v-radio-group v-model="value.remote" row>
                    <template v-slot:label>
                        <div>リモートメンテナンス</div>
                    </template>
                    <v-radio label="なし" value="なし"></v-radio>
                    <v-radio label="オリジナル" value="オリジナル"></v-radio>
                    <v-radio label="TeamViewer" value="TeamViewer"></v-radio>
                    <v-col cols="5">
                        <v-text-field v-if="value.remote=='なし'" v-model="value.authorizer" label="承認者" disabled>
                        </v-text-field>
                        <v-text-field v-if="value.remote=='オリジナル'" v-model="value.authorizer" label="承認者">
                        </v-text-field>
                        <v-text-field v-if="value.remote=='TeamViewer'" v-model="value.authorizer" label="承認者" required
                            :rules="[rules.required]">
                        </v-text-field>
                    </v-col>
                </v-radio-group>

                <v-radio-group v-model="value.satisfaction" row>
                    <template v-slot:label>
                        <div>満足度</div>
                    </template>
                    <v-radio label="満足" value="満足" tabindex="1100"></v-radio>
                    <v-radio label="普通" value="普通"></v-radio>
                    <v-radio label="不満" value="不満"></v-radio>
                </v-radio-group>
            </v-container>
        </v-form>
    </div>
</template>
<script>
    export default {
        name: 'RecordForm',
        props: ["id"],
        data() {
            return {
                //もしIDが指定されていないならvalueは下記（editで指定されてる版も別途指定が必要）

                value: {
                    phoneNumber: "",
                    kinds: "トラブル",
                    remote: 'なし',
                    satisfaction: "満足",
                },
                valid: null,
                isEditing: false,
                model: null,
                items: [
                    'TDC200',
                    'TD480',
                    'UTM100',
                    'UTM200',
                    'EEW',
                ],
                rules: {
                    tel: value => (/^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/.test(value.replace(
                        /[━.*‐.*―.*－.*\-.*ー.*\-]/gi,
                        '')) || value == '') || '電話番号の形式が正しくありません',
                    required: v => !!v || '必須',

                },
            }
        },
        mounted() {
            if (this.id) {
                axios.get('/api/inquiries/' + this.id)
                    .then(response => {
                        this.inquiry = response.data.data
                        this.value = {
                            phoneNumber: this.inquiry.phoneNumber,
                            kinds: this.inquiry.kinds,
                            remote: this.inquiry.remote,
                            satisfaction: this.inquiry.satisfaction,
                        }
                    })
            }
        },
        methods: {
            post() { //投稿とボタンが押されたときに発動するメソッド
                let postData = {
                    //v-modelで取得した入力値の内容を変数に格納
                    answer: this.value.answer,
                    authorizer: this.value.authorizer,
                    customer: this.value.customer,
                    dealer: this.value.dealer,
                    kinds: this.value.kinds,
                    phoneNumber: this.value.phoneNumber,
                    question: this.value.question,
                    questioner: this.value.questioner,
                    remote: this.value.remote,
                    satisfaction: this.value.satisfaction,
                    type: this.value.type,
                    operator_id: 1, //とりあえず1
                }
                console.log(postData)

                if (this.$refs.test_form.validate()) {
                    axios.post('/api/inquiries/create', postData) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は入力された内容）
                        .then(response => {
                            //ここに成功した時に行いたい処理を記載
                            alert('投稿できました');
                            console.log(response); //成功してたらデータが返ってくる
                            this.$router.go({
                                path: this.$router.currentRoute.path,
                                force: true
                            })
                        })
                        .catch(function (error) {
                            // handle error(axiosの処理にエラーが発生した場合に処理させたいことを記述)
                            alert('あかんかったわ、コンソール見て');
                            console.log(error);
                        })

                } else {
                    alert('入力内容に不備があります。')
                }
            },

        }

    }

</script>
