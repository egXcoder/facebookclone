<template>
  <div class="messenger">
    <div class="header">
      <div class="details">
        <img :src="chatWith.image_url" alt="" />
        <span>{{ chatWith.name }}</span>
      </div>
      <div class="actions">
        <i class="fas fa-video"></i>
        <i class="fas fa-phone-alt"></i>
        <i class="fas fa-window-minimize" @click="$emit('cache-user', chatWith)"></i>
        <i class="fas fa-times" @click="$emit('close')"></i>
      </div>
    </div>
    <div class="messages" ref="messages">
      <template v-if="showSpinner">
        <div class="spinner"></div>
      </template>
      <template v-else>
        <template v-if="messages">
          <div v-for="(message, index) in messages" :key="index">
            <p class="show-date" v-if="message.show_date">{{ message.show_date }}</p>
            <template v-if="isMessageSentByLoggedUser(message)">
              <div class="sent">{{ message.text }}</div>
            </template>
            <template v-else>
              <div class="received">
                <img :src="chatWith.image_url" />
                <div>{{ message.text }}</div>
              </div>
            </template>
          </div>
        </template>
        <template v-else>
          <div class="no-messages-yet">
            <img :src="chatWith.image_url" alt="" />
            <p>{{ chatWith.name }}</p>
            <span>Facebook</span>
            <span>You're friends on Facebook</span>
          </div>
        </template>
      </template>
    </div>
    <div class="write-message">
      <div contenteditable @keyup.enter.exact="sendMessage($event)" data-placeholder="Aa"></div>
    </div>
  </div>
</template>

<script>
import { bus } from "../../app";

export default {
  props: {
    chatWith: {
      type: Object,
    },
  },
  data() {
    return {
      showSpinner: false,
      next_link: null,
    };
  },
  computed: {
    messages() {
      return this.$store.state.User.messagesMap[this.chatWith.id];
    },
  },
  created() {
    this.fetchMessagesFirstTime();
  },
  mounted() {
    this.loadMoreOnScroll();
    this.listenOnMessageSent();
  },
  methods: {
    launchFetching(link) {
      if (!link) {
        return;
      }

      return window.axios.get(link);
    },
    fetchMessagesFirstTime() {
      this.showSpinner = true;
      this.launchFetching(`/api/user/${this.chatWith.id}/messages`).then((response) => {
        this.$store.commit("User/setMessages", {
          user: this.chatWith,
          messages: response.data.data,
        });
        this.next_link = response.data.links.next;
        this.showSpinner = false;
        this.scrollBottom();
      });
    },
    scrollBottom() {
      this.$nextTick(() => {
        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight + 100;
      });
    },
    loadMoreOnScroll() {
      //on scroll at the top of the div, load more if next_link is available
      this.$refs.messages.addEventListener("scroll", (e) => {
        if (e.target.scrollTop == 0) {
          this.fetchMessagesToLoadMore();
        }
      });

      this.$on("hooks:beforeDestroy", () => {
        this.$refs.messages.removeEventListener("scroll");
      });
    },
    fetchMessagesToLoadMore() {
      if (!this.next_link) {
        return;
      }

      this.launchFetching(this.next_link).then((response) => {
        this.$store.commit("User/loadMoreMessages", {
          user: this.chatWith,
          messages: response.data.data,
        });
        this.next_link = response.data.links.next;
        this.avoidRescrolling();
      });
    },
    avoidRescrolling() {
      let scrollHeight = this.$refs.messages.scrollHeight;
      this.$nextTick(() => {
        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight - scrollHeight;
      });
    },
    isMessageSentByLoggedUser(message) {
      return message.sender_id == this.$store.state.User.me.id;
    },
    sendMessage($event) {
      let message = {
        text: $event.target.innerText.trim(),
        sender_id: this.$store.state.User.me.id,
        receiver_id: this.chatWith.id,
        show_date: false,
      };

      this.$store.commit("User/addMessage", {
        user: this.chatWith,
        message,
      });

      this.scrollBottom();

      $event.target.innerText = "";

      window.axios.post(`/api/user/${this.chatWith.id}/messages`, { text: message.text });
    },
    listenOnMessageSent() {
      bus.$on("MessageSent", (e) => {
        //make sure message sent belong to the person
        if (e.sender.id != this.chatWith.id) {
          return;
        }

        this.playNotificationSound();

        //push message to user chat
        this.$store.commit("User/addMessage", {
          user: e.sender,
          message: e.message,
        });

        this.scrollBottom();
      });

      this.$on("hooks:beforeDestroy", () => {
        bus.$off("MessageSent");
      });
    },
    playNotificationSound() {
      new Audio("/audio/message.mp3").play();
    },
  },
};
</script>

<style lang="scss" scoped>
.messenger {
  margin-left: 7px;
  margin-right: 7px;
  background: white;
  width: 320px;
  border-radius: 1rem;
  .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important;
    padding: 7px;
    .details {
      display: flex;
      align-items: center;
      img {
        height: 40px;
        border-radius: 50%;
        margin: 0px 7px;
      }
      span {
        width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
    }
    .actions {
      display: flex;
      font-size: 1.1rem;
      color: var(--primary-color);
      i {
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 50%;
        padding: 7px;
        display: flex;
        justify-content: center;
        align-items: center;
        &:hover {
          background: var(--grayed);
        }
      }
    }
  }
  .messages {
    display: flex;
    flex-flow: column;
    height: 300px;
    overflow: auto;
    padding: 0 1rem;
    position: relative;
    .show-date {
      text-align: center;
      color: gray;
      font-size: 11px;
      margin-top: 5px;
      margin-bottom: 5px;
    }
    .sent {
      margin: 5px 0px;
      text-align: right;
      background: var(--primary-color);
      padding: 7px;
      color: white;
      border-radius: 1rem;
      white-space: pre-wrap;
    }
    .received {
      margin: 5px 0px;
      display: flex;
      align-items: flex-end;
      img {
        border-radius: 50%;
        height: 30px;
        margin: 0px 5px;
      }
      div {
        background: #e5e4e4;
        padding: 7px;
        border-radius: 1rem;
        white-space: pre-wrap;
      }
    }
    .no-messages-yet {
      display: flex;
      flex-flow: column;
      align-items: center;
      img {
        margin-top: 1rem;
        border-radius: 100%;
        height: 50px;
      }
      p {
        margin: 0.5rem 0rem;
        font-size: 1rem;
        font-weight: bold;
      }
      span {
        color: gray;
      }
    }
  }
  .write-message {
    [contenteditable="true"] {
      display: flex;
      padding: 1rem;
      max-height: 100px;
      overflow: auto;
      background: var(--grayed);
      outline: none;
      border: 0px;
      &:empty::before {
        content: attr(data-placeholder);
      }
    }
  }
  .spinner {
    &::before {
      content: "";
      box-sizing: border-box;
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin-top: -10px;
      margin-left: -10px;
      border-radius: 50%;
      border: 2px solid #ccc;
      border-top-color: var(--primary-color);
      border-bottom-color: var(--primary-color);
      animation: spinner 0.6s linear infinite;
    }
    @keyframes spinner {
      0% {
        transform: rotate(0deg);
      }
      25% {
        transform: rotate(45deg);
      }
      50% {
        transform: rotate(90deg);
      }
      75% {
        transform: rotate(135deg);
      }
      100% {
        transform: rotate(180deg);
      }
    }
  }
}
</style>