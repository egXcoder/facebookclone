<template>
  <div class="add-post-component" ref="add_post_component">
    <div class="add-post">
      <div class="d-flex align-items-center onmind">
        <img
          :src="$store.state.user.me.image_url"
          class="img-fluid"
          @click="showModal()"
        />
        <p class="flex-grow-1" @click="showModal()">
          {{ welcome_question }}
        </p>
      </div>
      <hr />
      <div class="d-flex extra-actions">
        <div class="feeling-activity">
          <span class="icon mx-1"><i class="far fa-grin" /></span>
          Feeling/Activity
        </div>
      </div>
    </div>

    <div ref="modal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header align-items-center">
            <h5 class="modal-title font-weight-bold text-center flex-grow-1">
              Create Post
            </h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div
              ref="text"
              class="text"
              contenteditable="true"
              :data-placeholder="welcome_question"
            />
            <div class="d-flex justify-content-lg-between">
              <div>a</div>
              <div class="emoji">
                <span @click="pick_emoji = !pick_emoji"
                  ><i class="far fa-grin"
                /></span>
                <picker
                  v-show="pick_emoji"
                  set="twitter"
                  @select="selectEmoji"
                />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Picker } from "emoji-mart-vue";

export default {
  components: {
    Picker,
  },
  data() {
    return {
      pick_emoji: false,
      text: "",
    };
  },
  computed: {
    welcome_question() {
      let name = this.$store.state.user.me.name;
      name = this.$options.filters.capitalize(name);
      name = this.$options.filters.firstWord(name);
      return `What's on your mind, ${name}?`;
    },
  },
  mounted() {
    this.hideEmojiPopupOnClickOutside();
  },
  methods: {
    showModal() {
      window.$(this.$refs.modal).modal("show");
    },
    selectEmoji(emoji) {
      this.$refs.text.innerText += emoji.native;
    },
    hideEmojiPopupOnClickOutside() {
      window.$(this.$refs.add_post_component).click(() => {
        let clicked = window.$(window.event.target);

        function isClickedElementHasParent(parentSelector) {
          return clicked.parents(parentSelector).length;
        }

        if (!isClickedElementHasParent(".emoji")) {
          this.pick_emoji = false;
        }
      });
    },
  },
};
</script>

<style lang="scss">
.add-post-component {
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

  .modal {
    .modal-content {
      border: none;
      .text {
        height: 150px;
        outline: none;
        overflow-y: auto;
        &[contenteditable="true"]:empty:before {
          content: attr(data-placeholder);
          color: grey;
        }
      }
      .emoji {
        > span {
          font-size: 1.7rem;
        }
        span {
          cursor: pointer;
        }
      }
    }
    .close {
      background: #e4e6eb;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #2b2e31;
      font-size: 1.9rem;
      margin-left: 3px;
      margin-right: 3px;
    }
  }
}
</style>