<template>
    <div>
        <v-form ref="test_form" v-model="valid" lazy-validation>
            <v-autocomplete v-model="value.type" :items="items" dense filled label="機種名" required
                :rules="[rules.required]" no-data-text="該当なし"></v-autocomplete>
            <v-text-field v-model="value.serial" label="シリアル" :rules="[rules.alphaNum,rules.required]" :counter="255" hint="半角英数字">
            </v-text-field>
            <v-text-field v-model="value.customer" label="ユーザー名" :rules="[rules.required]" :counter="255"
                hint="(例)株式会社亜田岡建築工房"></v-text-field>
            <v-text-field v-model="value.furigana" label="ユーザー名フリガナ" :rules="[rules.required,rules.katakana]" :counter="255"
                hint="全角カタカナ、「・」などの記号不可"></v-text-field>                
            <v-text-field v-model="value.dealer" label="販売店名" :rules="[rules.required]" :counter="255"
                hint="(例)株式会社エコソリューションホールディングス"></v-text-field>                
        </v-form>
    </div>
</template>
<script>
    export default ({
        data() {
            return {
                value: {},
                valid:null,
                items: ["TDC200", "TD480", "UTM100", "UTM200", "EEW"],
                rules: {
                    tel: (value) =>
                        /^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/.test(
                            value.replace(/[━.*‐.*―.*－.*\-.*ー.*\-]/gi, "")
                        ) ||
                        value == "" ||
                        "電話番号の形式が正しくありません",
                    required: (v) => !!v || "必須",
                    alphaNum: (value) => {
                        const pattern = /^[0-9a-zA-Z]*$/;
                        return pattern.test(value) || "半角英数字のみで入力してください。";
                    },
                    katakana:(v)=>{
                        const pattern =/^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/
                        return pattern.test(v)||"全角カタカナのみで入力してください。（アルファベットも許可したい）"
                    }
                },
            }
        },
    })

</script>
