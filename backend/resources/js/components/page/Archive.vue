<template>
    <div>
        <h1>受付記録</h1>
        <v-row justify="start">
            <v-col cols="auto">
                <v-dialog max-width="90%" scrollable v-model="dialog.post">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" v-bind="attrs" v-on="on" tabindex="1">
                            <v-icon>mdi-lead-pencil</v-icon>記録入力
                        </v-btn>
                    </template>
                    <template v-slot:default="postDialog">
                        <v-card class="dialogBg">
                            <v-toolbar color="primary" dark dense>記録入力
                                <v-spacer></v-spacer>
                                <v-btn icon @click="postDialog.value = false">
                                    <v-icon>mdi-window-close</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-card-text>
                                <p class="text-right" style="padding-top: 16px">
                                    <v-icon>mdi-open-in-new</v-icon>
                                    <a href="/recordpost">新しいタブで入力画面を開く</a>
                                </p>
                                <RecordForm v-if="resetFlag" ref="RecordForm" @parentMethod="judge"></RecordForm>
                            </v-card-text>
                            <v-card-actions class="end">
                                <v-btn text @click="submit" color="primary">登録</v-btn>
                                <v-btn text @click="postDialog.value = false" color="primary">閉じる</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </v-col>
        </v-row>

        <v-dialog max-width="90%" scrollable persistent v-model="dialog.posted">
            <template v-slot:default="postedDialog">
                <v-card class="dialogBg">
                    <v-toolbar color="primary" dark dense>{{actionTitleName}}
                        <v-spacer></v-spacer>
                        <v-btn icon @click="closeConfirm">
                            <v-icon>mdi-window-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <div style="padding-top: 16px">
                            <v-form ref="test_form" v-model="valid" lazy-validation>
                                <v-autocomplete 
                                    v-model="value.type" 
                                    :items="types" 
                                    dense filled label="機種名" 
                                    required
                                    :rules="[rules.required]" 
                                    no-data-text="該当なし"
                                    placeholder="検索できます"
                                    prepend-icon="mdi-magnify"
                                ></v-autocomplete>
                                <v-text-field v-model="value.serial" label="シリアル番号" :rules="[rules.alphaNum]">
                                </v-text-field>

                                <v-text-field v-model="value.dealer" label="販売店"></v-text-field>
                                <v-row justify="start">
                                    <v-col cols="5">
                                        <v-text-field v-model="value.questioner" label="問い合わせ者"></v-text-field>
                                    </v-col>
                                    <v-col cols="5">
                                        <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber"
                                            :counter="13" label="TEL(「 - 」有無どちらでも可)" :rules="[rules.tel]">
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
                                <v-textarea v-model="value.answer" label="回答内容" required :rules="[rules.required]">
                                </v-textarea>
                                <v-container fluid>
                                    <v-radio-group v-model="value.remote" row>
                                        <template v-slot:label>
                                            <div>リモートメンテナンス</div>
                                        </template>
                                        <v-radio label="なし" value="なし"></v-radio>
                                        <v-radio label="オリジナル" value="オリジナル"></v-radio>
                                        <v-radio label="TeamViewer" value="TeamViewer"></v-radio>
                                        <v-col cols="5">
                                            <v-text-field v-if="value.remote == 'なし'" v-model="value.authorizer"
                                                label="承認者" disabled>
                                            </v-text-field>
                                            <v-text-field v-if="value.remote == 'オリジナル'" v-model="value.authorizer"
                                                label="承認者">
                                            </v-text-field>
                                            <v-text-field v-if="value.remote == 'TeamViewer'" v-model="value.authorizer"
                                                label="承認者" required :rules="[rules.required]">
                                            </v-text-field>
                                        </v-col>
                                    </v-radio-group>

                                    <v-radio-group v-model="value.satisfaction" row>
                                        <template v-slot:label>
                                            <div>満足度</div>
                                        </template>
                                        <v-radio label="満足" value="満足"></v-radio>
                                        <v-radio label="普通" value="普通"></v-radio>
                                        <v-radio label="不満" value="不満"></v-radio>
                                    </v-radio-group>
                                    <v-text-field v-model="value.anchor" label="引き継ぎ元ID（調整中）" :rules="[rules.alphaNum]"
                                        hint="引き継ぎ元のIDを1つだけ入力" tabindex="-1"></v-text-field>
                                </v-container>
                            </v-form>
                        </div>
                    </v-card-text>
                    <v-card-actions class="end">
                        <v-btn text color="primary" v-if="submitButtonName=='更新'" @click="update(inquiryID)">
                            {{submitButtonName}}</v-btn>
                        <v-btn text color="primary" v-if="submitButtonName=='引継'" @click="inhert(inquiryID)">
                            {{submitButtonName}}</v-btn>
                        <v-btn text @click="closeConfirm" color="primary">閉じる</v-btn>
                        <v-btn v-show="false" @click="postedDialog.value = false;"></v-btn>
                    </v-card-actions>
                </v-card>
            </template>
        </v-dialog>

        <v-card v-for="(inquiry,index) in inquiries" :key="inquiry.id" :id="inquiry.id" class="inquiry" flat>
            <span class="inquiryBox">
                <v-toolbar color="primary" dark dense>
                    <span class="overflow">{{ inquiry.created_at }}</span>
                    <span class="overflow">機種名: {{ inquiry.type }}</span>
                    <span class="overflow">シリアル:{{ inquiry.serial }}</span>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="dialog.posted=true;inhertDialog(inquiry,index,'inhert');id=inquiry.id">
                        <v-icon>mdi-subdirectory-arrow-left</v-icon>
                    </v-btn>
                    <v-btn icon @click="dialog.posted = true;updateDialog(inquiry,index);id=inquiry.id">
                        <v-icon>mdi-square-edit-outline</v-icon>
                    </v-btn>

                    <v-btn icon>
                        <v-icon>mdi-flag-variant-outline</v-icon>
                    </v-btn>

                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item v-for="(cardMenu, method, index) in cardMenus" :key="index" class="hover">
                                <v-list-item-title @click="triggerClick(cardMenu.method, inquiry.id)">
                                    {{ cardMenu.title }}
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-toolbar>

                <v-card-text>
                    <v-container class="lighten-5">
                        <v-row no-gutters>
                            <v-col cols="2" xs="0">
                                <v-layout justify-center>
                                    <v-avatar color="primary" size="56">
                                        <img src="/storage/img/customer.jpeg" alt="お客様" style="width: auto" />
                                    </v-avatar>
                                </v-layout>

                                <div class="people">
                                    <v-layout v-if="inquiry.questioner" justify-center>
                                        {{inquiry.questioner}}</v-layout>
                                    <v-layout v-else justify-center>不明</v-layout>
                                    <span v-if="inquiry.phoneNumber" style="text-align: center">
                                        <v-icon size="4">mdi-phone</v-icon>
                                        <span>{{ inquiry.phoneNumber }}</span>
                                        <v-btn icon style="height: " @click="copyToClipboard(inquiry.phoneNumber)">
                                            <v-icon size="4">mdi-content-copy</v-icon>
                                        </v-btn>
                                    </span>
                                </div>
                            </v-col>
                            <v-col cols="6">
                                <span class="bigFont">{{ inquiry.question }}</span>
                            </v-col>
                            <v-col cols="4">
                                <span class="">
                                    <div>
                                        <span class="subject">販売店:</span>
                                        <span v-if="inquiry.dealer">{{ inquiry.dealer }}</span>
                                        <span v-else>不明</span>
                                    </div>
                                    <div>
                                        <span class="subject">ユーザー:</span>
                                        <span v-if="inquiry.customer">{{ inquiry.customer }}</span>
                                        <span v-else>不明</span>
                                    </div>
                                    <div>
                                        <span class="subject">満足度:</span>{{ inquiry.satisfaction }}
                                    </div>
                                </span>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-divider class="mx-4"></v-divider>
                <v-card-text>
                    <v-container class="lighten-5">
                        <v-row no-gutters>
                            <v-col cols="2">
                                <v-layout justify-center>
                                    <v-avatar color="primary" size="56">オペ</v-avatar>
                                </v-layout>
                                <v-layout justify-center>
                                    {{ inquiry.operator_id }}
                                </v-layout>
                            </v-col>
                            <v-col cols="6">
                                <span class="bigFont">{{ inquiry.answer }}</span>
                            </v-col>
                            <v-col cols="4">
                                <span class="">
                                    <div class="id">ID: {{ inquiry.previewAnchor }}</div>
                                </span>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </span>
        </v-card>

        <div class="text-center ma-2">
            <v-snackbar v-model="centerSnackbar.snackbar" :timeout="centerSnackbar.timeout">
                {{ centerSnackbar.text }}
                <template v-slot:action="{ attrs }">
                    <v-btn v-if="centerSnackbar.deleteButton" color="pink" text v-bind="attrs" @click="del(deleteId)">
                        削除
                    </v-btn>
                    <v-btn v-if="centerSnackbar.rebornButton" color="pink" text v-bind="attrs"
                        @click="reborn(deleteId)">
                        元に戻す
                    </v-btn>
                    <v-btn v-if="centerSnackbar.discard" color="pink" text v-bind="attrs"
                        @click="dialog.posted = false;closeCenterSnackbar()">
                        破棄する
                    </v-btn>
                    <v-btn v-if="centerSnackbar.notDiscard" color="pink" text v-bind="attrs"
                        @click="closeCenterSnackbar()">
                        破棄しない
                    </v-btn>
                    <v-btn v-if="centerSnackbar.close" color="pink" text v-bind="attrs"
                        @click="closeCenterSnackbar(deleteId)">
                        閉じる
                    </v-btn>
                </template>
            </v-snackbar>
        </div>
    </div>
