<template>
  <div class="infinit-posts">
    <template v-for="(post, index) in $store.state.Feed.posts">
      <show-post :post="post" :key="index"></show-post>
    </template>
  </div>
</template>

<script>
import ShowPost from "./ShowPost";
export default {
  components: {
    ShowPost,
  },
  data() {
    return {
      posts: [],
    };
  },
  created() {
    this.$store.dispatch('Feed/fetchPosts');
  },
};
</script>

<style>
</style>