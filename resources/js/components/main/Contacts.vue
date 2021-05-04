<template>
  <div class="contacts">
    <div class="search">
      <input
        type="search"
        class="form-control"
        placeholder="Search Contacts..."
        v-model="searchContact"
      />
    </div>
    <div class="list">
      <template v-for="(contact, index) in contacts">
        <div class="friend" :key="index" @click="addToChats(contact)">
          <img :src="contact.image_url" alt="" />
          <p>{{ contact.name }}</p>
        </div>
      </template>
    </div>
    <div v-if="cache.length" class="chat-with-list">
      <div v-for="(user, index) in cache" :key="index">
        <img :src="user.image_url" @click="removeFromCache(user)" />
      </div>
    </div>
    <div class="chats-container">
      <div v-for="(user, index) in chats" :key="index">
        <messenger
          :key="user.id"
          :chatWith="user"
          @close="removeFromChats(user)"
          @cache-user="addToCache($event)"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Messenger from "./Messenger";
import { bus } from "../../app";

export default {
  components: { Messenger },
  data() {
    return {
      searchContact: null,
      cache: [],
      chats: [],
    };
  },
  computed: {
    contacts() {
      if (!this.searchContact) {
        return this.$store.state.User.friends.slice(0, 8);
      }

      return this.$store.state.User.friends.filter((user) => {
        return user.name.toLowerCase().includes(this.searchContact.toLowerCase());
      });
    },
  },
  mounted() {
    this.listenOnMessageSent();
  },
  methods: {
    addToChats(user) {
      if (this.isUserOnChats(user)) {
        return;
      }

      this.chats.push(user);

      if (this.chats.length > 2) {
        this.chats.shift();
      }
    },
    isUserOnChats(user) {
      return this.chats.find((x) => x.id == user.id);
    },
    removeFromChats(user) {
      this.chats = this.chats.filter((x) => x.id != user.id);
    },
    addToCache(user) {
      this.removeFromChats(user);

      if (this.isUserOnCache(user)) {
        return;
      }

      this.cache.push(user);

      if (this.cache.length >= 5) {
        this.cache.shift();
      }
    },
    isUserOnCache(user) {
      return this.cache.find((x) => x.id == user.id);
    },
    removeFromCache(user) {
      this.addToChats(user);
      this.cache = this.cache.filter((x) => x.id != user.id);
    },
    listenOnMessageSent() {
      bus.$on("MessageSent", (e) => {
        if (!this.isUserOnChats(e.sender)) {
          this.addToChats(e.sender);
        }
      });

      this.$on("hooks:beforeDestroy", () => {
        bus.$off("MessageSent");
      });
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
  .chats-container {
    position: fixed;
    bottom: 0px;
    right: 6rem;
    display: flex;
  }
}
</style>