</template>
<script>
    import RecordForm from "../layout/RecordForm";

    export default {
        components: {
            RecordForm
        },
        data() {
            return {
                inquiryID: null,
                postedDialog: null,
                dialog: [],
                inquiries: null,
                cardMenus: [{
                        title: "削除",
                        method: "delete",
                    },
                    {
                        title: "ダミー",
                        method: "delete",
                    },
                ],

                centerSnackbar: {
                    snackbar: false,
                    text: "",
                    close: true,
                    deleteButton: false,
                    rebornButton: false,
                    discard: false,
                    notDiscard: false,
                    timeout: 20000,
                },
                centerSnackbarDefault: {
                    snackbar: false,
                    text: "",
                    close: true,
                    deleteButton: false,
                    rebornButton: false,
                    discard: false,
                    notDiscard: false,
                    timeout: 20000,
                },

                index: null,
                deleteId: null,
                anchor: [],
                result: "",
                resetFlag: true,

                value: {
                    answer: "",
                    anchor: "",
                    authorizer: "",
                    customer: "",
                    dealer: "",
                    id: "",
                    kinds: "トラブル",
                    phoneNumber: "",
                    question: "",
                    questioner: "",
                    remote: "なし",
                    satisfaction: "満足",
                    serial: "",
                    operator_id: "",
                    type: "",
                },
                actionTitleName: null,
                action: null,
                submitButtonName: null,
                oldValue: null,
                newValue: null,

                valid: null,
                model: null,
                types: [],
                rules: {
                    tel: (value) =>
                        /^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/.test(
                            value.replace(/[━.*‐.*―.*－.*\-.*ー.*\-]/gi, "")
                        ) ||
                        value == "" ||
                        "電話番号の形式が正しくありません",
                    required: (v) => !!v || "必須",
                    alphaNum: (value) => {
                        const pattern = /^[0-9a-zA-Z]*$/;
                        return pattern.test(value) || "半角英数字のみで入力してください。";
                    },
                },
            };
        },
        watch: {


        },        
        mounted() {
            this.showArchive()
            this.getTypes()
        },
        methods: {
            postData(id) {
                let postData = {
                    answer: this.value.answer,
                    anchor: this.value.anchor,
                    authorizer: this.value.authorizer,
                    customer: this.value.customer,
                    dealer: this.value.dealer,
                    id: id,
                    kinds: this.value.kinds,
                    phoneNumber: this.value.phoneNumber,
                    question: this.value.question,
                    questioner: this.value.questioner,
                    remote: this.value.remote,
                    serial: this.value.serial, //コントローラー側で登録処理がまだ書かれてない
                    satisfaction: this.value.satisfaction,
                    type: this.value.type,
                    operator_id: 1, //とりあえず1
                };
                return postData;
            },
            update(id) {
                let postData = this.postData(id);

                if (this.$refs.test_form.validate()) {
                    axios.post('/api/inquiries/edit', postData) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は入力された内容）
                        .then(response => {
                            alert('更新しました。');
                            console.log(response.statusText)
                            this.showArchive()
                            this.dialog.posted = false
                        })
                        .catch(function (error) {
                            alert('あかんかったわ、コンソール見て');
                            console.log(error);
                        })
                } else {
                    alert('入力内容に不備があります。')
                }
            },
            inhert(id) {
                let postData = this.postData(id);
                if (this.$refs.test_form.validate()) {
                    axios.post("/api/inquiries/create", postData)
                        .then((response) => {
                            alert("引き継ぎました。");
                            console.log(response.statusText);
                            this.showArchive()
                            this.dialog.posted = false
                        })
                        .catch(function (error) {
                            alert("あかんかったわ、コンソール見て");
                            console.log(error);
                        });
                } else {
                    alert("入力内容に不備があります。");
                }
            },
            getInquiries: async function () {
                await axios.get("/api/inquiries/archive").then((response) => {
                    //サーバーの処理が終わるまで待て
                    this.inquiries = response.data.data.reverse();
                });

                return this.inquiries; //awaitの処理が終わったらリターン
            },
            showArchive: async function () {
                let result = await this.getInquiries(); //showArchive()の処理が完全に終わるまで待て

                //終わったら残りの処理を実行(ダイアログを確実に閉じておく)
                this.dialog = {
                    post: null,
                    posted: null,
                };
            },
            closeDialog() {
                this.dialog.post = false;
            },
            submit() {
                this.$refs.RecordForm.post()
            },
            judge(result) {
                //正常にpostできたらダイアログを閉じてアーカイブ再読み込み
                //this.result = result;
                this.closeDialog();
                this.showArchive();

                //フォームをリフレッシュ（再読み込み）
                this.resetFlag = false;
                this.$nextTick(() => (this.resetFlag = true));
            },
            copyToClipboard(text) {
                navigator.clipboard
                    .writeText(text)
                    .then(() => {
                        alert("copied");
                    })
                    .catch((e) => {
                        console.error(e);
                    });
            },
            triggerClick(method, id) {
                if (method === "delete") {
                    this.deleteConfirm(id);
                }
            },
            deleteConfirm(id) {
                this.snackbarDefaultStatus()
                var activeClass = document.getElementById(id);
                activeClass.classList.add("activeCard");
                this.centerSnackbar.rebornButton = false;
                this.centerSnackbar.discard = false;
                this.centerSnackbar.notDiscard = false;
                this.centerSnackbar.deleteButton = true;
                this.centerSnackbar.close = true;
                this.centerSnackbar.text = "削除しますか？";
                this.centerSnackbar.snackbar = true; //スナックバーを表示
                this.deleteId = id; //deleteやrebornメソッドで削除・復活させたいときに参照するID
            },
            closeConfirm() {
                this.oldValue = this.inquiries[this.index]
                if (this.action == 'inhert') {
                    this.oldValue.anchor = this.inquiries[this.index].inquiry_id
                } //inhertの場合引継ID初期値は引継元IDとして変化したかどうかを判断

                //更新前のデータをjson形式に変換（なおかつnullは""に置き換え
                this.oldValue = JSON.stringify(Object.fromEntries(Object.entries(this.oldValue).map(([k,
                    v
                ]) => [k,
                    v === null ? "" : v
                ])))

                this.newValue = JSON.stringify(this.value)

                if (this.oldValue == this.newValue) {
                    this.dialog.posted = false
                    this.action == null //actionを元に戻す
                    console.log('変更がなかったので警告なしで閉じます。')
                } else {
                    this.snackbarDefaultStatus()
                    this.centerSnackbar.text = "変更を破棄しますか？"
                    this.centerSnackbar.discard = true;
                    this.centerSnackbar.notDiscard = true;
                    this.centerSnackbar.deleteButton = false;
                    this.centerSnackbar.close = false; //「閉じる」だと紛らわしいので表示しない
                    this.centerSnackbar.snackbar = true; //スナックバーを表示
                }
            },
            updateDialog(inquiry, index) {
                this.actionTitleName = '記録編集'
                this.submitButtonName = '更新'
                this.inquiryID = inquiry.id
                this.index = index
                if (inquiry) {
                    this.value = Object.fromEntries(
                        Object.entries(inquiry).map(([k, v]) => [k, v === null ? "" : v])
                    ); //投稿済み記事を参照する処理する場合は変数を上書き
                }
                this.inquiry = this.value
            },
            inhertDialog(inquiry, index, action) {
                this.actionTitleName = '記録引継'
                this.action = action
                this.submitButtonName = '引継'
                this.inquiryID = inquiry.id
                this.index = index
                if (inquiry) {
                    this.value = Object.fromEntries(
                        Object.entries(inquiry).map(([k, v]) => [k, v === null ? "" : v])
                    ); //投稿済み記事を参照する処理する場合は変数を上書き
                }
                this.value.anchor = inquiry.inquiry_id //引継元IDを取得(しかしIDを入力したとして変更扱いになりダイアログを閉じるときに無編集でも警告が出る)
                this.inquiry = this.value
            },
            del(id) {
                let postdata = {
                    id: id,
                }; //消したい記事のidをオブジェクトidに格納
                axios
                    .post("/api/inquiries/delete", postdata)
                    .then((response) => {
                        //ここに成功した時に行いたい処理を記載
                        console.log(response); //成功してたらデータが返ってくる
                        //var activeClass = document.getElementById(id);
                        this.centerSnackbar.text = "削除しました。";
                        this.centerSnackbar.deleteButton = false; //削除ボタンを非表示
                        this.centerSnackbar.rebornButton = true; //元に戻すボタンを表示
                        this.centerSnackbar.snackbar = true; //スナックバーを表示
                        this.showArchive();
                    })
                    .catch(function (error) {
                        alert("削除できませんでした。");
                        console.log(error);
                    });
            },
            reborn(id) {
                let postdata = {
                    id: id,
                }; //復活させたい記事のidをオブジェクトidに格納
                axios
                    .post("/api/inquiries/reborn", postdata)
                    .then((response) => {
                        this.centerSnackbar.text = "復活させました。";
                        this.centerSnackbar.rebornButton = false; //元に戻すボタンを非表示
                        this.showArchive();
                    })
                    .catch(function (error) {
                        this.centerSnackbar.text = "復活させられませんでした。";
                        console.log(error);
                    });
            },
            closeCenterSnackbar(id) {
                this.centerSnackbar.snackbar = false;
                if (id) {
                    var activeClass = document.getElementById(id);
                    activeClass.classList.remove("activeCard");
                }
            },
            snackbarDefaultStatus() {
                this.centerSnackbar = this.centerSnackbarDefault
            },
            getTypes() {
                axios.get('/api/types/archive')
                    .then(response => {
                        const types = response.data.data                       
                        types.forEach((value) => {
                           this.types.push(value.name)
                        })
                        this.types.sort()
                        console.log(this.types)
                    })
            },
        },
    };

</script>
<style scoped>
    .inquiry {
        margin: 2em 0;
    }

    .bigFont {
        font-size: 1rem !important;
        white-space: break-spaces;
    }

    .people {
        font-size: 0.75em;
    }

    .overflow {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .id {
        bottom: 1em;
        right: 1em;
        position: absolute;
    }

    .subject {
        color: #009688;
    }

    .hover:hover {
        opacity: 0.7;
        background-color: rgba(49, 49, 49, 0.767);
        cursor: pointer;
        color: #fff !important;
    }

    .activeCard {
        opacity: 0.5;
        transition: 0.5s;
    }

    .deleting {
        opacity: 0.1;
        transition: 0.5s;
    }

    .delete {
        display: none;
    }

    .v-dialog>.v-card {
        background: #ffffffde !important;
    }

</style>
