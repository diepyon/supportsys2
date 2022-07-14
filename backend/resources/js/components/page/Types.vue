<template>
    <div>
        <p>文字列として機種選択候補に追加するだけなので、誤って削除しても投稿済みの受付記録には影響しません。</p>
        <v-card>
                <v-card-text> 
                <v-form ref="test_form" v-model="valid" lazy-validation @submit.prevent>
                    <template v-if="resetFlag">
                        <v-text-field v-model="value.name" label="機種名" hint="(例)APS100" required
                            :rules="[rules.required]">
                        </v-text-field>
                    </template>
                    <v-btn text type="submit" @click="submit" color="primary">登録</v-btn>
                </v-form>
                </v-card-text> 
        </v-card>
        <hr>
        <v-card>
            <v-card-title>
                機種一覧
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="検索" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-card-text> 
            <v-data-table :headers="headers" :items="items" :search="search" :items-per-page="20">
                <template v-slot:[`item.action`]="{ item }">
                    <v-btn @click="dialog = true;getItem(item)" color="primary">編集</v-btn>
                    <v-btn @click="onClickShow(item)">削除</v-btn>
                </template>
            </v-data-table>
            </v-card-text> 
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

        <v-dialog max-width="500px" v-model="dialog">
            <template v-slot:default="dialog">
                <v-form ref="test_form" v-model="valid" lazy-validation @submit.prevent>
                    <v-card class="dialogBg">
                        <v-toolbar color="primary" dark dense>機種編集
                            <v-spacer></v-spacer>
                            <v-btn icon @click="dialog.value = false">
                                <v-icon>mdi-window-close</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <v-text-field v-model="name" label="機種名" required :rules="[rules.required]"></v-text-field>
                        </v-card-text>
                        <v-card-actions class="end">
                            <v-btn text @click="update(name,id)" color="primary">更新</v-btn>
                            <v-btn text @click="dialog.value = false;showArchive()" color="primary">閉じる
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </template>
        </v-dialog>
    </div>
</template>
<script>
    export default ({
        data() {
            return {
                resetFlag: true,
                name: null,
                id: null,
                dialog: null,
                valid: null,
                rules: {
                    required: (v) => !!v || "必須",
                },
                value: {},
                types: null,

                search: '',

                headers: [{
                        text: "名称",
                        value: "name"
                    },
                    {
                        text: "登録日",
                        value: "date"
                    },
                    {
                        text: "操作",
                        value: "action"
                    },
                ],
                items: [],

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
        methods: {
            getItem(item) {
                this.name = item.name
                this.id = item.id
            },
            submit() {
                let postData = {
                    name: this.value.name,
                };

                if (this.$refs.test_form.validate()) {
                    axios.post('/api/types/create', postData)
                        .then(response => {
                            console.log(response.data)

                            if (response.data) {
                                this.centerSnackbar.text = postData.name + 'は既に登録されています。'
                                this.centerSnackbar.snackbar = true; //スナックバーを表示 
                            } else {
                                this.showArchive()
                                this.centerSnackbar.text = postData.name + 'を新規登録しました。'
                                this.centerSnackbar.snackbar = true; //スナックバーを表示 
                                 this.$refs.test_form.reset();

                            }
                        })
                        .catch(function (error) {
                            this.centerSnackbar.text = '登録できませんでした。'
                            this.centerSnackbar.snackbar = true; //スナックバーを表示 
                            console.log(error);
                        })

                    //console.log(result)
                } else {
                    alert("入力してください。");
                }
            },
            onClickShow(item) {
                console.log(`${item.name}:${item.id}`);
                this.deleteConfirm(item.id, item.name)
            },
            deleteConfirm(id, name) {
                this.centerSnackbar.rebornButton = false
                this.centerSnackbar.deleteButton = true
                this.centerSnackbar.text = name + 'を削除しますか？'
                this.centerSnackbar.snackbar = true; //スナックバーを表示 
                this.deleteId = id //deleteやrebornメソッドで削除・復活させたいときに参照するID
            },
            del(id) {
                const postdata = {
                    id: id
                }
                axios.post('/api/types/delete', postdata)
                    .then(response => {
                        if (response.data === 0) { //削除したレコードの数がリターンされる
                            this.centerSnackbar.text = '削除できませんでした。'
                            this.centerSnackbar.snackbar = true; //スナックバーを表示 
                        } else {
                            this.centerSnackbar.text = '削除しました。'
                            this.centerSnackbar.deleteButton = false //削除ボタンを非表示
                            //this.centerSnackbar.rebornButton = true //元に戻すボタンを表示
                            this.centerSnackbar.snackbar = true //スナックバーを表示  
                            this.showArchive()
                        }
                    })
                    .catch(function (error) {
                        this.centerSnackbar.text = '削除できませんでした。'
                        console.log(error);
                    })
            },
            closeCenterSnackbar(id) {
                this.centerSnackbar.snackbar = false
                this.centerSnackbar.deleteButton = false
            },
            showArchive() {
                axios.get('/api/types/archive')
                    .then(response => {
                        this.items = response.data.data.reverse()
                    })
            },
            update(name, id) {
                let postData = {
                    name: name,
                    id: id
                }
                if (this.$refs.test_form.validate()) {
                    axios.post('/api/types/update', postData)
                        .then(response => {
                            console.log(response.statusText)
                            //this.dialog = false
                            this.centerSnackbar.text = postData.name + 'に更新しました。'
                            this.centerSnackbar.snackbar = true; //スナックバーを表示                             
                            this.dialog = false
                            this.showArchive()
                        })
                        .catch(function (error) {
                            this.centerSnackbar.text = '編集できませんでした。'
                            this.centerSnackbar.snackbar = true; //スナックバーを表示 
                            console.log(error);

                        })
                } else {
                    alert('入力してください。')
                }
            },
        },
        mounted() {
            this.showArchive()
        },
    })

</script>
