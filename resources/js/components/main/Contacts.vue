<template>
  <div class="contacts">
    <div class="search">
      <input
        type="search"
        class="form-control"
        placeholder="Search Contacts..."
        v-model="search_contact"
      />
    </div>
    <div class="list">
      <template v-for="(contact, index) in contacts">
        <div class="friend" :key="index" @click="showMessenger(contact)">
          <img :src="contact.image_url" alt="" />
          <p>{{ contact.name }}</p>
        </div>
      </template>
    </div>
    <div v-if="chatWithList.length" class="chat-with-list">
      <div v-for="(chatWith, index) in chatWithList" :key="index">
        <img :src="chatWith.image_url" @click="showChatWithFromList(chatWith)" />
      </div>
    </div>
    <messenger
      v-if="chatWith"
      :key="chatWith.id"
      :chatWith="chatWith"
      @close="closeMessenger()"
      @cache-chat-with="cacheChatWith"
    />
  </div>
</template>

<script>
import Messenger from "./Messenger";

export default {
  components: { Messenger },
  data() {
    return {
      search_contact: "",
      chatWith: null,
      chatWithList: [],
    };
  },
  computed: {
    contacts() {
      if (!this.search_contact) {
        return this.$store.state.User.friends.slice(0, 8);
      }

      return this.$store.state.User.friends.filter((user) => {
        return user.name.toLowerCase().includes(this.search_contact.toLowerCase());
      });
    },
  },
  methods: {
    showMessenger(chatWith) {
      this.chatWith = chatWith;
    },
    closeMessenger() {
      this.chatWith = null;
    },
    cacheChatWith(chatWith) {
      if (this.chatWithList.find((x) => x.id == chatWith.id)) {
        this.chatWith = null;
        return;
      }

      if (this.chatWithList.length >= 5) {
        this.chatWithList.shift();
      }
      
      this.chatWith = null;
      this.chatWithList.push(chatWith);
    },
    showChatWithFromList(chatWith) {
      this.chatWith = chatWith;
      this.chatWithList = this.chatWithList.filter((x) => x.id != chatWith.id);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/_shared_styles.scss";

.contacts {
  .search {
    input {
      border-radius: 1rem;
      padding: 7px 1rem;
    }
  }
  .list {
    margin: 1rem 0rem;
    max-height: 500px;
    overflow-y: auto;
    @include scroll_style;
    .friend {
      display: flex;
      align-items: center;
      padding: 7px;
      &:hover {
        background: var(--grayed);
        cursor: pointer;
        border-radius: 1rem;
      }
      img {
        height: 40px;
        border-radius: 50%;
        margin: 0px 7px;
      }
      p {
        margin-bottom: 0px;
      }
    }
  }
  .chat-with-list {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    img {
      cursor: pointer;
      margin: 0.5rem 0rem;
      height: 60px;
      border-radius: 50%;
    }
  }
}
</style>