<template>
    <div>
        <h1>受付記録</h1>
        <v-row justify="start">
            <v-col cols="auto">
                <v-dialog max-width="90%" scrollable>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" v-bind="attrs" v-on="on" tabindex="1">
                            <v-icon>mdi-lead-pencil</v-icon>記録入力
                        </v-btn>
                    </template>
                    <template v-slot:default="dialog">
                        <v-card>
                            <v-toolbar color="primary" dark>記録入力</v-toolbar>
                            <v-card-text>
                                <RecordForm ref="RecordForm"></RecordForm>
                            </v-card-text>
                            <v-card-actions class="end">
                                <v-btn text @click="submit">登録</v-btn>
                                <v-btn text @click="dialog.value = false">閉じる</v-btn>
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

        <v-card v-for="inquiry in inquiries" :key="inquiry.id" class="inquiry" flat :id="inquiry.id">
            <span class="inquiryBox">
                <v-toolbar color="primary" dark dense>
                    <span class="overflow">{{inquiry.created_at}}</span>
                    <span class="overflow">機種名: {{inquiry.type}}</span>
                    <span class="overflow">シリアル:不明</span>
                    <v-spacer></v-spacer>
                    <v-btn icon>
                        <v-icon>mdi-subdirectory-arrow-left</v-icon>
                    </v-btn>

                    <v-dialog max-width="90%" scrollable>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on">
                                <v-icon>mdi-square-edit-outline</v-icon>
                            </v-btn>
                        </template>
                        <template v-slot:default="dialog">
                            <v-card>
                                <v-toolbar color="primary" dark>記録編集</v-toolbar>
                                <v-card-text>
                                    この記事のIDは{{inquiry.id}}
                                    <RecordForm v-bind:id="number" ref="RecordForm"></RecordForm>
                                </v-card-text>
                                <v-card-actions class="end">
                                    <v-btn text @click="submit">更新</v-btn>
                                    <v-btn text @click="dialog.value = false">閉じる</v-btn>
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
            <!--             <v-btn dark @click="snackbar = true">
                Open Snackbar
            </v-btn> -->
            <v-snackbar v-model="snackbar">
                {{ text }}
                <template v-slot:action="{ attrs }">
                    <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
                        Close
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

                snackbar: false,
                text: `削除しました`,
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
                    this.delete(id)
                }
            },
            delete(id) {
                var activeClass = document.getElementById(id);
                activeClass.classList.add("activeCard");

                let postdata = {
                    id: id
                } //消したい記事のidをオブジェクトidに格納

                setTimeout(function () {
                    if (confirm('削除しますか')) {
                        axios.post('/api/inquiries/delete', postdata)
                            .then(response => {
                                //ここに成功した時に行いたい処理を記載
                                //alert('削除しました。');
                                console.log(response); //成功してたらデータが返ってくる
                                activeClass.classList.add("delete");
       
                            })
                            .catch(function (error) {
                                alert('削除できませんでした。');
                                console.log(error);
                            })
                    } else { //やっぱり削除しなかったときの処理
                        activeClass.classList.remove("activeCard");

                    }
                }, 10);

            },

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

    .delete {
        display: none;
    }

</style>
