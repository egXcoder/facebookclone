<template>
  <div ref="add_post_component" class="add-post-component">
    <div class="add-post">
      <div class="d-flex align-items-center onmind">
        <img :src="$store.state.User.me.image_url" class="img-fluid" @click="showModal()" />
        <p class="flex-grow-1" @click="showModal()">
          {{ welcome_question }}
        </p>
      </div>
      <hr />
      <div class="d-flex extra-actions">
        <div class="feeling-activity" @click="showFeelingActivityModal()">
          <span class="icon mx-1"><i class="far fa-grin" /></span>
          Feeling/Activity
        </div>
      </div>
    </div>

    <div ref="modal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header align-items-center">
            <h5 class="modal-title font-weight-bold text-center flex-grow-1">Create Post</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-0">
            <div class="author">
              <img :src="$store.state.User.me.image_url" class="rounded-circle" />
              <div>
                <p class="mb-0">
                  {{ $store.state.User.me.name | capitalize }}
                  <span v-if="Object.keys(feeling).length">
                    {{ `is ${feeling.icon} feeling ${feeling.name}` }}
                  </span>
                  <span v-if="Object.keys(activity).length">
                    is
                    <img :src="activity.icon" style="height: 20px; margin: 0px" />
                    {{ `${activity.parent_name} ${activity.name}` }}
                  </span>

                  <span v-if="Object.keys(tagged).length"
                    >with
                    <span>{{ tagged_names.join(", ") }}</span>
                  </span>
                </p>
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
                  v-text="text"
                  @blur="text = $event.target.innerText"
                  :data-placeholder="welcome_question"
                ></div>
                <div class="gif-container" v-if="Object.keys(gif).length">
                  <span @click="gif = {}">X</span>
                  <img :src="gif.gif_url" style="width: 100%" />
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-lg-between overflow-hidden align-items-center p-2">
              <div class="flex-grow-1">
                <img
                  v-show="!pick_theme && !isAddToPostActionDisabled('theme')"
                  src="/images/SATP_Aa_square-2x.png"
                  class="img-fluid"
                  style="height: 42px; cursor: pointer"
                  @click="pick_theme = !pick_theme"
                />
                <theme-picker
                  v-show="pick_theme"
                  @hideThemePicker="pick_theme = false"
                  @setTheme="theme = $event"
                />
              </div>
              <div class="emoji">
                <picker
                  v-show="pick_emoji"
                  set="twitter"
                  :show-preview="false"
                  :picker-styles="{
                    position: 'fixed',
                    transform: 'translate(-50%,-100%)',
                  }"
                  @select="selectEmoji"
                />
                <span @click="showEmojiPopup()"><i class="far fa-grin" /></span>
              </div>
            </div>
            <div class="add-to-your-post">
              <div class="row align-items-center">
                <div class="col-md-4 font-weight-bold">Add To your Post</div>
                <div class="col-md-8 d-flex actions">
                  <div class="feeling-activity" @click="showFeelingActivityModal()">
                    <span class="icon mx-1"><i class="far fa-grin" /></span>
                  </div>
                  <div class="tag-friends" @click="showTagFriendsModal()">
                    <span class="icon mx-1">
                      <i class="fas fa-user-tag" />
                    </span>
                  </div>
                  <div
                    class="gif"
                    @click="showGifModal()"
                    :class="{ disabled_action: isAddToPostActionDisabled('gif') }"
                  >
                    <span class="icon mx-1">
                      <i class="fas fa-images"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              @click="savePost()"
              type="button"
              class="btn btn-primary w-100"
              :disabled="!isValidPost()"
            >
              Post
            </button>
          </div>
        </div>
      </div>
    </div>

    <feeling-activity-modal
      ref="feeling_activity_modal"
      @hide="hideAndSelectFeelingActivity($event)"
    />

    <tag-friends-modal ref="tag_friends_modal" @hide="hideAndSelectTagFriends($event)" />

    <gif-modal ref="gif_modal" @hide="hideAndSelectGif($event)"></gif-modal>
  </div>
</template>

<script>
import { Picker } from "emoji-mart-vue";
import ThemePicker from "./ThemePicker";
import FeelingActivityModal from "./FeelingActivityModal";
import TagFriendsModal from "./TagFriendsModal";
import GifModal from "./GifModal";

let FeelingActivityMixin = {
  data() {
    return {
      feeling: {},
      activity: {},
    };
  },
  methods: {
    showFeelingActivityModal() {
      window.$(this.$refs.feeling_activity_modal.$el).modal("show");
      window.$(this.$refs.modal).modal("hide");
    },
    hideFeelingActivityModal() {
      window.$(this.$refs.feeling_activity_modal.$el).modal("hide");
      window.$(this.$refs.modal).modal("show");
    },
    hideAndSelectFeelingActivity({ type, data } = {}) {
      this.hideFeelingActivityModal();
      if (type == "feeling") {
        return this.selectFeeling(data);
      }

      if (type == "activity") {
        return this.selectActivity(data);
      }
    },
    selectFeeling(feeling) {
      this.feeling = feeling;
      this.activity = {};
    },
    selectActivity(activity) {
      this.activity = activity;
      this.feeling = {};
    },
  },
};

