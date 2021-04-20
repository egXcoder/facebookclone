<template>
  <div class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header align-items-center">
          <button class="close" @click="$emit('hide')">
            <i class="fas fa-arrow-left" style="font-size: 1.2rem"></i>
          </button>
          <h5 class="modal-title font-weight-bold text-center flex-grow-1">
            Feeling/Activity
          </h5>
        </div>
        <div class="modal-body p-0">
          <div class="d-flex">
            <p
              :class="{ active: toShow == 'feeling' }"
              @click="toShow = 'feeling'"
              class="button-to-show"
            >
              Feeling
            </p>
            <p
              :class="{ active: toShow == 'activity' }"
              @click="toShow = 'activity'"
              class="button-to-show"
            >
              Activity
            </p>
          </div>

          <template v-if="toShow == 'feeling'">
            <div class="search">
              <i class="fas fa-search mx-2"></i>
              <input
                type="search"
                v-model="search_feelings"
                class="flex-grow-1"
                placeholder="Search ..."
              />
            </div>
            <hr>
            <div class="row no-gutters p-2 feeling-section">
              <template v-for="(feeling, index) in filtered_feelings">
                <div
                  class="col-md-6"
                  @click="$emit('select', feeling)"
                  :key="index"
                >
                  <div class="single-feeling p-1">
                    <span>{{ feeling.icon }}</span>
                    {{ feeling.name }}
                  </div>
                </div>
              </template>
            </div>
          </template>

          <template v-else-if="toShow == 'activity'">
            <div></div>
          </template>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      toShow: "feeling",
      feelings: [],
      activities: [],
      search_feelings: "",
    };
  },
  computed: {
    filtered_feelings() {
      if (!this.search_feelings.length) {
        return this.feelings;
      }

      let computed_feelings = [];
      for (let feeling of this.feelings) {
        if (feeling.name.includes(this.search_feelings)) {
          computed_feelings.push(feeling);
        }
      }
      return computed_feelings;
    },
  },
  created() {
    this.fetchFeelings();
    this.fetchActivities();
  },
  methods: {
    fetchFeelings() {
      window.axios.get("/api/posts/feelings").then((response) => {
        this.feelings = response.data;
      });
    },
    fetchActivities() {
      window.axios.get("/api/posts/activities").then((response) => {
        this.activities = response.data;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.button-to-show {
  background: rgb(255, 255, 255);
  padding: 1rem;
  margin: 0.5rem;
  cursor: pointer;
  border-radius: 3px;
  &:hover {
    background: #eee;
  }
  &.active {
    color: hsl(214deg 89% 52%);
    border-bottom: 2px solid hsl(214deg 89% 52%);
  }
}
.search {
  display: flex;
  background: #eee;
  margin: 1rem;
  padding: 0.3rem;
  border-radius: 7px;
  align-items: center;
  input {
    border: none;
    outline: none;
    background: #eee;
  }
}
.feeling-section {
  .single-feeling {
    cursor: pointer;
    &:hover {
      background: #eee;
    }
  }
}
</style>