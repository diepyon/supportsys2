<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app>
            <v-list>
                <v-list-item v-for="[icon, text,to] in links" :key="icon" :to="to" link>
                    <v-list-item-icon>
                        <v-icon>{{ icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ text }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
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
            links: [
                ['mdi-home', 'ダッシュボード', '/'],
                ['mdi-file-document-multiple-outline', '受付記録', '/archive'],
                ['mdi-lead-pencil', '記録入力', '/recordpost'],
                ['mdi-account', 'ユーザー登録', '/users'],
                ['mdi-alpha-p-box', '機種登録', '/types'],
            ],
        }),
    }

</script>
<style scoped>
    main.v-main {
        background: #E0F2F1;
    }

    .v-navigation-drawer {
        background-color: #009688 !important;
    }

    a.v-list-item--link.theme--light {
        color: white !important;
    }

    i.v-icon.notranslate.mdi.theme--light {
        color: white;
    }

    i.v-icon.notranslate.mdi.mdi-menu.theme--light {
        color: black;
    }

</style>
