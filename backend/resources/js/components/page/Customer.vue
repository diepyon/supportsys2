<template>
    <div>
        <v-card>
            <v-container>
                <v-form ref="test_form" v-model="valid" lazy-validation>
                    <v-autocomplete v-model="value.type" :items="types" dense filled label="機種名" required
                        :rules="[rules.required]" no-data-text="該当なし"></v-autocomplete>

                    <v-text-field v-model="value.lisence_type" label="ライセンスタイプ" :rules="[rules.alphaNum]" :counter="255"
                        hint="S・M・L、5・6・7 など半角英数字で">
                    </v-text-field>

                    <v-text-field v-model="value.serial" label="シリアル" :rules="[rules.alphaNum,rules.required]"
                        :counter="255" hint="半角英数字">
                    </v-text-field>

                    <v-text-field v-model="value.mac" label="MACアドレス" :rules="[rules.mac]" :counter="255"
                        hint="半角英数字と「:」">
                    </v-text-field>

                    <v-row>
                        <v-col cols="12" sm="3" md="4">
                            <v-menu v-model="menu" :close-on-content-click="false" :nudge-right="40"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="value.shipdate" label="出荷日" prepend-icon="mdi-calendar"
                                        readonly v-bind="attrs" v-on="on">
                                    </v-text-field>
                                </template>
                                <v-date-picker v-model="value.shipdate" @input="menu = false" :max="value.lisence_stop">
                                </v-date-picker>
                            </v-menu>
                        </v-col>

                        <v-col cols="12" sm="3" md="4">
                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="value.lisence_stop" label="ライセンス期限"
                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on" @click="TodayInTheFuture()" hint="カレンダー上の年をクリックで任意の西暦に移動可能">
                                    </v-text-field>
                                </template>
                                <v-date-picker v-model="value.lisence_stop" @input="menu2 = false"
                                    :min="value.shipdate">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>

                    <v-text-field v-model="value.customer" label="ユーザー名" :rules="[rules.required]" :counter="255"
                        hint="(例)株式会社亜田岡建築工房"></v-text-field>
                    <v-text-field v-model="value.furigana" label="ユーザー名フリガナ" :rules="[rules.required,rules.katakana]"
                        :counter="255" hint="全角カタカナ、「・」などの記号不可"></v-text-field>

                    <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber" :counter="13"
                        label="ユーザー電話番号(「 - 」有無どちらでも可)" :rules="[rules.tel]">
                    </v-text-field>
                    <v-text-field v-model="value.dealer" label="販売店名" :rules="[rules.required]" :counter="255"
                        hint="(例)株式会社エコソリューションホールディングス"></v-text-field>
                    <v-text-field v-model="value.remoteInfo" label="リモート接続情報" :rules="[rules.alphaNum]" :counter="255"
                        hint="(例)https://172.21.25.33 などあれば自由に記載"></v-text-field>
                </v-form>
            </v-container>
        </v-card>
    </div>
</template>
<script>
    export default ({
        data() {
            return {
                value: {
                    type: "",
                    lisence_type: "",
                    serial: "",
                    mac: "",
                    customer: "",
                    furigana: "",
                    shipdate: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString()
                        .substr(0, 10),
                    lisence_stop:null,
                    phoneNumber: "",
                    dealer: "",
                    remoteInfo: "",

                    shipdate: null,
                },
                valid: null,
                types: [],


                menu: false,
                menu2: false,
                rules: {
                    tel: (v) =>
                        /^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/.test(
                            v.replace(/[━.*‐.*―.*－.*\-.*ー.*\-]/gi, "")
                        ) ||
                        v == "" ||
                        "電話番号の形式が正しくありません",
                    required: (v) => !!v || "必須",
                    alphaNum: (v) => {
                        const pattern = /^[0-9a-zA-Z]*$/;
                        return pattern.test(v) || "半角英数字のみで入力してください。";
                    },
                    mac: (v) => {
                        const macAddress = /^([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}$/;
                        return (macAddress.test(v) || v == '') || "MACアドレスの形式で入力してください。";
                    },
                    katakana: (v) => {
                        const pattern = /^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/
                        return pattern.test(v) || "全角カタカナのみで入力してください。（アルファベットも許可したい）"
                    }
                },
            }
        },
        mounted() {
            this.getTypes()

        },
        methods: {
            getTypes() {
                axios.get('/api/types/archive')
                    .then(response => {
                        const types = response.data.data
                        types.forEach((value) => {
                            this.types.push(value.name)
                        })
                        this.types.sort()
                    })
            },
            TodayInTheFuture() {
                const today = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().split(
                    'T')[0]
                const splitDate = today.split('-')
                const year = String(Number(splitDate[0]) + 5)
                const date = splitDate[1] + '-' + splitDate[2]
                const future = year + '-' + date
                this.value.lisence_stop = future
            }
        }
    })

</script>
