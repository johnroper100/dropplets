<template>
    <v-app>
        <v-navigation-drawer v-model="drawer" :mini-variant="miniVariant" clipped fixed app disable-resize-watcher>
            <v-list>
                <v-list-item v-for="(item, i) in items" :key="i" :to="item.to" router exact>
                    <v-list-item-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title v-text="item.title" />
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click.stop="miniVariant = !miniVariant">
                    <v-list-item-action>
                        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content> Expand </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar clipped-left fixed app dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title v-text="title" />
            <v-spacer />
            <v-menu left bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn v-bind="attrs" color="secondary" v-on="on" v-if="isLoggedIn">
                        {{authUser.email}}
                    </v-btn>
                    <v-btn v-else color="secondary" nuxt to="/login">
                        Login
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item @click="logout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-main color="grey lighten-3">
            <v-container>
                <nuxt />
            </v-container>
        </v-main>
        <v-footer absolute app dark>
            <span>&copy; John Roper {{ new Date().getFullYear() }}</span>
        </v-footer>
    </v-app>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        data() {
            return {
                drawer: false,
                items: [
                    {
                        icon: 'mdi-apps',
                        title: 'Welcome',
                        to: '/',
                    },
                    {
                        icon: 'mdi-chart-bubble',
                        title: 'Inspire',
                        to: '/inspire',
                    },
                ],
                miniVariant: false,
                title: 'Home',
            }
        },
        computed: {
            authUser: {
                get() {
                    return this.$store.state.auth.authUser
                },
            },
            ...mapGetters({
                isLoggedIn: 'auth/isLoggedIn',
            }),
        },
        methods: {
            logout() {
            this.$store.dispatch('auth/signOut').then(() => {
                console.log('logged out 📴')
            })
            },
        },
    }
</script>