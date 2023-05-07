<template>
<admin-layout>
    <div style="position: absolute; inset: 0;" class="d-flex pa-3">
        <div :style="{position: 'relative', inset: 0, width: setDiscussionsCardWidth()}" :class="{'px-1': !$vuetify.breakpoint.xsOnly}">
            <v-card v-show="showDiscussionsCard" outlined rounded="xl" style="height: 100%;" class="pr-1" ref="chatCard">
                <v-card-title ref="chatCardTitle" class="py-2 d-flex justify-space-between">
                    <span>Chats</span>
                    <v-btn small fab rounded color="primary" dark @click="addDiscussion">
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>
                    <v-dialog v-model="addDiscussionDialog" :value="true" max-width="500px" scrollable>
                        <v-card>
                            <v-toolbar dense dark color="error" class="text-h6">
                                Add a new discussion
                            </v-toolbar>
                            <v-card-text class="pt-5 pb-0">
                                <v-autocomplete v-model="newDiscussionPatient" :items="patients" :item-value="null" item-text="name" label="Select a User" required filled outlined dense></v-autocomplete>
                            </v-card-text>
                            <v-card-actions class="d-flex justify-end">
                                <v-btn color="red" text dark @click="addDiscussionDialog = false">Close</v-btn>
                                <v-btn color="primary" text dark @click="createNewDiscussion">Create</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-card-title>
                <v-divider class=""></v-divider>
                <div v-resize="onContainerResize" ref="discussionsContainer" style="height:100%;overflow-y: auto;">
                    <v-list class="pt-1 pb-0 mx-2 flex-fill" ref="discussionsList" nav rounded>
                        <v-list-item-group ref="discussionsListGroup" v-model="selectedDiscussion" color="error" class="pr-2" mandatory>
                            <v-list-item v-for="(discussion, index) in discussions" :key="index" v-if="discussions && discussions[selectedDiscussion]" @click="showChatsMobile($vuetify.breakpoint.xsOnly)">
                                <v-list-item-avatar>
                                    <v-avatar :color="colors[index % 10]" style="color:white">
                                        {{ getFirstLetter(discussion.patient_name) }}
                                    </v-avatar>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title><span>{{discussion.patient_name}}</span>
                                        <v-icon v-if="discussion.connected" color="green">mdi-circle-medium</v-icon>
                                    </v-list-item-title>
                                    <v-list-item-subtitle class="d-flex" v-if="discussion.last_message">
                                        <div class=" text-truncate" style="max-width:80%;">
                                            {{discussion.last_message.sender.id == $page.props.auth.user.id ? "You: ": ""}}{{ discussion.last_message.text }}
                                        </div>
                                        &nbsp;-
                                        <div>
                                            {{ formatDate(discussion.last_message.created_at) }}
                                        </div>
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                            <div v-if="loadingDiscussions" ref="" class="d-flex flex-column-reverse pr-2">
                                <v-skeleton-loader v-for="i in numberOfListSkeletons" :key="i" type="list-item-avatar-two-line" class=""></v-skeleton-loader>
                            </div>
                        </v-list-item-group>
                    </v-list>
                </div>
            </v-card>
        </div>

        <div :style="{position: 'relative', inset: 0, width: setChatsCardWidth()}" :class="{'px-1': !$vuetify.breakpoint.xsOnly}">
            <v-card v-show="showChatsCard" outlined rounded="xl" style="height:100%;" ref="messageCard">
                <v-card-title ref="messageCardTitle" :class="{'py-2': $vuetify.breakpoint.xsOnly, 'py-3': !$vuetify.breakpoint.xsOnly}">
                    <v-btn class="mr-2" elevation="2" fab small v-if="$vuetify.breakpoint.xsOnly" @click="hideChatsCard()">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                    <div>{{discussions[selectedDiscussion]? discussions[selectedDiscussion].patient_name: "Messages"}}</div>
                    <v-icon v-if="discussions[selectedDiscussion] && discussions[selectedDiscussion].connected" color="green">mdi-circle-medium</v-icon>
                </v-card-title>
                <v-divider></v-divider>
                <div v-resize="onContainerResize" class="d-flex flex-column" style="height: 100%;" ref="messagesContainer">
                    <div class="flex-fill pt-1 px-lg-5 px-xl-5 px-md-5 px-0" style="height:100%; overflow-y: auto;" id="scrollContainer" ref="scrollContainer">
                        <v-list reverse class="py-0 mx-2" ref="messagesListGroup" rounded>
                            <div v-if="discussions && discussions[selectedDiscussion] && !loadingMessages" ref="" class="d-flex flex-column-reverse">
                                <v-list-item :class="{'ml-auto': discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].sender.id == $page.props.auth.user.id}" v-for="index in discussions[selectedDiscussion].messages.length" :key="index" :id="`message-${discussions[selectedDiscussion].messages.length - index}`" :ref="`message-${discussions[selectedDiscussion].messages.length - index}`" v-if="!loadingMessages">
                                    <v-list-item-avatar v-if="discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].sender.id != $page.props.auth.user.id">
                                        <v-avatar :color="colors[selectedDiscussion % 10]" style="color:white">
                                            {{getFirstLetter(discussions[selectedDiscussion].patient_name)}}
                                        </v-avatar>
                                    </v-list-item-avatar>
                                    <v-list-item-content>
                                        <v-list-item-title v-if="discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].sender.id != $page.props.auth.user.id">{{discussions[selectedDiscussion].patient_name}}</v-list-item-title>
                                        <v-list-item-subtitle class="d-flex">
                                            <v-chip class="text-wrap" style="height:fit-content" :color="discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].sender.id == $page.props.auth.user.id? 'red lighten-1': ''" :dark="discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].sender.id == $page.props.auth.user.id">
                                                <div class="" style="max-width:100%;">
                                                    {{ discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].text }}
                                                </div>
                                                <div :class="{'text-caption': true,'ml-3': true,'mt-3': true,'pr-1': true,'grey--text': true,'text--lighten-4': true,'text--darken-2':discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].sender.id != $page.props.auth.user.id}">
                                                    {{ formatMessageBubbleDate(discussions[selectedDiscussion].messages[discussions[selectedDiscussion].messages.length - index].created_at) }}
                                                </div>
                                            </v-chip>
                                        </v-list-item-subtitle>
                                    </v-list-item-content>
                                </v-list-item>
                            </div>
                            <div v-if="loadingMessages" ref="" class="d-flex flex-column-reverse pr-2">
                                <v-skeleton-loader v-for="i in numberOfListSkeletons" :key="i" type="list-item-avatar-two-line" class=""></v-skeleton-loader>
                            </div>
                        </v-list>
                    </div>

                    <div ref="sendMessageInput" v-show="discussions[selectedDiscussion]" class="mt-auto d-flex mx-auto py-3" style="width:80%">
                        <v-form v-if="discussions && discussions[selectedDiscussion]" @submit.prevent="sendMessage" ref="sendMessageForm" class="d-flex flex-grow-1">
                            <v-textarea v-model="newMessage" @keydown.enter.exact.prevent="sendMessage" hide-details placeholder="Type something..." outlined filled rounded dense auto-grow rows="1" max-rows="5" :rules="[v => v.length <= 255 || 'Max 255 characters']"></v-textarea>
                            <v-btn class="mx-3 align-self-end" color="primary" size="20" fab dark small type="submit" :loading="isSendingMessage">
                                <v-icon>mdi-send</v-icon>
                            </v-btn>
                        </v-form>
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
            showDiscussionsCard: true,
            showChatsCard: !this.$vuetify.breakpoint.xsOnly,

            newDiscussionPatient: null,
            addDiscussionDialog: false,
            patients: [],
            users: [],
            isConnected: false,
            socketMessage: '',
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
                "grey"
            ],
            selectedDiscussion: 0,
            loadingDiscussions: false,
            loadingMessages: false,
            discussions: [],
            messages: [],
            newMessage: "",
            isSendingMessage: false,
        };
    },
    sockets: {
        connect() {
            // Fired when the socket connects.
            this.isConnected = true;

        },

        disconnect() {
            this.isConnected = false;
        },

        connectionError(error) {
            console.log(error)
        },
        users(users) {
            users.forEach((user) => {
                user.self = user.channelID === this.$socket.id;
                this.initReactiveProperties(user, true);
            });
            // put the current user first, and then sort by username
            this.users = users.sort((a, b) => {
                if (a.self) return -1;
                if (b.self) return 1;
                if (a.username < b.username) return -1;
                return a.username > b.username ? 1 : 0;
            });
        },
        privateMessage(message) {
            let found = false;
            for (let i = 0; i < this.discussions.length; i++) {
                const user = this.discussions[i];
                if (user.channelID === message.from) {
                    found = true;
                    let m = message.content
                    this.discussions[this.selectedDiscussion].messages.push(m);
                    this.discussions[this.selectedDiscussion].last_message = m;
                    // if (user !== this.selectedUser) {
                    //     user.hasNewMessages = true;
                    // }
                    break;
                }
            }
            console.log("hello")
            if (!found) {
                console.log("found")
                this.discussions.push({
                    user_id: message.sender.id,
                    patient_name: message.sender.name,
                    messages: [m],
                    last_message: m,
                    connected: true,
                    channelID: message.from,
                })
            }
        },
        userConnected(user) {
            this.initReactiveProperties(user, true);
            this.users.push(user);
        },
        userDisconnected(user) {
            this.initReactiveProperties(user, false);
        },
    },
    methods: {
        setDiscussionsCardWidth() {
            if (this.showDiscussionsCard && this.showChatsCard) {
                return "30%"
            } else if (this.showDiscussionsCard && !this.showChatsCard) {
                return "100%"
            } else if (!this.showDiscussionsCard && this.showChatsCard) {
                return "0%"
            }
        },
        setChatsCardWidth() {
            if (this.showDiscussionsCard && this.showChatsCard) {
                return "70%"
            } else if (this.showDiscussionsCard && !this.showChatsCard) {
                return "0%"
            } else if (!this.showDiscussionsCard && this.showChatsCard) {
                return "100%"
            }
        },
        showChatsMobile() {
            if (this.$vuetify.breakpoint.xsOnly) {
                this.showDiscussionsCard = false;
                this.showChatsCard = true;
                this.onContainerResize()
            }
        },
        hideChatsCard() {
            if (this.$vuetify.breakpoint.xsOnly) {
                this.showDiscussionsCard = true;
                this.showChatsCard = false;
                this.onContainerResize()
            }
        },
        createNewDiscussion() {
            this.discussions.push({
                user_id: this.newDiscussionPatient.id,
                patient_name: this.newDiscussionPatient.name,
                messages: [],
                last_message: '',
                connected: false,
                channelID: '',
            })
            this.addDiscussionDialog = false;
        },
        async addDiscussion() {
            this.addDiscussionDialog = true;
            await axios.get(route('patient-users')).then(response => {
                this.patients = response.data;
            }).catch(error => {
                console.log(error);
            });
        },
        async initReactiveProperties(user, state) {
            //search in discussions if user is already there
            let discussion = this.discussions.find(discussion => discussion.user_id == user.username);
            if (discussion) {
                if (state) {
                    discussion.channelID = user.channelID;
                }
                discussion.connected = state;
            }
        },
        async sendMessageRequest() {
            const resp = await axios
                .post(
                    route("messages.store"), {
                        sender: this.$page.props.auth.user.id,
                        receiver: this.discussions[this.selectedDiscussion].user_id,
                        text: this.newMessage,
                    }
                )
                .catch(error => {
                    this.isSendingMessage = false;
                    console.log(error);
                });
        },
        sendMessage() {
            this.isSendingMessage = true;
            if (this.newMessage.length > 0 && this.newMessage.length <= 255 && this.selectedDiscussion >= 0) {

                let m = {
                    sender: {
                        id: this.$page.props.auth.user.id,
                        name: this.$page.props.auth.user.name,
                    },
                    receiver: {
                        id: this.discussions[this.selectedDiscussion].user_id,
                        name: this.discussions[this.selectedDiscussion].patient_name,
                    },
                    text: this.newMessage,
                    created_at: new Date(),
                }

                this.discussions[this.selectedDiscussion].messages.push(m);
                this.discussions[this.selectedDiscussion].last_message = m;
                this.isSendingMessage = false;
                this.onContainerResize();

                if (this.discussions[this.selectedDiscussion].channelID) {
                    this.$socket.emit("privateMessage", {
                        content: m,
                        to: this.discussions[this.selectedDiscussion].channelID
                    });
                }
                this.sendMessageRequest();
                this.newMessage = "";
            }
        },
        async getMessages() {
            if (this.discussions[this.selectedDiscussion]) {
                this.loadingMessages = true;
                await axios
                    .get(
                        route("messages", [
                            this.$page.props.auth.user.id,
                            this.discussions[this.selectedDiscussion].user_id
                        ])
                    )
                    .then(resp => {
                        this.discussions[this.selectedDiscussion].messages = resp.data;
                        this.loadingMessages = false;
                    })
                    .catch(error => {
                        this.loadingMessages = false
                        console.log(error);
                    });
            }
        },
        async getDiscussions() {
            this.loadingDiscussions = true;
            this.loadingMessages = true;
            await axios
                .get(route("discussions", this.$page.props.auth.user.id))
                .then(response => {
                    this.discussions = response.data;
                    this.loadingDiscussions = false;
                    this.loadingMessages = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loadingDiscussions = false;
                    this.loadingMessages = false;
                });
        },
        getFirstLetter(name) {
            let nameSplit = name.split(" ");
            return nameSplit[0][0] + nameSplit[nameSplit.length - 1][0];
        },
        formatMessageBubbleDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return "invalid date";
            }
            let time = date.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit"
            });
            let dateStr = date.toLocaleDateString([], {
                year: "numeric",
                month: "short",
                day: "numeric"
            });
            return `${time} ${dateStr}`;
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return "invalid date";
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
                return "just now";
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
        onContainerResize() {
            this.maxDiscussionsListHeight =
                this.$refs.chatCard.$el.clientHeight -
                this.$refs.chatCardTitle.clientHeight -
                10;
            this.$refs.discussionsContainer.style.maxHeight =
                this.maxDiscussionsListHeight + "px";

            this.maxMessagesHeight =
                this.$refs.messageCard.$el.clientHeight -
                this.$refs.messageCardTitle.clientHeight -
                10;

            this.$refs.messagesContainer.style.maxHeight = this.maxMessagesHeight + "px";

            const discussionsContentHeight = this.$refs.discussionsList.$el
                .clientHeight;
            const discussionsContainerHeight = this.$refs.discussionsContainer
                .clientHeight;

            const messagesContentHeight = this.$refs.messagesListGroup.$el
                .clientHeight;
            const messagesContainerHeight = this.$refs.messagesContainer.clientHeight - this.$refs.sendMessageInput.clientHeight;

            if (discussionsContentHeight > discussionsContainerHeight) {
                this.$refs.discussionsList.$el.style.maxHeight = `${discussionsContainerHeight}px`;
            }
            if (messagesContentHeight > messagesContainerHeight) {
                this.$refs.messagesListGroup.$el.style.maxHeight = `${messagesContainerHeight}px`;
            }
        },
        scrollDown() {
            this.$refs.scrollContainer.scrollTop = this.$refs.scrollContainer.scrollHeight;
        },
        pingServer() {
            // Send the "pingServer" event to the server.
            this.$socket.emit('pingServer', 'PING!')
        },
        async connectToSocket() {
            this.$socket.auth = {
                username: this.$page.props.auth.user.id
            };
            await this.$socket.connect();
        },
    },
    computed: {
        numberOfListSkeletons() {
            let screenHeight = window.innerHeight.toString();
            let numberOfListSkeletons = parseInt(screenHeight[0]);
            return numberOfListSkeletons;
        },
    },
    watch: {
        '$vuetify.breakpoint.xsOnly'(val) {
            if (val) {
                this.showDiscussionsCard = true;
                this.showChatsCard = false;
            } else {
                this.showDiscussionsCard = true;
                this.showChatsCard = true;
            }
        },
        selectedDiscussion() {
            this.getMessages()
        },
    },
    created() {},
    updated() {
        this.scrollDown();
        this.onContainerResize();
    },
    mounted() {
        this.getDiscussions();
        this.connectToSocket();
        this.onContainerResize();

    },
    beforeDestroy() {
        this.$socket.disconnect();
    }
};
</script>

<style>
.v-skeleton-loader__bone {
    padding-left: 8px;
}
</style>
