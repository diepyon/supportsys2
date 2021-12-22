<template>
    <div>
        <h1>
            受付記録
        </h1>

        <v-row justify="justify-start">
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
                            <v-card-actions class="justify-end">
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
                            <v-card-actions class="justify-end">
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

            <v-text-field v-model="value.questioner" label="問い合わせ者"></v-text-field>

            <v-text-field v-model="value.customer" label="ユーザー"></v-text-field>

            <validation-provider v-slot="{ errors }" name="phoneNumber" :rules="{
          required: true,
          digits: 7,
          regex: '^(71|72|74|76|81|82|84|85|86|87|88|89)\\d{5}$'
        }">
                <v-text-field v-model="phoneNumber" :counter="7" :error-messages="errors" label="Phone Number" required>
                </v-text-field>
            </validation-provider>
        </v-form>



    </div>
</template>
<script>
    export default {
        data() {
            return {
                dialogm1: '',
                dialog: false,
                value: {},
                valid:null,

                isEditing: false,
                model: null,
                items: [
                    'TDC200',
                    'TD480',
                    'UTM100',
                    'UTM200',
                    'EEW',
                ],


            }
        },
    }

</script>
