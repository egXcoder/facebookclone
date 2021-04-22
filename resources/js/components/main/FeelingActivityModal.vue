<template>
  <div class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header align-items-center">
          <button class="close" @click="$emit('hide')">
            <i class="fas fa-arrow-left" style="font-size: 1.2rem"></i>
          </button>
          <h5 class="modal-title font-weight-bold text-center flex-grow-1">Feeling/Activity</h5>
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
            <hr />
            <div class="row no-gutters p-2 feeling-section">
              <template v-for="(feeling, index) in filtered_feelings">
                <div
                  class="col-md-6"
                  @click="$emit('hide', { type: 'feeling', data: feeling })"
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
            <template v-if="selected_activity">
              <div class="d-flex align-items-center mx-2">
                <span class="selected-activity">
                  {{ selected_activity.name | capitalize }}...
                  <i class="fas fa-times mx-1" @click="selected_activity = null"></i>
                </span>
                <div class="search flex-grow-1">
                  <i class="fas fa-search mx-2"></i>
                  <input
                    type="search"
                    v-model="search_activities"
                    class="flex-grow-1"
                    placeholder="Search ..."
                  />
                </div>
              </div>
              <hr />
              <div class="row no-gutters p-2 nested-activities-section">
                <template v-for="(activity, index) in filtered_activities">
                  <div
                    class="single-activity col-md-6 p-1"
                    :key="index"
                    @click="$emit('hide', { type: 'activity', data: activity })"
                  >
                    <img :src="activity.icon" class="img-fluid" />
                    {{ activity.name }}
                  </div>
                </template>
              </div>
            </template>

            <template v-else>
              <div class="search">
                <i class="fas fa-search mx-2"></i>
                <input
                  type="search"
                  v-model="search_activities"
                  class="flex-grow-1"
                  placeholder="Search ..."
                />
              </div>
              <hr />
              <div class="row no-gutters p-2 activities-section">
                <template v-for="(activity, index) in filtered_activities">
                  <div
                    class="single-activity p-1"
                    :key="index"
                    @click="selected_activity = activity"
                  >
                    <img :src="activity.icon" class="img-fluid" />
                    {{ activity.name | capitalize }}
                    <div class="float-right">
                      <i class="fas fa-chevron-right"></i>
                    </div>
                  </div>
                </template>
              </div>
            </template>
          </template>
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
      search_activities: "",
      selected_activity: null,
    };
  },
  computed: {
    filtered_feelings() {
      if (!this.search_feelings.length) {
        return this.feelings;
      }

      let computed_feelings = [];
      for (let feeling of this.feelings) {
        if (feeling.name.toLowerCase().includes(this.search_feelings.toLowerCase())) {
          computed_feelings.push(feeling);
        }
      }
      return computed_feelings;
    },
    filtered_activities() {
      function search(activities, search) {
        if (!search.length) {
          return activities;
        }
        let computed_activities = [];
        for (let activity of activities) {
          if (activity.name.toLowerCase().includes(search.toLowerCase())) {
            computed_activities.push(activity);
          }
        }
        return computed_activities;
      }

      if (this.selected_activity) {
        return search(this.selected_activity.children, this.search_activities);
      }

      return search(this.activities, this.search_activities);
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
  padding: 0.5rem 1rem;
  margin: 0.3rem;
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
  border-radius: 10px;
  align-items: center;
  input {
    border: none;
    outline: none;
    background: #eee;
  }
}
.feeling-section {
  font-size: 1rem;
  max-height: 300px;
  overflow: auto;
  .single-feeling {
    cursor: pointer;
    &:hover {
      background: #eee;
      border-radius: 7px;
    }
  }
}

.activities-section,
.nested-activities-section {
  font-size: 1rem;
  max-height: 300px;
  overflow: auto;
  display: flex;
  flex-flow: column;
  .single-activity {
    cursor: pointer;
    margin-top: 3px;
    margin-bottom: 3px;
    img {
      border-radius: 50%;
      height: 40px;
      padding: 6px;
      background: #eee;
    }
    &:hover {
      background: #eee;
      border-radius: 7px;
    }
  }
}

.selected-activity {
  background: #e7f3ff;
  padding: 0.3rem;
  border-radius: 4px;
  color: hsl(214deg 89% 52%);
  i {
    cursor: pointer;
    border-radius: 50%;
    padding: 0.3rem;
  }
  i:hover {
    background: #eee;
  }
}

.nested-activities-section {
  flex-flow: wrap;
}
</style>