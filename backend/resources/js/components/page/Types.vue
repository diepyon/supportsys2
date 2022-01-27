<template>
    <div>
        <v-form ref="test_form" v-model="valid" lazy-validation>
            <v-text-field v-model="value.name" label="機種名" hint="(例)APS100" required :rules="[rules.required]">
            </v-text-field>
        </v-form>
        <v-btn text @click="submit" color="primary">登録</v-btn>

        <v-card>
            <v-card-title>
                機種一覧
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="検索" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="items" hide-default-footer :search="search" >

                    <template v-slot:[`item.action`]="{ item }">
                        <v-btn @click="onClickShow(item)">編集</v-btn>
                        <v-btn @click="onClickShow(item)">削除</v-btn>
                    </template>

            </v-data-table>
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
    export default ({
        data() {
            return {
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
            submit() {
                console.log('types.vueのメソッド')
                let postData = {
                    name: this.value.name,
                };
                axios.post('/api/types/create', postData)
                    .then(response => {})
                    .catch(function (error) {
                        this.centerSnackbar.text = '登録できませんでした。'
                        console.log(error);
                    })
            },
            onClickShow(item) {
                console.log(`${item.name}:${item.id}`);
                this.deleteConfirm(item.id)
            },
            deleteConfirm(id) {
                console.log('わたっている')
                //var activeClass = document.getElementByIdid);
                //activeClass.classList.add("activeCard");
                this.centerSnackbar.rebornButton = false
                this.centerSnackbar.deleteButton = true
                this.centerSnackbar.text = '削除しますか？'
                this.centerSnackbar.snackbar = true; //スナックバーを表示 
                this.deleteId = id //deleteやrebornメソッドで削除・復活させたいときに参照するID
            },
            del(id){
                console.log(id+'を削除')
                //削除に成功した時のメッセージはアクシオスに書く
            },
            closeCenterSnackbar(id) {
                this.centerSnackbar.snackbar = false
                //var activeClass = document.getElementById(id);
                //activeClass.classList.remove("activeCard");
                this.centerSnackbar.deleteButton = false
            },            
        },
        mounted() {
            axios.get('/api/types/archive')
                .then(response => {
                    this.items = response.data.data.reverse()
                })

        },
    })

</script>
