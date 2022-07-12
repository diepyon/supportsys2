<template>
    <div style="max-width:1000px;">
        <v-app>
            <v-card>
                <v-card-title>
                    <h1 class="display-1">記録入力</h1>
                </v-card-title>
                <v-card-text>
                    <RecordForm ref="RecordForm"></RecordForm>
                    <v-btn text color="primary" @click="submit">登録</v-btn>
                </v-card-text>
            </v-card>
        </v-app>
    </div>
</template>
<script>
    import RecordForm from '../layout/RecordForm'

    export default {
        components: {
            RecordForm,
        },
        methods: {
            submit() {
                this.$refs.RecordForm.post()
                this.$router.go({
                    path: this.$router.currentRoute.path,
                    force: true,
                });
            },
        }
    }

</script>
