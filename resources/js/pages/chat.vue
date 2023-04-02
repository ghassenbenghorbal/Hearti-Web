<template>
<admin-layout>
    <div style="height:100%" class="d-flex">
        <div style="height:100%;width:30%;" class="px-1">
            <v-card outlined rounded="xl" style="height:100%;" class="" ref="chatCard">
                <v-card-title ref="chatCardTitle" class=" border-bottom">
                    Chats
                </v-card-title>
                <v-divider class=""></v-divider>
                <v-list class="py-0 mx-2" ref="discussionsList" nav rounded>
                    <v-list-item-group ref="discussionsListGroup" v-model="selectedDiscussion" color="error" class="pr-2" style="overflow-y: auto;">
                        <v-list-item v-for="(discussion, index) in discussions" :key="index">
                            <v-list-item-avatar>
                                <v-avatar :color="colors[index%10]" style="color:white">
                                    {{getFirstLetter(discussion.patient_name)}}
                                </v-avatar>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{ discussion.patient_name }}</v-list-item-title>
                                <v-list-item-subtitle class="d-flex">
                                    <div class=" text-truncate" style="max-width:80%;">{{ discussion.last_message.sender.id == $page.props.auth.user.id ? "You: " : ""}}{{ discussion.last_message.text }}</div>&nbsp;-<div>{{ formatDate(discussion.last_message.created_at) }}</div>
                                </v-list-item-subtitle>
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
            maxDiscussionsListHeight: "",
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
            discussions: [],
        };
    },
    methods: {
        async getDiscussions() {
            const resp = await axios.get(route('discussions',4))
                .catch(error => {
                    console.log(error)
                })
            return resp
        },
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
    async mounted() {
        this.maxDiscussionsListHeight = this.$refs.chatCard.$el.clientHeight - this.$refs.chatCardTitle.clientHeight - 10
        this.$refs.discussionsListGroup.$el.style.maxHeight = this.maxDiscussionsListHeight + "px"
        const resp = await this.getDiscussions()
        this.discussions = resp.data
    },
    beforeDestroy() {}
};
</script>
