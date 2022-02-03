<template>
    <div style="max-width:1000px;">
        <RecordForm ref="RecordForm"></RecordForm>
        <v-btn text color="primary" @click="submit">登録</v-btn>
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
