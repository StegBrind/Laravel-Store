<template>
    <v-app>
        <nav>
            <v-app-bar app dark>
                <v-app-bar-nav-icon @click="drawer = !drawer">
                    <v-icon class="blue--text text--lighten-5">menu</v-icon>
                </v-app-bar-nav-icon>
            </v-app-bar>
            <v-navigation-drawer app dark v-model="drawer" width="270">
                <v-list>

                    <v-list-item>
                        <v-list-item-content>
                            <span class="blue--text text--lighten-5 headline text-center mt-n1">Админ Меню</span>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider class="blue lighten-5 mb-3 mt-1"/>

                    <v-list-item v-for="link in links" :key="link.text" router :to="link.route">
                        <v-list-item-action>
                            <v-icon>{{ link.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{ link.text }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider class="blue lighten-5 mt-3"/>

                    <v-list-item>
                        <v-list-item-content>
                            <v-btn class="white red--text">Выйти</v-btn>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
        </nav>
        <v-content class="mx-4 my-3">
            <router-view/>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {
                drawer: true,
                links: [
                    { icon: 'dashboard', text: 'Главная', route: 'dashboard' },
                    { icon: 'group', text: 'Список пользователей', route: 'users' },
                    { icon: 'perm_identity', text: 'Список админов', route: 'admins' },
                    { icon: 'view_comfy', text: 'Список категорий', route: 'categories' }
                ]
            }
        }
    }
</script>
