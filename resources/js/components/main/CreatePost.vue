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
          <div class="modal-body p-0">
            <div class="author">
              <img
                :src="$store.state.user.me.image_url"
                class="rounded-circle"
              />
              <div>
                <p class="mb-0">{{ $store.state.user.me.name | capitalize }} <span v-if="feeling">{{`is ${feeling.icon} feeling ${feeling.name}`}}</span></p>
                <select v-model="audience_type">
                  <option value="public">Public</option>
                  <option value="friends">Friends</option>
                  <option value="only_me">Only Me</option>
                </select>
              </div>
            </div>
            <div class="post-editor">
              <div class="theme px-3 py-1 w-100" :style="theme">
                <div
                  ref="text"
                  class="text"
                  contenteditable="true"
                  :data-placeholder="welcome_question"
                />
              </div>
            </div>
            <div
              class="d-flex justify-content-lg-between overflow-hidden align-items-center p-2"
            >
              <div class="flex-grow-1">
                <img
                  v-show="!pick_theme"
                  src="/images/SATP_Aa_square-2x.png"
                  class="img-fluid"
                  style="height: 32px; cursor: pointer"
                  @click="pick_theme = !pick_theme"
                />
                <theme-picker
                  v-show="pick_theme"
                  @hideThemePicker="pick_theme = false"
                  @setTheme="theme = $event"
                ></theme-picker>
              </div>
              <div class="emoji">
                <picker
                  v-show="pick_emoji"
                  set="twitter"
                  @select="selectEmoji"
                  :showPreview="false"
                  :pickerStyles="{
                    position: 'fixed',
                    transform: 'translate(-50%,-100%)',
                  }"
                />
                <span @click="pick_emoji = !pick_emoji"
                  ><i class="far fa-grin"
                /></span>
              </div>
            </div>
            <div class="add-to-your-post">
              <div class="row align-items-center">
                <div class="col-md-4 font-weight-bold">Add To your Post</div>
                <div class="col-md-8 d-flex actions">
                  <div
                    class="feeling-activity"
                    @click="showFeelingActivityModal()"
                  >
                    <span class="icon mx-1"><i class="far fa-grin" /></span>
                  </div>
                </div>
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

    <feeling-activity-modal
      @select="feeling = $event;hideFeelingActivityModal()"
      @hide="hideFeelingActivityModal"
      ref="feeling_activity_modal"
    />
  </div>
</template>

<script>
import { Picker } from "emoji-mart-vue";
import ThemePicker from "./ThemePicker";
import FeelingActivityModal from "./FeelingActivityModal";

export default {
  components: {
    Picker,
    ThemePicker,
    FeelingActivityModal,
  },
  data() {
    return {
      pick_emoji: false,
      pick_theme: false,
      text: "",
      theme: {},
      feeling:{},
      audience_type: "public",
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
  beforeDestroy() {
    this.removeHideEmojiPopupOnClickOutsideEvent();
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
    removeClickEventListenerOnRootDiv() {
      this.$refs.add_post_component.removeEventListener("click");
    },
    showFeelingActivityModal() {
      window.$(this.$refs.feeling_activity_modal.$el).modal("show");
      window.$(this.$refs.modal).modal("hide");
    },
    hideFeelingActivityModal() {
      window.$(this.$refs.feeling_activity_modal.$el).modal("hide");
      window.$(this.$refs.modal).modal("show");
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
      .author {
        display: flex;
        padding: 0.5rem;
        align-items: center;
        img {
          height: 35px;
          margin-left: 0.5rem;
          margin-right: 0.5rem;
        }
        p {
          font-weight: bold;
        }
        select {
          outline: none;
          border: none;
          background: #e8e8e8;
          border-radius: 5px;
        }
      }
      .post-editor {
        height: 180px;
        overflow-x: hidden;
        overflow-y: auto;
        &::-webkit-scrollbar {
          width: 5px;
        }

        &::-webkit-scrollbar-track {
          background: #f1f1f1;
        }

        &::-webkit-scrollbar-thumb {
          background: #888;
        }

        &::-webkit-scrollbar-thumb:hover {
          background: #555;
        }
        .theme {
          color: #65676b;
          .text {
            height: 150px;
            outline: none;
            overflow-y: auto;
            font-size: 1.7rem;
            &[contenteditable="true"]:empty:before {
              content: attr(data-placeholder);
              font-weight: bold;
              letter-spacing: 1px;
            }
          }
        }
      }
      .emoji {
        > span {
          font-size: 1.5rem;
        }
        span {
          cursor: pointer;
        }
      }
      .add-to-your-post {
        margin: 1rem;
        padding: 1rem;
        border: 1px solid #eee;
        border-radius: 7px;
        .actions {
          .feeling-activity {
            vertical-align: middle;
            font-size: 1.6rem;
            color: #f8c64e;
            cursor: pointer;
            &:hover {
              color: #ecb73a;
            }
          }
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