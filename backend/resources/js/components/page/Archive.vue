<template>
    <div>
        <h1>
            受付記録
        </h1>

        <v-row justify="start">
            <v-col cols="auto">
                <v-dialog max-width="90%" scrollable>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" v-bind="attrs" v-on="on">
                            <v-icon>mdi-lead-pencil</v-icon>記録入力
                        </v-btn>
                    </template>
                    <template v-slot:default="dialog">
                        <v-card>
                            <v-toolbar color="primary" dark>Opening from the bottom</v-toolbar>
                            <v-card-text>
                                <div class="text-h2 pa-12">Hello world!</div>
                            </v-card-text>
                            <v-card-actions class="end">
                                <v-btn text @click="dialog.value = false">Close</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </v-col>

            <v-col cols="auto">
                <v-dialog transition="dialog-top-transition" max-width="600" scrollable>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" v-bind="attrs" v-on="on">
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
                                <v-btn text @click="dialog.value = false">Close</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </v-col>
        </v-row>


        <v-form ref="form" v-model="valid" lazy-validation>
            <v-autocomplete v-model="value.type" :items="items" dense filled label="機種名" required
                :rules="[v => !!v || '必須']"></v-autocomplete>

            <v-text-field v-model="value.dealer" label="販売店"></v-text-field>

            <v-row justify="start">
                <v-col cols="auto">
                    <v-text-field v-model="value.questioner" label="問い合わせ者"></v-text-field>
                </v-col>
                <v-col cols="auto">
                    <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber" :counter="13" label="電話番号"
                        :rules="[empty,tel]">
                    </v-text-field>
                </v-col>
            </v-row>

            <v-text-field v-model="value.customer" label="ユーザー"></v-text-field>

            <v-container fluid>
                <v-radio-group v-model="value.radio" row>
                    <v-radio label="トラブル" value="radio-1"></v-radio>
                    <v-radio label="障害" value="radio-2"></v-radio>
                    <v-radio label="故障" value="radio-3"></v-radio>
                    <v-radio label="購入前" value="radio-4"></v-radio>
                    <v-radio label="クレーム" value="radio-5"></v-radio>
                </v-radio-group>
            </v-container>

        </v-form>

        <button @click="hoge">adfdasf</button>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                dialog: false,
                value: {
                    phoneNumber: "",
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
                empty: v => !!v || '空は許す',
                tel: value => /^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/.test(value.replace(/[━.*‐.*―.*－.*\-.*ー.*\-]/gi,
                    '')) || '電話番号の形式が正しくありません',
      

            }
        },
        methods: {
            hoge() {
                console.log(this.value)

            },
        }

    }

</script>
