<template>
<admin-layout>
    <div style="height:100%" class="d-flex">
        <div style="height:100%;width:30%;" class="px-1">
            <v-card outlined rounded="xl" style="height:100%;" class="pb-1" ref="chatCard">
                <v-card-title ref="chatCardTitle" class="py-2">
                    Chats
                </v-card-title>
                <v-divider class=""></v-divider>
                <v-list class="pt-1 pb-0 mx-2" ref="discussionsList" nav rounded>
                    <v-list-item-group ref="discussionsListGroup" v-model="selectedDiscussion" color="error" class="pr-2" style="overflow-y: auto;">
                        <v-list-item v-for="(discussion, index) in discussions" :key="index" v-if="!loadingDiscussions">
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
                        <v-skeleton-loader v-if="loadingDiscussions" v-for="i in numberOfListSkeletons" :key="i" type="list-item-avatar-two-line" class=""></v-skeleton-loader>
                    </v-list-item-group>
                </v-list>
            </v-card>
        </div>

        <div style="height:100%;" class="px-1 flex-grow-1">
            <v-card outlined rounded="xl" style="height:100%;">
                <v-card-title class="py-2">
                    {{ discussions[selectedDiscussion] ? discussions[selectedDiscussion].patient_name : "Messages" }}
                </v-card-title>
                <v-divider></v-divider>
                <div ref="messagesContainer" class="d-flex flex-column" style="">

                    <div class="flex-fill pt-1 px-5">
                        <v-list reverse class="py-0 mx-2" ref="discussionsList" rounded>
                            <div ref="messagesListGroup" class="d-flex flex-column-reverse pr-2" style="overflow-y: auto;">
                                <v-list-item class="my-4" v-for="(discussion, index) in discussions" :key="index" v-if="!loadingDiscussions">
                                    <v-list-item-avatar>
                                        <v-avatar :color="colors[index%10]" style="color:white">
                                            {{getFirstLetter(discussion.patient_name)}}
                                        </v-avatar>
                                    </v-list-item-avatar>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ discussion.patient_name }}</v-list-item-title>
                                        <v-list-item-subtitle class="d-flex">
                                            <v-chip><div class="" style="max-width:100%;">{{ discussion.last_message.sender.id == $page.props.auth.user.id ? "You: " : ""}}{{ discussion.last_message.text }}</div>&nbsp;-<div>{{ formatDate(discussion.last_message.created_at) }}</div></v-chip>
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-list-item class="ml-auto" v-for="(discussion, index) in discussions" :key="index" v-if="!loadingDiscussions">
                                    <v-list-item-content>
                                        <v-list-item-subtitle class="d-flex">
                                            <v-chip color="red lighten-1" dark><div class="" style="max-width:100%;">{{ discussion.last_message.sender.id == $page.props.auth.user.id ? "You: " : ""}}{{ discussion.last_message.text }}</div>&nbsp;-<div>{{ formatDate(discussion.last_message.created_at) }}</div></v-chip>
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-skeleton-loader v-if="loadingDiscussions" v-for="i in numberOfListSkeletons" :key="i" type="list-item-avatar-two-line" class=""></v-skeleton-loader>
                            </div>
                        </v-list>
                    </div>

                    <div ref="sendMessageInput" v-show="discussions[selectedDiscussion]" class="mt-auto d-flex mx-auto py-3" style="width:80%">
                        <v-text-field hide-details placeholder=" " outlined filled rounded dense></v-text-field>
                        <v-btn class="mx-3" color="primary" size="20" fab dark small>
                            <v-icon>mdi-send</v-icon>
                        </v-btn>
                    </div>

                </div>
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
            selectedDiscussion: 0,
            loadingDiscussions: true,
            discussions: [],
        };
    },
    methods: {
        async getMessages() {
            const resp = await axios.get(route('messages', 4))
                .catch(error => {
                    console.log(error)
                })
            return resp
        },
        async getDiscussions() {
            const resp = await axios.get(route('discussions', this.$page.props.auth.user.id))
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
        },
        handleResize() {
            this.maxDiscussionsListHeight = this.$refs.chatCard.$el.clientHeight - this.$refs.chatCardTitle.clientHeight - 10
            this.$refs.discussionsListGroup.$el.style.maxHeight = this.maxDiscussionsListHeight + "px"

            this.$refs.messagesContainer.style.maxHeight = this.$refs.discussionsListGroup.$el.style.maxHeight
            this.$refs.messagesContainer.style.height = this.$refs.discussionsListGroup.$el.style.maxHeight

            this.$refs.messagesListGroup.style.maxHeight = (this.maxDiscussionsListHeight - this.$refs.sendMessageInput.clientHeight) + "px"
        },
    },
    computed: {
        numberOfListSkeletons() {
            let screenHeight = window.innerHeight.toString();
            let numberOfListSkeletons = parseInt(screenHeight[0])
            return numberOfListSkeletons
        }
    },
    created() {
        window.addEventListener("resize", this.handleResize);
    },
    async mounted() {
        const resp = await this.getDiscussions()
        this.discussions = resp.data
        this.loadingDiscussions = false

        this.handleResize();
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.handleResize);
    }
};
</script>

<style>
.v-skeleton-loader__bone {
    padding-left: 8px;
}
</style>
