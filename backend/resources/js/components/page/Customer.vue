<template>
    <div>
        <v-card>
            <v-container>
                <p>機種によっては入力不要の項目もあるので、最低限埋めるだけでもOK</p>
                <v-form ref="test_form" v-model="valid" lazy-validation>
                    <v-autocomplete v-model="value.type" :items="types" dense filled label="機種名" required
                        :rules="[rules.required]" no-data-text="該当なし"></v-autocomplete>

                    <v-text-field v-model="value.detail_type" label="機種名詳細" :counter="255" hint="(例)Cbu-α507 stdなど"
                        prepend-icon="mdi-alpha-p-box">
                    </v-text-field>
                    <p>stdとかもかけたほうがいいんかな？いちいち分ける意味わからんけど。</p>

                    <v-text-field v-model="value.lisence_type" label="ライセンスタイプ" :rules="[rules.alphaNum]" :counter="255"
                        hint="S・M・L、5・6・7 など半角英数字で" prepend-icon="mdi-certificate">
                    </v-text-field>

                    <v-text-field v-model="value.serial" label="シリアル" :rules="[rules.alphaNum,rules.required]"
                        :counter="255" hint="半角英数字" prepend-icon="mdi-alpha-s-box">
                    </v-text-field>

                    <v-text-field v-model="value.mac" label="MACアドレス" :rules="[rules.mac]" :counter="255"
                        prepend-icon="mdi-barcode" hint="半角英数字と「:」">
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
                                        prepend-icon="mdi-calendar-clock" readonly v-bind="attrs" v-on="on"
                                        @click="TodayInTheFuture()" hint="カレンダー上の年をクリックで任意の西暦に移動可能">
                                    </v-text-field>
                                </template>
                                <v-date-picker v-model="value.lisence_stop" @input="menu2 = false"
                                    :min="value.shipdate">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>

                    <v-text-field v-model="value.customerName" label="ユーザー名" :rules="[rules.required]" :counter="255"
                        hint="(例)株式会社亜田岡建築工房" prepend-icon="mdi-account"></v-text-field>

                    <v-text-field v-model="value.furigana" label="ユーザー名フリガナ" :rules="[rules.required,rules.katakana]"
                        :counter="255" hint="全角カタカナ、「・」などの記号不可" prepend-icon="mdi-furigana-horizontal"></v-text-field>

                    <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber" :counter="13"
                        label="ユーザー電話番号(「 - 」有無どちらでも可)" :rules="[rules.tel]" prepend-icon="mdi-phone-in-talk">
                    </v-text-field>

                    <v-text-field v-model="value.address" label="顧客住所" :counter="255" hint="大阪府大阪市中央区安土町1-6-10"
                        prepend-icon="mdi-map-marker "></v-text-field>

                    <v-text-field v-model="value.dealer" label="販売店名" :rules="[rules.required]" :counter="255"
                        hint="(例)株式会社エコソリューションホールディングス" prepend-icon="mdi-store"></v-text-field>

                    <v-text-field v-model="value.remoteInfo" label="リモート接続情報" :rules="[rules.alphaNum]" :counter="255"
                        hint="(例)https://172.21.25.33 などあれば自由に記載" prepend-icon="mdi-remote-desktop"></v-text-field>

                    <v-text-field v-model="value.remoteInfo" label="備考" :counter="255" hint="何か追記したいことがあれば"
                        prepend-icon="mdi-comment-processing-outline"></v-text-field>
                    <v-btn text @click="submit" color="primary">登録</v-btn>
                </v-form>
            </v-container>
        </v-card>

        <v-card v-for="(customer,index) in customers" :key="customer.id" :id="customer.id" class="customer" flat>
            <span class="customerBox">
                <v-toolbar color="primary" dark dense>
                    <span class="overflow">登録日:{{ customer.created_at }}&nbsp;</span>
                    <span class="overflow">機種名: {{ customer.type }}&nbsp;{{ customer.detail_type }}&nbsp;</span>
                    <span class="overflow">シリアル:{{ customer.serial }}</span>
                    <v-spacer></v-spacer>

                    <v-btn icon @click="dialog.posted = true;updateDialog(customer,index,'update');id=customer.id"
                        :disabled="centerSnackbar.snackbar">
                        <v-icon>mdi-square-edit-outline</v-icon>
                    </v-btn>

                    <!-- <v-btn icon :disabled="centerSnackbar.snackbar">
                        <v-icon>mdi-flag-variant-outline</v-icon>
                    </v-btn>

                    <v-menu offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon v-bind="attrs" v-on="on" :disabled="centerSnackbar.snackbar">
                                <v-icon>mdi-dots-horizontal</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item v-for="(cardMenu, method, index) in cardMenus" :key="index" class="hover">
                                <v-list-item-title @click="triggerClick(cardMenu.method, customer.id)">
                                    {{ cardMenu.title }}
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu> -->
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
                                    <v-layout v-if="customer.customerName" justify-center>
                                        {{customer.customerName}}</v-layout>
                                    <v-layout v-else justify-center>不明</v-layout>
                                    <span v-if="customer.phoneNumber" style="text-align: center">
                                        <v-icon size="4">mdi-phone</v-icon>
                                        <span>{{ customer.phoneNumber }}</span>
                                        <v-btn icon style="height: " @click="copyToClipboard(customer.phoneNumber)">
                                            <v-icon size="4">mdi-content-copy</v-icon>
                                        </v-btn>
                                    </span>
                                </div>
                            </v-col>
                            <v-col cols="6">
                                <span class="bigFont">{{ customer.question }}</span>
                            </v-col>
                            <v-col cols="4">
                                <span class="">
                                    <div>
                                        <span class="subject">販売店:</span>
                                        <span v-if="customer.dealer">{{ customer.dealer }}</span>
                                        <span v-else>不明</span>
                                    </div>
                                    <div>
                                        <span class="subject">ユーザー:</span>
                                        <span v-if="customer.customer">{{ customer.customer }}</span>
                                        <span v-else>不明</span>
                                    </div>
                                    <div>
                                        <span class="subject">満足度:</span>{{ customer.satisfaction }}
                                    </div>
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
    export default ({
        data() {
            return {
                value: {
                    type: "",
                    lisence_type: "",
                    serial: "",
                    mac: "",
                    customerName: "",
                    furigana: "",
                    shipdate: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString()
                        .substr(0, 10),
                    lisence_stop: null,
                    phoneNumber: "",
                    address: "",
                    dealer: "",
                    remoteInfo: "",
                    detail_type: "",
                    shipdate: null,
                },
                valid: null,
                types: [],
                customers: [],

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
            this.getTypes()
            this.getCustomers()
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
            getCustomers() {
                axios.get('/api/customers/archive')
                    .then(response => {
                        const customers = response.data.data
                        this.customers = customers.reverse()
                        //revirceしたほうがいいのと、status publichに絞り込んだほうがいい
                        console.log(this.customers[7].phoneNumber)
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
            },
            clearShipdate() {

            },
            clearLisenceStop() {},

            submit() {
                let postData = this.value
                console.log(postData)
                if (this.$refs.test_form.validate()) {
                    axios.post('/api/customers/create', postData)
                        .then(response => {
                            console.log(response)
                            this.centerSnackbar.text = postData.name + 'を新規登録しました。'
                            this.centerSnackbar.snackbar = true; //スナックバーを表示 
                            this.$router.go({
                                path: this.$router.currentRoute.path,
                                force: true
                            }) //強制リロード]
                        })
                        .catch(function (error) {
                            // this.centerSnackbar.text = '登録できませんでした。'
                            // this.centerSnackbar.snackbar = true; //スナックバーを表示 
                            console.log(error);
                        })
                }
            },
        }
    })

</script>
