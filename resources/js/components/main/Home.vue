<template>
  <div class="home-screen row">
    <aside class="left d-flex flex-column col-md-3">
      <categories></categories>
      <hr class="w-100" />
      <shortcuts></shortcuts>
    </aside>
    <aside class="middle"></aside>
    <aside class="right"></aside>
  </div>
</template>

<script>
import Categories from "../widgets/Categories.vue";
import Shortcuts from "./../widgets/Shortcuts";
export default {
  components: {
    Shortcuts,
    Categories,
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