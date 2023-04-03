<template>
<v-app style="background-color: #f5f5f5">
    <!-- Tablet and below -->
    <v-navigation-drawer style="z-index:100" :mini-variant.sync="miniVariant" v-model="drawer" right temporary app>
        <v-list dense nav>
            <v-list-item>
                <v-list-item-title class="text-center"><span class="button text-uppercase font-weight-light">
                        <v-avatar color="red lighten-2" size="32">
                            <span style="color:white">{{$page.props.auth.user.name[0]}}</span>
                        </v-avatar>
                        {{$page.props.auth.user.name}}
                    </span></v-list-item-title>
            </v-list-item>
            <v-list-item-group :value="indexMenu">
                <v-list-item color="red" v-for="(item, i) in items" :key="i" @click="goToPage(item.to)">
                    <v-list-item-content>
                        <v-list-item-title style="font-family: 'Poppins', sans-serif;" class="font-weight-medium text-center">{{item.title}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>
        </v-list>
        <v-list>
            <v-list-item>
                <v-list-item-title class="text-center">
                    <v-btn small rounded dark color="red lighten-1" @click="logout()">
                        Log Out
                        <v-icon style="padding-bottom:1px" right>mdi-logout</v-icon>
                    </v-btn>
                </v-list-item-title>
            </v-list-item>
        </v-list>

    </v-navigation-drawer>
    <!-- Tablet and below -->
    <v-app-bar color="white" elevation="1" app style="z-index:50">
        <!-- Tablet and below -->
        <v-container fluid class="py-0 fill-height hidden-md-and-up">
            <v-avatar class="mr-10" color="red lighten-4" size="32">
                <v-icon color="red">mdi-hospital</v-icon>
            </v-avatar>
            <v-spacer></v-spacer>
            <v-app-bar-nav-icon style="color:red" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        </v-container>
        <!-- Laptop and above -->
        <v-container fluid class="py-0 fill-height hidden-sm-and-down">
            <v-avatar class="mr-10" color="red lighten-4" size="32">
                <v-icon large color="red">mdi-hospital</v-icon>
            </v-avatar>

            <v-btn v-for="(item, i) in items" :key="i" @click="goToPage(item.to)" class="mr-1" v-text="item.title" rounded small :text="route().current() == item.to ? false : true" :color="route().current() == item.to ? 'error' : ''" :dark="route().current() == item.to ? true : false" :elevation="route().current() == item.to ? 1 : 0" />

            <v-spacer></v-spacer>
            <div>
                <v-badge class="mr-8" :content="3" :value="3" overlap dark>
                    <v-icon class="notification-bell" color="red lighten-1">
                        mdi-bell
                    </v-icon>
                </v-badge>
            </div>
            <v-menu offset-y rounded="lg" transition="scale-transition" origin="center center" style="z-index:1000">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn class="ml-4" small rounded dark color="red lighten-1" v-bind="attrs" v-on="on">
                        {{$page.props.auth.user.name}}
                        <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                </template>
                <v-list dense min-width="200" class="py-0">
                    <v-subheader class="pl-4">Manage Account</v-subheader>
                    <v-list-item link @click="profile()">
                        <v-list-item-content>
                            <v-list-item-title>Profile</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon style="padding-right:2px">
                            <v-icon>mdi-account</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item link @click="switchDarkMode()">
                        <v-list-item-content>
                            <v-list-item-title>{{darkMode ? "Light Mode" : "Dark Mode"}}</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon style="padding-right:2px">
                            <v-icon>{{darkMode ? "mdi-weather-sunny" : "mdi-weather-night"}}</v-icon>
                        </v-list-item-icon>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item link @click="logout()">
                        <v-list-item-content>
                            <v-list-item-title>Log Out</v-list-item-title>
                        </v-list-item-content>
                        <v-list-item-icon>
                            <v-icon>mdi-logout</v-icon>
                        </v-list-item-icon>

                    </v-list-item>
                </v-list>
            </v-menu>
        </v-container>
    </v-app-bar>
    <v-main>
        <v-container fluid style="height:100%">
            <slot />
        </v-container>
    </v-main>
</v-app>
</template>

<script>
import ApplicationLogo from "../components/ApplicationLogo.vue";
import {
    enable as enableDarkMode,
    disable as disableDarkMode,
    auto as followSystemColorScheme,
    exportGeneratedCSS as collectCSS,
    isEnabled as isDarkReaderEnabled
} from 'darkreader';
export default {
    components: {
        ApplicationLogo
    },
    data() {
        return {
            darkMode: isDarkReaderEnabled(),
            drawer: false,
            items: [{
                    title: "Home",
                    to: "home"
                },
                {
                    title: "Patients",
                    to: "patient.index"
                },
                {
                    title: "Analysis",
                    to: "analysis"
                },
                {
                    title: "Chat",
                    to: "chat"
                },
            ],
            miniVariant: false,
        };
    },
    computed: {
        appName() {
            return this.$page.props.appName;
        },
        user() {
            return this.$page.props.auth.user;
        },
        indexMenu() {
            const inertiaUrl = this.$inertia.page.url.split("?")[0];
            const index = this.items.findIndex((item) => {
                const windowUrl = this.route(item.to);
                return windowUrl.includes(inertiaUrl);
            });
            return index;
        },
    },
    watch: {
        $page: {
            handler() {
                const message = this.$page.props.flash.message;
                if (message != null) {
                    switch (message.type) {
                        case "success":
                            this.$toast.success(message.text);
                            break;
                        case "error":
                            this.$toast.error(message.text);
                            break;
                    }
                }
            },
        },
    },
    methods: {
        switchDarkMode() {
            this.darkMode ? disableDarkMode() : enableDarkMode({
                                                    brightness: 100,
                                                    contrast: 90,
                                                    sepia: 10,
                                                });
            this.darkMode = !this.darkMode
        },
        profile() {
            this.$inertia.get('/profile')
        },
        logout() {
            this.$inertia.post("/logout");
        },
        goToPage(page) {
            this.$inertia.visit(this.route(page));
        },
        trimRouteName(route) {
            return route.split(".")[0]
        }
    },
};
</script>

<style>
body,
html {
    margin: 0px !important;
}

*::-webkit-scrollbar {
    width: 16px;
}

*::-webkit-scrollbar-track {
    border-radius: 8px;
    background-color: white;
}

*::-webkit-scrollbar-thumb {
    height: 56px;
    border-radius: 8px;
    border: 4px solid transparent;
    background-clip: content-box;
    background-color: #888;
}

*::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}
</style>
