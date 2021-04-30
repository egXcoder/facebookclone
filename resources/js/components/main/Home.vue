<template>
  <div class="home-screen row">
    <aside class="left d-flex flex-column col-md-3">
      <categories />
      <hr class="w-100" />
      <shortcuts />
    </aside>
    <main class="middle col-md-6">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <create-post />
          <infinite-post></infinite-post>
        </div>
      </div>
    </main>
    <aside class="right col-md-3">
      <contacts></contacts>
    </aside>
  </div>
</template>

<script>
import Categories from "../widgets/Categories.vue";
import Shortcuts from "./../widgets/Shortcuts";
import CreatePost from "./CreatePost.vue";
import InfinitePost from "./InfinitePost";
import Contacts from "./Contacts";

export default {
  components: {
    Shortcuts,
    Categories,
    CreatePost,
    InfinitePost,
    Contacts,
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>

<style lang="scss" scoped>
.home-screen {
  aside.left,aside.right {
    position: sticky;
    top: var(--top-header);
    height: fit-content;
  }
}
</style>