<template>
  <div class="categories-widget">
    <router-link to="/xx">
      <img
        :src="$store.state.User.me.image_url"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      {{ $store.state.User.me.name }}
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/covid.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      COVID-19 Information Center
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/friends.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      Friends
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/groups.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      Groups
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/market.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      Marketplace
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/recent.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      Most Recent
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/events.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      Events
    </router-link>
    <router-link to="/xx">
      <img
        src="/images/favorites.png"
        class="img-fluid rounded-circle mx-2"
        style="height: 30px"
      />
      Favorites
    </router-link>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.categories-widget {
  display: flex;
  flex-direction: column;
  a {
    text-decoration: none;
    color: black;
    padding-top: 5px;
    padding-bottom: 5px;
    transition: all 0.2s;
    &:hover {
      background: #efefef;
      border-top-left-radius: 1rem;
      border-bottom-left-radius: 1rem;
    }
  }
}
</style>