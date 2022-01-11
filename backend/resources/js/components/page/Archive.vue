<template>
    <div>
        <h1>受付記録</h1>
        <v-row justify="start">
            <v-col cols="auto">
                <v-dialog max-width="90%" scrollable >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" v-bind="attrs" v-on="on" tabindex="1">
                            <v-icon>mdi-lead-pencil</v-icon>記録入力
                        </v-btn>
                    </template>
                    <template v-slot:default="dialog">
                        <v-card class="dialogBg">
                            <v-toolbar color="primary" dark>記録入力
                                <v-spacer></v-spacer>
                                <v-btn icon @click="dialog.value = false">
                                    <v-icon>mdi-window-close</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-card-text >
                                <p class="text-right" style="padding-top:16px;">
                                    <v-icon>mdi-open-in-new</v-icon> <a href="/recordpost">新しいタブで入力画面を開く</a>
                                </p>
                                <RecordForm ref="RecordForm"></RecordForm>
                            </v-card-text>
                            <v-card-actions class="end">
                                <v-btn text @click="submit" color="primary">登録</v-btn>
                                <v-btn text @click="dialog.value = false" color="primary">閉じる</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </v-col>

            <v-col cols="auto">
                <v-dialog transition="dialog-top-transition" max-width="600" scrollable>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" v-bind="attrs" v-on="on" tabindex="2">
                            <v-icon>mdi-magnify</v-icon>記録検索
                        </v-btn>
                    </template>
                    <template v-slot:default="dialog">
                        <v-card>
                            <v-toolbar color="primary" dark>Opening from the top</v-toolbar>
                            <v-card-text>
                                <div class="text-h2 pa-12">Hello world!</div>
                            </v-card-text>
                            <v-card-actions class="end">
                                <v-btn text @click="dialog.value = false">閉じる</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </v-col>
        </v-row>

        <v-card v-for="(inquiry,index) in inquiries" :key="inquiry.id" class="inquiry" flat :id="inquiry.id">
            <span class="inquiryBox">
                <v-toolbar color="primary" dark dense>
                    <span class="overflow">{{inquiry.created_at}}</span>
                    <span class="overflow">機種名: {{inquiry.type}}</span>
                    <span class="overflow">シリアル:{{inquiry.serial}}</span>
                    <v-spacer></v-spacer>

                    <v-dialog max-width="90%" scrollable >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-subdirectory-arrow-left</v-icon>
                            </v-btn>
                        </template>
                        <template v-slot:default="dialog">
                            <v-card>
                                <v-toolbar color="primary" dark>記録引き継ぎ
                                    <v-spacer></v-spacer>
                                    <v-btn icon @click="dialog.value = false">
                                        <v-icon>mdi-window-close</v-icon>
                                    </v-btn>
                                </v-toolbar>
                                <v-card-text>
                                    <RecordForm :inquiry="inquiry" :ref="RecordForm + index"></RecordForm>
                                </v-card-text>
                                <v-card-actions class="end">
                                    <v-btn text @click="inhert(index,inquiry.id)" color="primary">引継</v-btn>
                                    <v-btn text @click="dialog.value = false" color="primary">閉じる</v-btn>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-dialog>

                    <v-dialog max-width="90%" scrollable>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-square-edit-outline</v-icon>
                            </v-btn>
                        </template>
                        <template v-slot:default="dialog">
                            <v-card>
                                <v-toolbar color="primary" dark>記録編集
                                    <v-spacer></v-spacer>
                                    <v-btn icon @click="dialog.value = false">
                                        <v-icon>mdi-window-close</v-icon>
                                    </v-btn>
                                </v-toolbar>
                                <v-card-text>
                                    <RecordForm :inquiry="inquiry" :ref="'RecordForm' + index"></RecordForm>
                                </v-card-text>
                                <v-card-actions class="end">
                                    <v-btn text @click="edit(index,inquiry.id)" color="primary">更新</v-btn>
                                    <v-btn text @click="dialog.value = false" color="primary">閉じる</v-btn>
                                </v-card-actions>
                            </v-card>
                        </template>
                    </v-dialog>


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
                            <v-list-item v-for="(cardMenu, method,index) in cardMenus" :key="index" class="hover">
                                <v-list-item-title @click="triggerClick(cardMenu.method,inquiry.id)">
                                    {{cardMenu.title }}
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
                                        <img src="/storage/img/customer.jpeg" alt="お客様" style="width:auto;">
                                    </v-avatar>
                                </v-layout>

                                <div class="people">
                                    <v-layout v-if="inquiry.questioner" justify-center>{{inquiry.questioner}}</v-layout>
                                    <v-layout v-else justify-center>不明</v-layout>
                                    <span v-if="inquiry.phoneNumber" style="text-align: center;">
                                        <v-icon size="4">mdi-phone</v-icon>
                                        <span>{{inquiry.phoneNumber}}</span>
                                        <v-btn icon style="height:;" @click="copyToClipboard(inquiry.phoneNumber)">
                                            <v-icon size="4">mdi-content-copy</v-icon>
                                        </v-btn>
                                    </span>
                                </div>
                            </v-col>
                            <v-col cols="6">
                                <span class="bigFont">{{inquiry.question}}</span>
                            </v-col>
                            <v-col cols="4">
                                <span class="">
                                    <div><span class="subject">販売店:</span>
                                        <span v-if="inquiry.dealer">{{inquiry.dealer}}</span>
                                        <span v-else>不明</span>
                                    </div>
                                    <div><span class="subject">ユーザー:</span>
                                        <span v-if="inquiry.customer">{{inquiry.customer}}</span>
                                        <span v-else>不明</span>
                                    </div>
                                    <div><span class="subject">満足度:</span>{{inquiry.satisfaction}}</div>
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
                                    {{inquiry.operator_id}}
                                </v-layout>
                            </v-col>
                            <v-col cols="6">
                                <span class="bigFont">{{inquiry.answer}}</span>
                            </v-col>
                            <v-col cols="4">
                                <span class="">
                                    <div class="id">ID:{{inquiry.inquiry_id}}</div>
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
                    <v-btn color="pink" text v-bind="attrs" @click="closeCenterSnackbar(deleteId)">
                        閉じる
                    </v-btn>
                </template>
            </v-snackbar>
        </div>


    </div>
