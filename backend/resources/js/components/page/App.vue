<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app>
            <div class="v-list v-sheet theme--light v-list--dense v-list--nav">
                <div class="v-list v-sheet theme--light v-list--dense v-list--nav">
                    ダッシュボード
                </div>
                <div class="v-list v-sheet theme--light v-list--dense v-list--nav">
                    記録作成
                </div>
                <div class="v-list v-sheet theme--light v-list--dense v-list--nav">
                    ユーザー登録
                </div>                                
            </div>
        </v-navigation-drawer>

        <v-app-bar app>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

            <v-toolbar-title>Application</v-toolbar-title>

        </v-app-bar>
        <v-main>
            <v-container>
                <router-view />
            </v-container>
        </v-main>
    </v-app>
</template>

<script>


    export default {
        data: () => ({
            drawer: null,

            items: ['foo', 'bar', 'fizz', 'buzz'],
            values: ['foo', 'bar'],
            value: null,
        }),
    }

</script>
