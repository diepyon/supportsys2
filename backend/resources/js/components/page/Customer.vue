<template>
    <div>
        <v-card>
            <v-container>
                <p>機種によっては入力不要の項目もあるので、最低限埋めるだけでもOK</p>
                <v-form ref="test_form" v-model="valid" lazy-validation>
                    <v-autocomplete v-model="value.type" :items="types" dense filled label="機種名" required
                      prepend-icon="mdi-trash-can"   :rules="[rules.required]" no-data-text="該当なし"></v-autocomplete>

                    <v-text-field v-model="value.detail_type" label="機種名詳細" :counter="255" hint="(例)Cbu-α507 stdなど"
                        prepend-icon="mdi-alpha-p-box">
                    </v-text-field>
                    <p>
                        stdとかもかけたほうがいいんかな？いちいち分ける意味わからんけど。
                    </p>

                    <v-text-field v-model="value.lisence_type" label="ライセンスタイプ" :rules="[rules.alphaNum]" :counter="255"
                        hint="S・M・L、5・6・7 など半角英数字で" prepend-icon="mdi-certificate">
                    </v-text-field>

                    <v-text-field v-model="value.serial" label="シリアル" :rules="[rules.alphaNum, rules.required]"
                       filled :counter="255" hint="半角英数字" prepend-icon="mdi-alpha-s-box">
                    </v-text-field>

                    <v-text-field v-model="value.mac" label="MACアドレス" :rules="[rules.mac]" :counter="255"
                        prepend-icon="mdi-barcode" hint="半角英数字と「:」">
                    </v-text-field>

                    <v-row>
                        <v-col cols="12" sm="3" md="4">
                            <v-menu v-model="postDateMenu.shipdate" :close-on-content-click="false" :nudge-right="40"
                                transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="value.shipdate" label="出荷日" prepend-icon="mdi-calendar"
                                        readonly v-bind="attrs" v-on="on">
                                    </v-text-field>
                                </template>
                                <v-date-picker v-model="value.shipdate" @input="postDateMenu.shipdate = false"
                                    :max="value.lisence_stop">
                                </v-date-picker>
                            </v-menu>
                        </v-col>

                        <v-col cols="12" sm="3" md="4">
                            <v-menu v-model="postDateMenu.lisence_stop" :close-on-content-click="false"
                                :nudge-right="40" transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="value.lisence_stop" label="ライセンス期限"
                                        prepend-icon="mdi-calendar-clock" readonly v-bind="attrs" v-on="on"
                                        @click="TodayInTheFuture()" hint="カレンダー上の年をクリックで任意の西暦に移動可能">
                                    </v-text-field>
                                </template>
                                <v-date-picker v-model="value.lisence_stop" @input="postDateMenu.lisence_stop = false"
                                    :min="value.shipdate">
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>

                    <v-text-field v-model="value.customerName" label="ユーザー名" :rules="[rules.required]" :counter="255"
                       filled hint="(例)株式会社亜田岡建築工房" prepend-icon="mdi-account"></v-text-field>

                    <v-text-field v-model="value.furigana" label="ユーザー名フリガナ" :rules="[rules.furigana]" :counter="255"
                        hint="全角カタカナ、「・」などの記号不可" prepend-icon="mdi-furigana-horizontal"></v-text-field>

                    <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber" :counter="13"
                        label="ユーザー電話番号(「 - 」有無どちらでも可)" :rules="[rules.tel]" prepend-icon="mdi-phone-in-talk">
                    </v-text-field>

                    <v-text-field v-model="value.address" label="顧客住所" :counter="255" hint="大阪府大阪市中央区安土町1-6-10"
                        prepend-icon="mdi-map-marker"></v-text-field>

                    <v-text-field v-model="value.dealer" label="販売店名" :rules="[rules.required]" :counter="255"
                        filled hint="(例)株式会社エコソリューションホールディングス" prepend-icon="mdi-store"></v-text-field>

                    <v-text-field v-model="value.remoteInfo" label="リモート接続情報" :rules="[rules.alphaNum]" :counter="255"
                        hint="(例)https://172.21.25.33 などあれば自由に記載" prepend-icon="mdi-remote-desktop"></v-text-field>

                    <v-text-field v-model="value.remarks" label="備考" :counter="255" hint="何か追記したいことがあれば"
                        prepend-icon="mdi-comment-processing-outline"></v-text-field>
                    <v-btn text @click="submit" color="primary">登録</v-btn>
                </v-form>
            </v-container>
        </v-card>

        <v-dialog max-width="90%" scrollable persistent v-model="dialog.posted">
            <template v-slot:default="postedDialog">
                <v-card class="dialogBg">
                    <v-toolbar color="primary" dark dense>{{actionTitleName}}
                        <v-spacer></v-spacer>
                        <v-btn icon @click="closeConfirm">
                            <v-icon>mdi-window-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <div style="padding-top: 16px">
                            <v-form ref="test_form" v-model="valid" lazy-validation>
                                <v-autocomplete v-model="value.type" :items="types" dense filled label="機種名" required
                                    :rules="[rules.required]" no-data-text="該当なし"></v-autocomplete>

                                <v-text-field v-model="value.detail_type" label="機種名詳細" :counter="255"
                                    hint="(例)Cbu-α507 stdなど" prepend-icon="mdi-alpha-p-box">
                                </v-text-field>
                                <p>
                                    stdとかもかけたほうがいいんかな？いちいち分ける意味わからんけど。
                                </p>

                                <v-text-field v-model="value.lisence_type" label="ライセンスタイプ" :rules="[rules.alphaNum]"
                                    :counter="255" hint="S・M・L、5・6・7 など半角英数字で" prepend-icon="mdi-certificate">
                                </v-text-field>

                                <v-text-field v-model="value.serial"
                                    :rules="[rules.alphaNum, rules.required]" :counter="255" hint="半角英数字"
                                    filled
                                    prepend-icon="mdi-alpha-s-box">
                                </v-text-field>

                                <v-text-field v-model="value.mac" label="MACアドレス" :rules="[rules.mac]" :counter="255"
                                    prepend-icon="mdi-barcode" hint="半角英数字と「:」">
                                </v-text-field>

                                <v-row>
                                    <v-col cols="12" sm="3" md="4">
                                        <v-menu v-model="menu" :close-on-content-click="false" :nudge-right="40"
                                            transition="scale-transition" offset-y min-width="auto">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field v-model="value.shipdate" label="出荷日"
                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                </v-text-field>
                                            </template>
                                            <v-date-picker v-model="value.shipdate" @input="menu = false"
                                                :max="value.lisence_stop">
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

                                <v-text-field v-model="value.customerName" label="ユーザー名" :rules="[rules.required]"
                                    :counter="255" hint="(例)株式会社亜田岡建築工房" prepend-icon="mdi-account"></v-text-field>

                                <v-text-field v-model="value.furigana" label="ユーザー名フリガナ" :rules="[rules.furigana]"
                                    :counter="255" hint="全角カタカナ、「・」などの記号不可" prepend-icon="mdi-furigana-horizontal">
                                </v-text-field>

                                <v-text-field id="tel" v-bind:type="'tel'" v-model="value.phoneNumber" :counter="13"
                                    label="ユーザー電話番号(「 - 」有無どちらでも可)" :rules="[rules.tel]"
                                    prepend-icon="mdi-phone-in-talk">
                                </v-text-field>

                                <v-text-field v-model="value.address" label="顧客住所" :counter="255"
                                    hint="大阪府大阪市中央区安土町1-6-10" prepend-icon="mdi-map-marker"></v-text-field>

                                <v-text-field v-model="value.dealer" label="販売店名" :rules="[rules.required]"
                                    :counter="255" hint="(例)株式会社エコソリューションホールディングス" prepend-icon="mdi-store">
                                </v-text-field>

                                <v-text-field v-model="value.remoteInfo" label="リモート接続情報" :rules="[rules.alphaNum]"
                                    :counter="255" hint="(例)https://172.21.25.33 などあれば自由に記載"
                                    prepend-icon="mdi-remote-desktop"></v-text-field>

                                <v-text-field v-model="value.remarks" label="備考" :counter="255" hint="何か追記したいことがあれば"
                                    prepend-icon="mdi-comment-processing-outline"></v-text-field>
                            </v-form>
                        </div>
                    </v-card-text>
                    <v-card-actions class="end">
                        <v-btn text color="primary" v-if="submitButtonName=='更新'" @click="update(customerID)">
                            {{submitButtonName}}</v-btn>
                        <v-btn text @click="closeConfirm" color="primary">閉じる</v-btn>
                        <v-btn v-show="false" @click="postedDialog.value = false;"></v-btn>
                    </v-card-actions>
                </v-card>
            </template>
        </v-dialog>

        <v-card v-for="(customer, index) in customers" :key="customer.id" :id="customer.id" class="cardMargin" flat>
            <span class="customerBox">
                <v-toolbar color="primary" dark dense>
                    <span class="overflow">登録日:{{ customer.created_at }}&nbsp;</span>
                    <span class="overflow">機種名: {{ customer.type }}&nbsp;{{
              customer.detail_type
            }}&nbsp;</span>
                    <span class="overflow">シリアル:{{ customer.serial }}</span>
                    <v-spacer></v-spacer>

                    <v-btn icon @click="
              dialog.posted = true;
              updateDialog(customer, index, 'update');
              id = customer.id;
            " :disabled="centerSnackbar.snackbar">
                        <v-icon>mdi-square-edit-outline</v-icon>
                    </v-btn>



                    <!-- メニュー右の・・・ -->
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
                                    <v-layout justify-center>
                                        {{ customer.customerName }}</v-layout>

                                    <span class="layout justify-center">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                    mdi-phone </v-icon>{{ customer.phoneNumber }}
                                            </template>
                                            <span>電話番号</span>
                                        </v-tooltip>

                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn icon style="height: "
                                                    @click="copyToClipboard(customer.phoneNumber)"
                                                    v-if="customer.phoneNumber != '不明'">
                                                    <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                        mdi-content-copy
                                                    </v-icon>
                                                </v-btn>
                                            </template>
                                            <span>電話番号コピー</span>
                                        </v-tooltip>
                                    </span>
                                </div>
                            </v-col>
                            <v-col cols="6">
                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-alpha-p-box </v-icon>
                                            {{customer.detail_type}}
                                        </template>
                                        <span>機種名詳細</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-certificate
                                            </v-icon>
                                            {{ customer.lisence_type }}
                                        </template>
                                        <span>ライセンスタイプ</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-clock-start </v-icon>
                                            {{ customer.lisence_start }}～{{ customer.lisence_stop }}
                                        </template>
                                        <span>ライセンス期間</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-map-marker </v-icon>{{ customer.address }}
                                        </template>
                                        <span>住所</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-barcode
                                            </v-icon>
                                            {{ customer.mac }}
                                        </template>
                                        <span>MACアドレス</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-remote-desktop
                                            </v-icon>
                                            {{ customer.remoteInfo }}
                                        </template>
                                        <span>リモート接続情報</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-calendar
                                            </v-icon>
                                            {{ customer.shipdate }}
                                        </template>
                                        <span>出荷日</span>
                                    </v-tooltip>
                                </div>

                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-comment-processing-outline
                                            </v-icon>
                                            {{ customer.remarks }}
                                        </template>
                                        <span>備考</span>
                                    </v-tooltip>
                                </div>
                            </v-col>
                            <v-col cols="4">
                                <div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-icon color="primary" dark v-bind="attrs" v-on="on">
                                                mdi-store
                                            </v-icon>
                                            {{ customer.dealer }}
                                        </template>
                                        <span>販売店</span>
                                    </v-tooltip>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
            </span>
        </v-card>

        <div class="text-center">
            <v-pagination v-model="current_page" :length="length" @input="changePage"></v-pagination>
        </div>

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
    export default {
        data() {
            return {
                value: {
                    type: "",
                    lisence_type: "",
                    serial: "",
                    mac: "",
                    customerName: "",
                    furigana: "",
                    shipdate: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                        .toISOString()
                        .substr(0, 10),
                    lisence_start: null,
                    lisence_stop: null,
                    phoneNumber: "",
                    address: "",
                    dealer: "",
                    remoteInfo: "",
                    detail_type: "",
                    shipdate: null,
                },
                current_page: 1,
                length: null, //総ページ数
                customerID: null,
                index: null,
                dialog: {
                    posted: false
                },
                actionTitleName: "ユーザー情報編集",
                submitButtonName: "更新",

                valid: null,
                types: [],
                customers: [],

                menu: false,
                menu2: false,

                postDateMenu: {
                    shipdate: false,
                    lisence_stop: false
                },

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
                        return (
                            macAddress.test(v) ||
                            v == "" ||
                            "MACアドレスの形式で入力してください。"
                        );
                    },
                    furigana: (v) => {
                        const furigana = /^[ァ-ヴー0-9a-zA-Z]+$/
                        return (
                            furigana.test(v) ||
                            v == "" ||
                            "全角カタカナか半角英数で入力してください。（省略可）"
                        );
                    },
                },
                centerSnackbar: {
                    snackbar: false,
                    text: "",
                    deleteButton: false,
                    rebornButton: false,
                    timeout: 20000,
                },
                deleteId: null,
            };
        },
        mounted() {
            this.getTypes();
            this.getCustomers();

            if (this.$route.query.page) {
                this.current_page = Number(this.$route.query.page)
            } else {
                this.current_page = 1
            }
        },
        methods: {
            closeConfirm() {
                alert('未作成')
            },
            getTypes() {
                axios.get("/api/types/archive").then((response) => {
                    const types = response.data.data;
                    types.forEach((value) => {
                        this.types.push(value.name);
                    });
                    this.types.sort();
                });
            },
            async getCustomers() {
                const result = await axios.get(`/api/customers?page=${this.current_page}`)

                console.log(result.data.meta)

                const customers = result.data
                this.customers = customers.data

                this.dialog = {
                    post: null,
                    posted: null,
                };

                //総ページ数を取得
                this.length = (Math.ceil(customers.meta.total / customers.meta
                    .per_page)) //（テーブルのレコード総数÷1ページ当たりの表示件数）を繰り上げ

                //現在のページに表示されている記事数分だけ繰り返す
                for (let i = 0; i < this.customers.length; i++) {
                    this.customers[i] = Object.fromEntries(
                        Object.entries(this.customers[i]).map(([k, v]) => [
                            k,
                            v === null ? "不明" : v,
                        ])
                    ); //nullを不明に置き換え

                    //日時整形
                    this.customers[i].lisence_start =
                        this.customers[i].lisence_start.split(" ")[0]
                    this.customers[i].lisence_stop =
                        this.customers[i].lisence_stop.split(" ")[0]
                }
            },
            moveToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            },
            changePage(number) {
                this.current_page = number
                this.getCustomers()
                window.history.pushState({
                        number
                    },
                    `Page${number}`,
                    `${window.location.origin}/customer?page=${number}`
                )
                this.moveToTop()
            },

            updateDialog(customer, index, action) {
                if (customer) {
                    this.value = Object.fromEntries(
                        Object.entries(customer).map(([k, v]) => [k, v === null ? "" : v])
                    ) //nullを""に置き換え

                    this.value = Object.fromEntries(
                        Object.entries(customer).map(([k, v]) => [k, v === "不明" ? "" : v])
                    ) //不明を"""に置き換え

                    this.value.shipdate = this.value.shipdate.split(" ")[0] //日付から時間を除外  
                    this.customer = this.value //this.valueの内容を反映
                    this.customerID = customer.id
                    this.index = customer.index
                }
            },
            update(id) {
                let postData = this.value
                if (this.$refs.test_form.validate()) {
                    axios.post('/api/customers/update',
                            postData) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は入力された内容）
                        .then(response => {
                            alert('更新しました。');
                            console.log(response.statusText)
                            this.getCustomers()
                            this.dialog.posted = false
                            this.action = null
                        })
                        .catch(function (error) {
                            alert('あかんかったわ、コンソール見て');
                            console.log(error);
                        })
                } else {
                    alert('入力内容に不備があります。')
                }
            },

            copyToClipboard() {
                alert("未作成");
            },

            TodayInTheFuture() {
                const today = new Date(
                        Date.now() - new Date().getTimezoneOffset() * 60000
                    )
                    .toISOString()
                    .split("T")[0];
                const splitDate = today.split("-");
                const year = String(Number(splitDate[0]) + 5);
                const date = splitDate[1] + "-" + splitDate[2];
                const future = year + "-" + date;
                this.value.lisence_stop = future;
            },
            clearShipdate() {},
            clearLisenceStop() {},

            submit() {
                let postData = this.value
                if (this.$refs.test_form.validate()) {
                    axios.post("/api/customers/create", postData)
                        .then((response) => {
                            this.centerSnackbar.text = postData.customerName + "を新規登録しました。";
                            this.centerSnackbar.snackbar = true; //スナックバーを表示
                            this.$refs.test_form.reset(); //フォームリセット
                            this.changePage(1)
                        })
                        .catch(function (error) {
                            // this.centerSnackbar.text = '登録できませんでした。'
                            // this.centerSnackbar.snackbar = true; //スナックバーを表示
                            console.log(error);
                        });
                }
            },
        },
    };

</script>
