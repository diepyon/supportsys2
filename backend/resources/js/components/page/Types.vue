<template>
    <div>
        <h1>機種登録</h1>
        <p>限られた人しかさわれない方がいいと思う。</p>

        <v-form ref="test_form" v-model="valid" lazy-validation>
            <v-text-field label="機種名" hint="(例)APS100" required :rules="[rules.required]"></v-text-field>
        </v-form>
        <v-btn text @click="submit" color="primary">登録</v-btn>
    </div>
</template>
<script>
    export default ({
        data() {
            return {
                valid: null,
                rules: {
                    required: (v) => !!v || "必須",
                }
            }
        },
        methods: {
            submit() {
                let postData = {
                    name: this.value.name,
                };
                axios.post('/api/types/create', postData)
                    .then(response => {})
                    .catch(function (error) {
                        this.centerSnackbar.text = '復活させられませんでした。'
                        console.log(error);
                    })
            },
        }
    })

</script>
