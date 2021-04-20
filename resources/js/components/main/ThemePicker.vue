<template>
  <div class="position-relative">
    <div class="theme-picker-ribbon d-flex">
      <div class="theme-icon grayed" @click="$emit('hideThemePicker')">
        <i class="fas fa-backward"></i>
      </div>

      <div class="theme-icon grayed" @click="prev()">
        <i class="fas fa-chevron-left"></i>
      </div>

      <div class="flex-grow-1 overflow-hidden">
        <div class="d-flex themes-container" ref="themes" :style="slidingStyle">
          <div
            class="theme-icon transparent"
            @click="$emit('setTheme', {})"
          ></div>
          <template v-for="(theme, index) in themes">
            <img
              class="theme-icon img-fluid"
              :key="index"
              @click="$emit('setTheme', buildTheme(theme))"
              :src="theme.background_url"
            />
          </template>
        </div>
      </div>

      <div class="theme-icon grayed" @click="next()">
        <i class="fas fa-chevron-right"></i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      themes: [],
      slidingStyle: {},
      currentTranslateValue: 0,
    };
  },
  created() {
    this.fetchThemes();
  },
  methods: {
    fetchThemes() {
      window.axios.get("/api/posts/themes").then((response) => {
        this.themes = response.data;
      });
    },
    buildTheme(theme) {
      let builtTheme = {
        width: "500px",
        height: "500px",
        "background-size": "cover",
        "background-repeat": "no-repeat",
        display: "flex",
        "align-items": "center",
        "justify-content": "center",
        "text-align": "center",
      };

      if (theme.background_url) {
        builtTheme["background-image"] = `url('${theme.background_url}')`;
      }

      if (theme.color) {
        builtTheme["color"] = theme.color;
      }

      return builtTheme;
    },
    next() {
      if(this.isSliderAtEnd()){
        return;
      }
      this.currentTranslateValue -= 50;
      this.slidingStyle = {
        transform: `translateX(${this.currentTranslateValue}px)`,
      };
    },
    prev() {
      if(this.isSliderAtStart()){
        return;
      }

      this.currentTranslateValue += 50;
      this.slidingStyle = {
        transform: `translateX(${this.currentTranslateValue}px)`,
      };
    },
    isSliderAtStart(){
      return this.currentTranslateValue>=0;
    },
    isSliderAtEnd(){
      let maxScrollableWidth = this.$refs.themes.scrollWidth - this.$refs.themes.offsetWidth;
      return (this.currentTranslateValue + maxScrollableWidth) < 0;
    }
  },
};
</script>

<style lang="scss">
$img-border-radius: 0.3rem;

.theme-picker-ribbon {
  .themes-container {
    transition: transform 0.4s;
  }
  .theme-icon {
    width: 32px;
    height: 32px;
    border-radius: $img-border-radius;
    cursor: pointer;
    margin-left: 0.3rem;
    margin-right: 0.3rem;
    display: inline-block;
    &.transparent {
      background: #f0f2f5;
      box-shadow: inset 0 0 0 2px white, 0 0 5px 0 rgba(0, 0, 0, 0.2);
      padding: 1rem;
    }
    &.grayed {
      background: #f0f2f5;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    img {
      border-radius: $img-border-radius;
    }
  }
}
</style>