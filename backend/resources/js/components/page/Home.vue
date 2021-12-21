<template>
    <div>
        <h1>aho</h1>
        <p>プルダウンテスト</p>
        <v-autocomplete v-model="value" :items="items" dense filled label="機種名"></v-autocomplete>
        <v-alert type="success">通知</v-alert>
    </div>
</template>

<script>

</script>
