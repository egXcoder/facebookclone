<template>
  <div class="home-screen row">
    <aside class="left d-flex flex-column col-md-3">
      <categories></categories>
      <hr class="w-100" />
      <shortcuts></shortcuts>
    </aside>
    <main class="middle col-md-6">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <add-post></add-post>
        </div>
      </div>
    </main>
    <aside class="right"></aside>
  </div>
</template>

<script>
import Categories from "../widgets/Categories.vue";
import Shortcuts from "./../widgets/Shortcuts";
import AddPost from "./AddPost.vue";

export default {
  components: {
    Shortcuts,
    Categories,
    AddPost,
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