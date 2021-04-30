<template>
  <div class="contacts">
    <div class="search">
      <input
        type="text"
        class="form-control"
        placeholder="Search contacts"
        v-model="search_contact"
      />
    </div>
    <div class="list">
      <div class="friend" v-for="(contact, index) in contacts" :key="index">
        <img :src="contact.image_url" alt="" />
        <p>{{ contact.name }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search_contact: "",
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
};
</script>

<style lang="scss" scoped>
@import "../../../sass/_shared_styles.scss";

.contacts {
  .search {
    input {
      border-radius: 1rem;
      padding: 7px;
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
}
</style>