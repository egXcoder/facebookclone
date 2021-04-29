<template>
  <div class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header align-items-center">
          <button class="close" @click="$emit('hide', tagged)">
            <i class="fas fa-arrow-left" style="font-size: 1.2rem" />
          </button>
          <h5 class="modal-title font-weight-bold text-center flex-grow-1">Tag Friends</h5>
        </div>
        <div class="modal-body p-0">
          <div>
            <div class="d-flex align-items-center mx-2 search-container">
              <div class="search flex-grow-1">
                <i class="fas fa-search mx-2" />
                <input
                  v-model="search"
                  type="search"
                  class="flex-grow-1"
                  placeholder="Search ..."
                />
              </div>
              <a class="mx-3 text-reset text-decoration-none" @click="$emit('hide', tagged)"
                >Done</a
              >
            </div>
            <hr />
            <template v-if="tagged.length">
              <div class="tagged mx-2">
                <p class="mb-0">Tagged</p>
                <div class="d-flex flex-wrap tagged-container">
                  <template v-for="(friend, index) in tagged">
                    <div class="single-tag m-1" :key="index">
                      {{ friend.name }}
                      <a @click="deleteFriendFromTagged(friend)">x</a>
                    </div>
                  </template>
                </div>
              </div>
            </template>
            <div class="friends mx-2">
              <p class="mb-0">Friends</p>
              <div class="d-flex flex-column">
                <div v-for="(friend, index) in friends" :key="index">
                  <div class="single-friend" @click="addFriendToTagged(friend)">
                    <img :src="friend.image_url" />
                    {{ friend.name }}
                  </div>
                </div>
              </div>
            </div>
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
      search: "",
      tagged: [],
      friends: [],
    };
  },
  created() {
    this.fetchFriends();
  },
  methods: {
    fetchFriends() {
      window.axios.get("/api/user/friends").then((response) => {
        this.friends = response.data;
      });
    },
    addFriendToTagged(friend) {
      if (!this.isFriendSelected(friend)) {
        this.tagged.push(friend);
      }
    },
    deleteFriendFromTagged(friend) {
      if (this.isFriendSelected(friend)) {
        this.tagged = this.tagged.filter((user) => user.id != friend.id);
      }
    },
    isFriendSelected(friend) {
      return this.tagged.find((user) => user.id == friend.id);
    },
  },
};
</script>

<style lang="scss" scoped>
.modal {
  .modal-body {
    .search-container {
      .search {
        display: flex;
        background: #eee;
        padding: 0.3rem;
        margin: 1rem 0rem;
        border-radius: 10px;
        align-items: center;
        input {
          border: none;
          outline: none;
          background: #eee;
        }
      }
      a {
        cursor: pointer;
        font-weight: bold;
        color: var(--blue-link) !important;
      }
    }

    .tagged {
      .tagged-container {
        margin-bottom: 1rem;
        overflow-x: hidden;
        overflow-y: auto;
        max-height: 130px;
        padding: 0.5rem;
        border-radius: 7px;
        border: 1px solid var(--divider);
        .single-tag {
          cursor: pointer;
          font-weight: bold;
          color: var(--blue-link) !important;
          background: var(--accent);
          padding: 7px;
          border-radius: 7px;
          a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            &:hover {
              background: #eee;
            }
          }
        }
      }
    }

    .friends {
      max-height: 300px;
      overflow-x: hidden;
      overflow-y: auto;
      .single-friend {
        cursor: pointer;
        padding: 0.5rem;
        img {
          border-radius: 50%;
          margin-left: 7px;
          margin-right: 7px;
          height: 40px;
        }
        &:hover {
          background: #eee;
          border-radius: 1rem;
        }
      }
    }
  }
}
</style>