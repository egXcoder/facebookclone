<template>
  <div class="home-screen row">
    <aside class="left d-flex flex-column col-md-3">
      <categories />
      <hr class="w-100">
      <shortcuts />
    </aside>
    <main class="middle col-md-6">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <create-post />
        </div>
      </div>
    </main>
    <aside class="right" />
  </div>
</template>

<script>
import Categories from "../widgets/Categories.vue";
import Shortcuts from "./../widgets/Shortcuts";
import CreatePost from "./CreatePost.vue";

export default {
  components: {
    Shortcuts,
    Categories,
    CreatePost,
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>

<style lang="scss" scoped>
.home-screen {
}
</style>