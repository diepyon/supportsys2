<template>
    <div>
        <h1>
            受付記録
        </h1>

        <v-row justify="center">
            <v-dialog v-model="dialog" scrollable max-width="90%">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" class="ma-2" dark v-bind="attrs" v-on="on">
                        記録入力
                    </v-btn>
                </template>
                <v-card>
                    <v-card-title>記録入力</v-card-title>
                    <v-divider></v-divider>
                    <v-card-text style="height: 70%;">

                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-btn color="blue darken-1" text @click="dialog = false">
                            登録
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="dialog = false">
                            閉じる
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>

        
    </div>
</template>
<script>
    export default {
        data() {
            return {
                dialogm1: '',
                dialog: false,
            }
        },
    }

</script>
