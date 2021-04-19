<template>
  <div class="add-post">
    <div class="d-flex align-items-center onmind">
      <img :src="$store.state.user.me.image_url" class="img-fluid" />
      <p class="flex-grow-1">
        What's on your mind,
        {{ $store.state.user.me.name | firstWord | capitalize }}?
      </p>
    </div>
    <hr />
    <div class="d-flex extra-actions">
      <div class="feeling-activity">
        <span class="icon mx-1"><i class="far fa-grin"></i></span>
        Feeling/Activity
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss">
.add-post {
  background: white;
  border-radius: 1rem;
  padding: 0.8rem;
  .onmind {
    img {
      height: 40px;
      border-radius: 50%;
      margin-left: 0.5rem;
      margin-right: 0.5rem;
      cursor: pointer;
      &:hover {
        background: gray;
      }
    }

    p {
      background: #f0f2f5;
      border-radius: 1rem;
      padding: 0.7rem 0.4rem;
      cursor: pointer;
      margin-bottom: 0px;
      &:hover {
        background: #dce1e7;
      }
    }
  }
  .extra-actions {
    .feeling-activity {
      padding: 0.1rem 0.5rem;
      border-radius: 1rem;
      cursor: pointer;
      color: #606770;
      font-weight: bold;
      &:hover {
        background: #f0f2f5;
      }
      .icon {
        vertical-align: middle;
        font-size: 1.6rem;
        color: #f8c64e;
      }
    }
  }
}
</style>