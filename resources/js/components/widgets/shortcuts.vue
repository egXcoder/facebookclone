<template>
  <div class="shortcuts-widget">
    <h4>Your shortcuts</h4>
    <div class="d-flex flex-column">
      <template v-for="shortcut in shortcuts">
        <router-link :to="shortcut.link" :key=shortcut.name>
          <img
            src="shortcut.icon"
            class="img-fluid rounded-circle mx-2"
            style="height: 30px"
          />
          {{ shortcut.name }}
        </router-link>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      shortcuts: [],
    };
  },
  created() {
    this.fetchShortcuts();
  },
  methods: {
    fetchShortcuts() {
      window.axios.get("/widgets/shortcuts").then((response) => {
      });
    },
  },
};
</script>

<style lang="scss" scoped>
    .shortcuts-widget{
        h4{
            color:#65676b;
            letter-spacing: -1px;
        }
    }
</style>
