<template>
  <div class="my-5">
    {{ post.text }}
  </div>
</template>

<script>
export default {
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

<style>
</style>