<template>
  <admin-layout>
    <div style="position: absolute; inset: 0;" class="d-flex pa-3">
      <div
        style="position: relative; inset: 0;width:30%;"
        class="px-1 d-flex flex-column"
      >
        <v-card
          outlined
          rounded="xl"
          style="height: 100%;"
          class="pr-1"
          ref="chatCard"
        >
          <v-card-title ref="chatCardTitle" class="py-2">
            Chats
          </v-card-title>
          <v-divider class=""></v-divider>
          <div
            v-resize="onContainerResize"
            ref="discussionsContainer"
            style="height:100%;overflow-y: auto;"
          >
            <v-list
              class="pt-1 pb-0 mx-2 flex-fill"
              ref="discussionsList"
              nav
              rounded
            >
              <v-list-item-group
                ref="discussionsListGroup"
                v-model="selectedDiscussion"
                color="error"
                class="pr-2"
              >
                <v-list-item
                  v-for="(discussion, index) in discussions"
                  :key="index"
                  v-if="!loadingDiscussions"
                >
                  <v-list-item-avatar>
                    <v-avatar :color="colors[index % 10]" style="color:white">
                      {{ getFirstLetter(discussion.patient_name) }}
                    </v-avatar>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{
                      discussion.patient_name
                    }}</v-list-item-title>
                    <v-list-item-subtitle class="d-flex">
                      <div class=" text-truncate" style="max-width:80%;">
                        {{
                          discussion.last_message.sender.id ==
                          $page.props.auth.user.id
                            ? "You: "
                            : ""
                        }}{{ discussion.last_message.text }}
                      </div>
                      &nbsp;-
                      <div>
                        {{ formatDate(discussion.last_message.created_at) }}
                      </div>
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
                <v-skeleton-loader
                  v-if="loadingDiscussions"
                  v-for="i in numberOfListSkeletons"
                  :key="i"
                  type="list-item-avatar-two-line"
                  class=""
                ></v-skeleton-loader>
              </v-list-item-group>
            </v-list>
          </div>
        </v-card>
      </div>

      <div style="position: relative; inset: 0;" class="px-1 flex-grow-1">
        <v-card outlined rounded="xl" style="height:100%;">
          <v-card-title class="py-2">
            {{
              discussions[selectedDiscussion]
                ? discussions[selectedDiscussion].patient_name
                : "Messages"
            }}
          </v-card-title>
          <v-divider></v-divider>
          <div
            class="d-flex flex-column"
            style="height: 100%;"
            ref="messagesContainer"
          >
            <div
              class="flex-fill pt-1 px-5"
              style="height:100%; overflow-y: auto;"
            >
              <v-list reverse class="py-0 mx-2" ref="messagesListGroup" rounded>
                <div
                  ref=""
                  class="d-flex flex-column-reverse pr-2"
                  style="overflow-y: auto;"
                >
                  <v-list-item
                    :class="{
                      'ml-auto': message.sender.id == $page.props.auth.user.id
                    }"
                    v-for="(message, index) in messages"
                    :key="index"
                    v-if="!loadingMessages"
                  >
                    <v-list-item-avatar
                      v-if="message.sender.id != $page.props.auth.user.id"
                    >
                      <v-avatar :color="colors[index % 10]" style="color:white">
                        {{
                          getFirstLetter(
                            discussions[selectedDiscussion].patient_name
                          )
                        }}
                      </v-avatar>
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title
                        v-if="message.sender.id != $page.props.auth.user.id"
                        >{{
                          discussions[selectedDiscussion].patient_name
                        }}</v-list-item-title
                      >
                      <v-list-item-subtitle class="d-flex">
                        <v-chip
                          :color="
                            message.sender.id == $page.props.auth.user.id
                              ? 'red lighten-1'
                              : ''
                          "
                          :dark="message.sender.id == $page.props.auth.user.id"
                          ><div class="" style="max-width:100%;">
                            {{ message.text }}
                          </div>
                          <div
                            :class="{
                              'text-caption': true,
                              'ml-3': true,
                              'mt-3': true,
                              'pr-1': true,
                              'grey--text': true,
                              'text--lighten-4': true,
                              'text--darken-2':
                                message.sender.id != $page.props.auth.user.id
                            }"
                          >
                            {{ formatMessageBubbleDate(message.created_at) }}
                          </div></v-chip
                        >
                      </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  <v-skeleton-loader
                    v-if="loadingMessages"
                    v-for="i in numberOfListSkeletons"
                    :key="i"
                    type="list-item-avatar-two-line"
                    class=""
                  ></v-skeleton-loader>
                </div>
              </v-list>
            </div>

            <div
              ref="sendMessageInput"
              v-show="discussions[selectedDiscussion]"
              class="mt-auto d-flex mx-auto py-3"
              style="width:80%"
            >
              <v-text-field
                hide-details
                placeholder=" "
                outlined
                filled
                rounded
                dense
              ></v-text-field>
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
        "grey"
      ],
      selectedDiscussion: 0,
      loadingDiscussions: true,
      loadingMessages: true,
      discussions: [],
      messages: []
    };
  },
  methods: {
    async getMessages() {
      const resp = await axios
        .get(
          route("messages", [
            this.$page.props.auth.user.id,
            this.discussions[this.selectedDiscussion].user_id
          ])
        )
        .catch(error => {
          console.log(error);
        });
      return resp;
    },
    async getDiscussions() {
      const resp = await axios
        .get(route("discussions", this.$page.props.auth.user.id))
        .catch(error => {
          console.log(error);
        });
      return resp;
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

      this.$refs.messagesContainer.style.maxHeight = this.$refs.discussionsContainer.style.maxHeight;

      const discussionsContentHeight = this.$refs.discussionsList.$el
        .clientHeight;
      const discussionsContainerHeight = this.$refs.discussionsContainer
        .clientHeight;

      const messagesContentHeight = this.$refs.messagesListGroup.$el
        .clientHeight;
      const messagesContainerHeight =
        this.$refs.messagesContainer.clientHeight -
        this.$refs.sendMessageInput.clientHeight;

      if (discussionsContentHeight > discussionsContainerHeight) {
        this.$refs.discussionsList.$el.style.maxHeight = `${containerHeight}px`;
      }
      if (messagesContentHeight > messagesContainerHeight) {
        this.$refs.messagesListGroup.$el.style.maxHeight = `${messagesContainerHeight}px`;
      }
      this.$refs.messagesListGroup.scrollTop =
        this.$refs.messagesListGroup.scrollHeight -
        this.$refs.messagesListGroup.clientHeight;
    }
  },
  computed: {
    numberOfListSkeletons() {
      let screenHeight = window.innerHeight.toString();
      let numberOfListSkeletons = parseInt(screenHeight[0]);
      return numberOfListSkeletons;
    }
  },
  watch: {
    selectedDiscussion() {
      this.loadingMessages = true;
      this.getMessages().then(resp => {
        this.messages = resp.data;
        this.loadingMessages = false;
      });
    }
  },
  created() {},
  async mounted() {
    const resp = await this.getDiscussions();
    this.discussions = resp.data;
    const resp2 = await this.getMessages();
    this.messages = resp2.data;
    this.loadingDiscussions = false;
    this.loadingMessages = false;
  },
  beforeDestroy() {}
};
</script>

<style>
.v-skeleton-loader__bone {
  padding-left: 8px;
}
</style>