</template>
<script>
    import RecordForm from '../layout/RecordForm'

    export default {
        components: {
            RecordForm,
        },
        data() {
            return {
                dialog: false,
                inquiries: null,
                cardMenus: [{
                        title: '削除',
                        method: 'delete'
                    },
                    {
                        title: 'ダミー',
                        method: 'delete'
                    },
                ],

                centerSnackbar: {
                    snackbar: false,
                    text: "",
                    deleteButton: false,
                    rebornButton: false,
                    timeout: 20000,
                },
                deleteId: null,

            }
        },
        mounted() {
            axios.get('/api/inquiries/archive')
                .then(response => {
                    this.inquiries = response.data.data.reverse()
                    console.log(this.inquiries)
                })
        },
        methods: {
            submit() {
                this.$refs.RecordForm.post()
            },
            edit(index, id) {
                const RecordFormStr = 'RecordForm'+index
                console.log(RecordFormStr)

                console.log(this.$refs[RecordFormStr][0])
                this.$refs[RecordFormStr][0].update(id)
                //this.$refs.RecordForm[index].hoge(id)
            },
            inhert(index, id) {
                console.log('親コンポーネントのinhertメソッド')
                this.$refs.RecordForm[index].inhert(id)
                //更新じゃなくて新規投稿
            },

            copyToClipboard(text) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        alert('copied')
                    })
                    .catch(e => {
                        console.error(e)
                    })
            },
            triggerClick(method, id) {
                if (method === "delete") {
                    this.deleteConfirm(id)
                }
            },
            deleteConfirm(id) {
                var activeClass = document.getElementById(id);
                activeClass.classList.add("activeCard");
                this.centerSnackbar.rebornButton = false
                this.centerSnackbar.deleteButton = true
                this.centerSnackbar.text = '削除しますか？'
                this.centerSnackbar.snackbar = true; //スナックバーを表示 
                this.deleteId = id //deleteやrebornメソッドで削除・復活させたいときに参照するID
            },
            del(id) {
                let postdata = {
                    id: id
                } //消したい記事のidをオブジェクトidに格納
                axios.post('/api/inquiries/delete', postdata)
                    .then(response => {
                        //ここに成功した時に行いたい処理を記載                             
                        console.log(response); //成功してたらデータが返ってくる
                        var activeClass = document.getElementById(id);
                        activeClass.classList.add("deleting");
                        setTimeout(function () {
                            activeClass.classList.add("delete");
                        }, 500);
                        this.centerSnackbar.text = '削除しました。'
                        this.centerSnackbar.deleteButton = false //削除ボタンを非表示
                        this.centerSnackbar.rebornButton = true //元に戻すボタンを表示
                        this.centerSnackbar.snackbar = true; //スナックバーを表示                             
                    })
                    .catch(function (error) {
                        alert('削除できませんでした。');
                        console.log(error);
                    })
            },
            reborn(id) {
                let postdata = {
                    id: id
                } //復活させたい記事のidをオブジェクトidに格納
                axios.post('/api/inquiries/reborn', postdata)
                    .then(response => {
                        this.centerSnackbar.text = '復活させました。'
                        this.centerSnackbar.rebornButton = false //元に戻すボタンを非表示
                        var activeClass = document.getElementById(id);
                        activeClass.classList.remove("delete");

                        setTimeout(function () {
                            activeClass.classList.remove("deleting");
                        }, 500);

                        setTimeout(function () {
                            activeClass.classList.remove("activeCard");
                        }, 1000);
                    })
                    .catch(function (error) {
                        this.centerSnackbar.text = '復活させました。'
                        console.log(error);
                    })
            },
            closeCenterSnackbar(id) {
                this.centerSnackbar.snackbar = false
                var activeClass = document.getElementById(id);
                activeClass.classList.remove("activeCard");
                this.centerSnackbar.deleteButton = false
            }
        },
    }

</script>
<style scoped>
    .inquiry {
        margin: 2em 0;
    }

    .bigFont {
        font-size: 1.0rem !important;
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
        color: #FFF !important;
    }

    .activeCard {
        opacity: 0.5;
        transition: .5s;
    }

    .deleting {
        opacity: 0.1;
        transition: .5s;
    }

    .delete {
        display: none;
    }
    .v-dialog>.v-card{
        background: #ffffffde !important;
    }



</style>
