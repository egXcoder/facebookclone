<template>
  <div class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header align-items-center">
          <button class="close" @click="$emit('hide')">
            <i class="fas fa-arrow-left" style="font-size: 1.2rem" />
          </button>
          <h5 class="modal-title font-weight-bold text-center flex-grow-1">Choose Gif</h5>
        </div>
        <div class="modal-body p-0" ref="modal_body">
          <div v-for="(gif, index) in gifs_to_render" :key="index" class="gif-container">
            <img :src="gif.gif_url" style="width: 100%" @click="$emit('hide', gif)" />
          </div>
          <div class="loading" ref="loading">
            <div class="ball"></div>
            <div class="ball"></div>
            <div class="ball"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      gifs: [],
      how_many_gifs_to_render: 3,
    };
  },
  computed: {
    gifs_to_render() {
      return this.gifs.slice(0, this.how_many_gifs_to_render);
    },
  },
  created() {
    this.fetchGif();
  },
  mounted() {
    this.applyInfinteScroll();
  },
  methods: {
    fetchGif() {
      window.axios.get("/api/posts/gifs").then((response) => {
        this.gifs = response.data;
      });
    },
    applyInfinteScroll() {
      let modal_body = this.$refs.modal_body;

      modal_body.addEventListener("scroll", () => {
        let heightFromBottomToScrollTop =
          modal_body.scrollHeight - (modal_body.scrollTop + modal_body.clientHeight);

        if (heightFromBottomToScrollTop < 1) {
          this.showLoading();
          setTimeout(() => {
            this.hideLoading();
          }, 1000);
          this.how_many_gifs_to_render += 3;
        }
      });

      this.$on("hook:beforeDestroy", () => {
        modal_body.removeEventListener();
      });
    },
    showLoading() {
      this.$refs.loading.classList.add("shown");
    },
    hideLoading() {
      this.$refs.loading.classList.remove("shown");
    },
  },
};
</script>

<style lang="scss" scoped>
.modal-body {
  height: 500px;
  overflow-x: hidden;
  overflow-y: auto;
  .gif-container {
    cursor: pointer;
  }
  .loading {
    opacity: 0;
    display: flex;
    position: fixed;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);
    transition: opacity 0.3s ease-in;
    &.shown {
      opacity: 1;
    }
    .ball {
      background-color: #777;
      border-radius: 50%;
      margin: 5px;
      height: 10px;
      width: 10px;
      animation: jump 0.5s ease-in infinite;
      &:nth-of-type(2) {
        animation-delay: 0.1s;
      }

      &:nth-of-type(3) {
        animation-delay: 0.2s;
      }
    }
  }

  @keyframes jump {
    0%,
    100% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-10px);
    }
  }
}
</style>>