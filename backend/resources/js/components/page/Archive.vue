<template>
    <div>
        <h1>
            受付記録
        </h1>
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
                                <RecordForm ref="RecordForm" @parentMethod="getValue"></RecordForm>
                                <p>{{ value }}</p>
                            </v-card-text>
                            <v-card-actions class="end">
                                <v-btn text @click="submit" tabindex="">登録</v-btn>
                                <v-btn text @click="dialog.value = false" tabindex="">Close</v-btn>
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
                                <v-btn text @click="dialog.value = false" tabindex="">Close</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </v-col>
        </v-row>

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
                value: '',
            }
        },
        methods: {
            submit(value) {
                this.$refs.RecordForm.post()
/*                 var result = this.judge(this.value)
                if(result === true){
                    console.log('送信')
                    //this.$refs.RecordForm.post()
                }else{
                    alert('入力内容に不備があります。')//何があかんのかもわかるようにしたい
                    //https://qiita.com/tekunikaruza_jp/items/0a68d86084d961d632ac
                } */
            },
            
            getValue(value) {
                this.value = value
            },//子コンポーネントから送られてくる入力情報を受け取る

            judge(value){
                //この関数要らんかも
                if(value.answer && value.type && value.kinds && value.remote && value.satisfaction && value.question && value.authorizer){
                    return true
                }else if((value.remote=='なし'|| value.remote=='オリジナル') && value.answer && value.type && value.kinds && value.remote && value.satisfaction && value.question){
                   return false //リモートメンテナンスはチームビューアーではないので承認者名はなくても許す
                }else{
                    return false 
                }
            }//入力内容に問題がないかチェック　なければtrueをreturn
        }
    }

</script>
<style scoped>

</style>
