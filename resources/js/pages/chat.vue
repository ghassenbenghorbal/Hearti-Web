<template>
<admin-layout>
    <div style="height:100%" class="d-flex">
        <div style="height:100%;width:30%;" class="px-1">
            <v-card outlined rounded="xl" style="height:100%;">
                <v-card-title>
                    Chats
                </v-card-title>
                <v-list class="py-0 mx-2" nav rounded>
                    <v-list-item-group v-model="selectedDiscussion" color="red">
                        <v-list-item v-for="(discussion, index) in discussions" :key="index">
                            <v-list-item-avatar>
                                <v-avatar :color="colors[index%10]" style="color:white">
                                    {{getFirstLetter(discussion.patient.name)}}
                                </v-avatar>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{ discussion.patient.name }}</v-list-item-title>
                                <v-list-item-subtitle class="d-flex"><div class=" text-truncate" style="max-width:80%;">{{ discussion.message.sender == "doctor" ? "You: " : ""}}{{ discussion.message.text }}</div> - <div>{{ formatDate(discussion.message.timestamp) }}</div></v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-card>
        </div>

        <div style="height:100%;" class="px-1 flex-grow-1">
            <v-card outlined rounded="xl" style="height:100%;">

            </v-card>
        </div>
    </div>
</admin-layout>
</template>

<script>
import AdminLayout from "../layouts/AdminLayout.vue";
export default {
    components: {
        AdminLayout
    },
    inject: {
        theme: {
            default: {
                isDark: false
            }
        }
    },
    props: {},
    data() {
        return {
            colors: [
                "red",
                "green",
                "blue",
                "purple",
                "orange",
                "indigo",
                "pink",
                "cyan",
                "teal",
                "brown",
                "grey",
            ],
            selectedDiscussion: null,
            discussions: [{
                patient: {
                    name: "Dali Spinoza"
                },
                message: {
                    text: "You are more likely to have a heart disease",
                    sender: "doctor",
                    timestamp: new Date(),
                }
            }, {
                patient: {
                    name: "Alexander The Great",
                },
                message: {
                    text: "You are less likely to have a heart disease",
                    sender: "doctor",
                    timestamp: new Date(),
                }
            }, {
                patient: {
                    name: "Christopher Colombus",
                },
                message: {
                    text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                    sender: "patient",
                    timestamp: new Date(),
                }
            }, ]
        };
    },
    methods: {
        getFirstLetter(name) {
            let nameSplit = name.split(' ')
            return nameSplit[0][0] + nameSplit[nameSplit.length - 1][0]
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return 'invalid date';
            }
            const now = new Date();
            const diffInMs = now - date;

            const minute = 60 * 1000;
            const hour = 60 * minute;
            const day = 24 * hour;
            const week = 7 * day;
            const month = 30 * day;
            const year = 365 * day;

            if (diffInMs < minute) {
                return 'just now';
            } else if (diffInMs < hour) {
                return `${Math.floor(diffInMs / minute)}m`;
            } else if (diffInMs < day) {
                return `${Math.floor(diffInMs / hour)}h`;
            } else if (diffInMs < week) {
                return `${Math.floor(diffInMs / day)}d`;
            } else if (diffInMs < month) {
                return `${Math.floor(diffInMs / week)}w`;
            } else if (diffInMs < year) {
                return `${Math.floor(diffInMs / month)}M`;
            } else {
                return `${Math.floor(diffInMs / year)}y`;
            }
        }
    },
    computed: {},
    watch: {},
    mounted() {},
    beforeDestroy() {}
};
</script>
