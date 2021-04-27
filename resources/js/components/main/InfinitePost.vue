<template>
  <div class="infinit-posts">
    <template v-for="(post, index) in posts">
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
    window.axios.get("api/posts/feed").then((response) => {
      this.posts = response.data.data;
    });
  },
};
</script>

<style>
</style>