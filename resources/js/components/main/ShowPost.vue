<template>
  <div class="show-post">
    <div class="p-4">
      <div class="author">
        <img :src="post.user.image_url" alt="" />
        <div class="details">
          <div class="user-name">{{ post.user.name }}</div>
          <div>
            {{ post.created_at }}
            <template v-if="post.audience_type == 'public'">
              <i class="fas fa-globe-europe"></i>
            </template>
            <template v-else-if="post.audience_type == 'friends'">
              <i class="fas fa-user-friends"></i>
            </template>
            <template v-else-if="post.audience_type == 'only_me'">
              <i class="fas fa-lock"></i>
            </template>
          </div>
        </div>
      </div>
      {{ post.text }}
    </div>

    <post-interaction :commentsProp="post.comments" :likesProp="post.likes" />
  </div>
</template>

<script>
import PostInteraction from "./PostInteraction";

export default {
  components: {
    PostInteraction,
  },
  data() {
    return {};
  },
  props: {
    post: {
      required: true,
      type: Object,
      validator: function (post) {
        return post.text != "";
      },
    },
  },
};
</script>

<style lang="scss" scoped>
.show-post {
  background: white;
  border-radius: 7px;
  margin-bottom: 1rem;
  box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important;
  .author {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    img {
      border-radius: 50%;
      height: 40px;
      margin-left: 6px;
      margin-right: 6px;
    }

    .details {
      .user-name {
        font-weight: bold;
      }
    }
  }
}
</style>