let TagFriendsMixin = {
  data() {
    return {
      tagged: {},
    };
  },
  computed: {
    tagged_names() {
      let names = [];
      for (let friend_id in this.tagged) {
        names.push(this.tagged[friend_id].name);
      }
      return names;
    },
  },
  methods: {
    showTagFriendsModal() {
      window.$(this.$refs.tag_friends_modal.$el).modal("show");
      window.$(this.$refs.modal).modal("hide");
    },
    hideTagFriendsModal() {
      window.$(this.$refs.tag_friends_modal.$el).modal("hide");
      window.$(this.$refs.modal).modal("show");
    },
    hideAndSelectTagFriends(tagged = {}) {
      this.hideTagFriendsModal();
      this.tagged = tagged;
    },
    getTaggedIds() {
      let ids = [];
      for (let tagged_id in this.tagged) {
        ids.push(tagged_id);
      }
      return ids;
    },
  },
};

let GifMixin = {
  data() {
    return {
      gif: {},
    };
  },
  methods: {
    showGifModal() {
      window.$(this.$refs.gif_modal.$el).modal("show");
      window.$(this.$refs.modal).modal("hide");
    },
    hideGifModal() {
      window.$(this.$refs.gif_modal.$el).modal("hide");
      window.$(this.$refs.modal).modal("show");
    },
    hideAndSelectGif(gif) {
      this.hideGifModal();
      if (gif) {
        this.gif = gif;
      }
    },
  },
};

export default {
  components: {
    Picker,
    ThemePicker,
    FeelingActivityModal,
    TagFriendsModal,
    GifModal,
  },
  mixins: [FeelingActivityMixin, TagFriendsMixin, GifMixin],
  data() {
    return {
      pick_emoji: false,
      pick_theme: false,
      text: "",
      theme: {},
      audience_type: "public",
    };
  },
  computed: {
    welcome_question() {
      let name = this.$store.state.User.me.name;
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
    hideModal() {
      window.$(this.$refs.modal).modal("hide");
    },
    showEmojiPopup() {
      this.pick_emoji = !this.pick_emoji;
    },
    selectEmoji(emoji) {
      this.text += emoji.native;
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
    isAddToPostActionDisabled(action) {
      if (action == "gif") {
        return Object.keys(this.theme).length;
      }

      if (action == "theme") {
        return Object.keys(this.gif).length;
      }
    },
    isValidPost() {
      return this.text.length;
    },
    savePost() {
      if (!this.isValidPost()) {
        return window.toastr.error("Post is Not Valid");
      }

      let post = {
        text: this.text,
        audience_type: this.audience_type,
        theme_id: this.theme.id,
        feeling_id: this.feeling.id,
        activity_id: this.activity.id,
        gif_id: this.gif.id,
        tagged: this.getTaggedIds(),
      };

      window.axios.post("/api/posts", post).then((response) => {
        if (response.data.success) {
          window.toastr.success(response.data.success);
          this.hideModal();
          this.$store.commit('Feed/unshiftPost',response.data.data);
          
          let freshCopyOfData = this.$options.data();
          Object.assign(this.$data,freshCopyOfData);
        }

        if (response.data.error) {
          window.toastr.error(response.data.error);
        }
      });
    },
  },
};
</script>

<style lang="scss">
@mixin scroll_style {
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
}

.add-post-component {
  margin-bottom: 1rem;
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
        @include scroll_style;
        .theme {
          color: #65676b;
          .text {
            font-weight: bold;
            border: 0px;
            background: transparent;
            outline: none;
            overflow-y: auto;
            font-size: 1.7rem;
            width: 100%;
            resize: none;
            &[contenteditable="true"]:empty:before {
              content: attr(data-placeholder);
              font-weight: bold;
              letter-spacing: 1px;
            }
            @include scroll_style;
            img.emoji {
              height: 1em;
              width: 1em;
              margin: 0 0.05em 0 0.1em;
              vertical-align: -0.1em;
            }
          }
        }
        .gif-container {
          position: relative;
          span {
            position: absolute;
            right: 5px;
            top: 5px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            background: white;
            color: gray;
            cursor: pointer;
          }
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
      .add-to-your-post {
        margin: 1rem;
        padding: 1rem;
        border: 1px solid #eee;
        border-radius: 7px;
        .actions {
          font-size: 1.5rem;
          .feeling-activity {
            vertical-align: middle;
            cursor: pointer;
            margin-left: 5px;
            margin-right: 5px;
            color: #f8c64e;
            &:hover {
              color: #ecb73a;
            }
          }
          .tag-friends {
            vertical-align: middle;
            cursor: pointer;
            margin-left: 5px;
            margin-right: 5px;
            color: #1877f2;
            &:hover {
              color: #176edf;
            }
          }
          .gif {
            vertical-align: middle;
            cursor: pointer;
            margin-left: 5px;
            margin-right: 5px;
            color: #45bd62;
            &:hover {
              color: #46a85f;
            }
          }
          .disabled_action {
            color: gray;
            pointer-events: none;
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
    .modal-footer {
      button[disabled],
      button:disabled {
        background: gray;
        border: gray;
        cursor: not-allowed;
      }
    }
  }
}
</style>