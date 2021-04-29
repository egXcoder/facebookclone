<template>
  <div class="show-post">
    <div class="p-4">
      <div class="author">
        <img :src="post.user.image_url" alt="" />
        <div class="details">
          <div class="user-name">{{ post.user.name }}</div>
          <div>
            {{ post.created_at }}
            <template v-if="post.audience_type == 'public'">
              <i class="fas fa-globe-europe"></i>
            </template>
            <template v-else-if="post.audience_type == 'friends'">
              <i class="fas fa-user-friends"></i>
            </template>
            <template v-else-if="post.audience_type == 'only_me'">
              <i class="fas fa-lock"></i>
            </template>
          </div>
        </div>
      </div>
      <pre>{{ post.text }}</pre>
    </div>

    <div class="post-interaction">
      <template v-if="post.likes.length || post.comments.length">
        <div class="interaction-details px-4 py-2">
          <div class="likes">
            <template v-if="likesCategorized.like && likesCategorized.like.length">👍</template>
            <template v-if="likesCategorized.love && likesCategorized.love.length">❤️</template>
            <template v-if="likesCategorized.care && likesCategorized.care.length">🥰</template>
            <template v-if="likesCategorized.haha && likesCategorized.haha.length">😂</template>
            <template v-if="likesCategorized.wow && likesCategorized.wow.length">😯</template>
            <template v-if="likesCategorized.sad && likesCategorized.sad.length">😢</template>
            <template v-if="likesCategorized.angry && likesCategorized.angry.length">😡</template>
            <span class="mx-1">{{ post.likes.length }}</span>
          </div>
          <div class="comments" @click="viewMore(post.comments)">
            {{ this.post.comments.length }} Comments
          </div>
        </div>
      </template>

      <div class="actions-container">
        <div class="actions">
          <button
            :class="{ active: post.isLiked, btn: true }"
            @mouseenter="showLikesBar($event)"
            @mouseleave="hideLikesBar($event)"
            @click="post.isLiked ? unlikePost() : likePost('like')"
          >
            <template v-if="post.isLiked == 'like'">👍 Like</template>
            <template v-else-if="post.isLiked == 'love'">❤️ Love</template>
            <template v-else-if="post.isLiked == 'care'">🥰 Care</template>
            <template v-else-if="post.isLiked == 'haha'">😂 Haha</template>
            <template v-else-if="post.isLiked == 'wow'">😯 Wow</template>
            <template v-else-if="post.isLiked == 'sad'">😢 Sad</template>
            <template v-else-if="post.isLiked == 'angry'">😡 Angry</template>
            <template v-else><i class="far fa-thumbs-up"></i> Like</template>
          </button>

          <button class="btn" @click="focusComment()">
            <i class="far fa-comment-alt"></i> Comment
          </button>
          <button class="btn"><i class="fas fa-share"></i> Share</button>
        </div>

        <div
          class="likes-bar"
          @mouseenter="showLikesBar($event)"
          @mouseleave="hideLikesBar($event)"
        >
          <span @click="likePost('like')">👍</span>
          <span @click="likePost('love')">❤️</span>
          <span @click="likePost('care')">🥰</span>
          <span @click="likePost('haha')">😂</span>
          <span @click="likePost('wow')">😯</span>
          <span @click="likePost('sad')">😢</span>
          <span @click="likePost('angry')">😡</span>
        </div>
      </div>

      <div class="all-comments" v-show="isAnyCommentsShown(post.comments)">
        <a
          class="link"
          @click="viewMore(post.comments)"
          v-if="isStillCommentsToBeShown(post.comments)"
        >
          View more comments
        </a>
        <template v-for="(comment, index) in post.comments">
          <div v-if="comment.shown" :key="index" class="comment">
            <img :src="comment.user.image_url" />
            <div>
              <div class="details">
                <div class="user-name">{{ comment.user.name }}</div>
                <div class="text">
                  <pre>{{ comment.text }}</pre>
                </div>
                <div class="actions">
                  <a class="link">like</a>
                  .
                  <a class="link">reply</a>
                  .
                  {{ comment.created_at }}
                </div>
              </div>
              <a
                class="link"
                v-if="isStillCommentsToBeShown(comment.comments)"
                @click="viewMore(comment.comments)"
              >
                <i class="fas fa-share"></i> {{ comment.comments.length }} Reply
              </a>
              <div class="replies">
                <template v-for="(reply, index) in comment.comments">
                  <div v-if="reply.shown" :key="index" class="comment">
                    <img :src="reply.user.image_url" />
                    <div class="details">
                      <div class="user-name">{{ reply.user.name }}</div>
                      <div class="text">
                        <pre>{{ reply.text }}</pre>
                      </div>
                      <div class="actions">
                        <a class="link">like</a>
                        .
                        <a class="link">reply</a>
                        .
                        {{ reply.created_at }}
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>
        <div class="write-comment">
          <img :src="$store.state.user.me.image_url" />
          <div
            ref="write_comment"
            @keyup.enter.exact="comment($event)"
            contenteditable
            data-placeholder="Write a comment..."
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  computed: {
    likesCategorized() {
      let obj = {};
      for (let like of this.post.likes) {
        if (!obj[like.type]) {
          obj[like.type] = [];
        }
        obj[like.type].push(like);
      }
      return obj;
    },
  },
  props: {
    post: {
      required: true,
      type: Object,
      validator: function (post) {
        return post.text != "";
      },
    },
  },
  methods: {
    viewMore(comments) {
      this.flagCommentsToShow(comments, 2);
    },
    flagCommentsToShow(comments, number) {
      let flagged_no = 0;
      let i = comments.length - 1;
      while (i >= 0 && flagged_no < number) {
        if (!comments[i].shown) {
          comments[i].shown = true;
          flagged_no++;
        }
        i--;
      }
    },
    isStillCommentsToBeShown(comments) {
      for (let comment of comments) {
        if (!comment.shown) {
          return true;
        }
      }
      return false;
    },
    isAnyCommentsShown(comments) {
      for (let comment of comments) {
        if (comment.shown) {
          return true;
        }
      }
      return false;
    },
    showLikesBar(e) {
      let likeButton = e.target;
      window.$(likeButton).parents(".actions-container").addClass("show");
      window.$(likeButton).parents(".actions-container").find(".likes-bar").addClass("show");
    },
    hideLikesBar(e) {
      let likeButton = e.target;
      window.$(likeButton).parents(".actions-container").removeClass("show");
      window.$(likeButton).parents(".actions-container").find(".likes-bar").removeClass("show");
    },
    likePost(type) {
      window.axios.post(`/api/posts/${this.post.id}/like`, { type: type }).then((response) => {
        if (response.data.success) {
          this.post.isLiked = type;
          window.toastr.success(response.data.success);
        }
      });
    },
    unlikePost() {
      window.axios.post(`/api/posts/${this.post.id}/unlike`).then((response) => {
        if (response.data.success) {
          this.post.isLiked = "";
          window.toastr.success(response.data.success);
        }
      });
    },
    comment(event) {
      window.axios
        .post(`/api/posts/${this.post.id}/comments`, { text: event.target.innerText })
        .then((response) => {
          if (response.data.success) {
            let comment = response.data.data;
            comment.shown = true;
            comment.comments = [];
            this.post.comments.push(comment);
            window.toastr.success(response.data.success);
          }
        });
      window.$(event.target).trigger("blur");
      event.target.innerText = "";
    },
    focusComment() {
      if (this.isAnyCommentsShown(this.post.comments)) {
        this.$refs.write_comment.focus();
      }

      this.viewMore(this.post.comments);
      this.$nextTick(() => {
        this.$refs.write_comment.focus();
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.show-post {
  background: white;
  border-radius: 7px;
  margin-bottom: 1rem;
  box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important;
  pre {
    white-space: pre-wrap;
    font-family: inherit;
    font-size: 1rem;
  }
  .author {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    img {
      border-radius: 50%;
      height: 40px;
      margin-left: 6px;
      margin-right: 6px;
    }

    .details {
      .user-name {
        font-weight: bold;
      }
    }
  }

  .post-interaction {
    pre {
      white-space: pre-wrap;
      font-family: inherit;
      font-size: 0.9rem;
    }
    .interaction-details {
      border-inline-start: 2px var(--primary-color) solid;
      border-bottom: 1px #eee solid;
      display: flex;
      justify-content: space-between;
      align-items: center;
      .comments:hover {
        text-decoration: underline;
        cursor: pointer;
      }
      .likes {
        letter-spacing: -2px;
      }
    }

    .actions-container {
      overflow: hidden;
      position: relative;
      &.show {
        overflow: inherit;
      }
      .likes-bar {
        position: absolute;
        background: white;
        padding: 7px;
        border-radius: 1rem;
        display: flex;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important;
        margin: 0rem 5px;
        transition: left 0.3s, opacity 0.6s;
        top: -40px;
        left: -30px;
        opacity: 0;
        &.show {
          left: 0px;
          opacity: 1;
        }
        span {
          cursor: pointer;
        }
      }
      .actions {
        display: flex;
        padding: 7px;
        border-bottom: 1px #eee solid;

        button {
          flex-grow: 1;
          padding: 7px;
          &:hover {
            background: var(--grayed) !important;
          }
          &:focus {
            box-shadow: none;
          }
          &.active {
            color: var(--primary-color);
            font-weight: bold;
          }
        }
      }
    }
    .all-comments {
      padding: 7px;
      > a {
        margin-bottom: 7px;
        display: inline-block;
        font-size: 1rem;
      }
      .comment {
        display: flex;
        margin-bottom: 1rem;
        img {
          height: 40px;
          border-radius: 50%;
          margin-left: 3px;
          margin-right: 3px;
        }
        .details {
          background: #f0f2f5;
          padding: 7px;
          border-radius: 1rem;
          .user-name {
            font-weight: bold;
            // margin-bottom: 4px;
          }
        }
        .replies {
          margin-top: 7px;
        }
      }
      .write-comment {
        display: flex;
        align-items: center;
        img {
          height: 40px;
          border-radius: 50%;
          margin-left: 7px;
          margin-right: 7px;
        }
        div {
          background: var(--grayed);
          color: black;
          padding: 1rem;
          flex-grow: 1;
          border: none;
          outline: none;
          border-radius: 1rem;
          display: flex;
          &:empty:before {
            content: attr(data-placeholder);
            letter-spacing: 1px;
          }
        }
      }
    }
    a.link {
      color: gray;
      cursor: pointer;
      &:hover {
        text-decoration: underline;
      }
    }
  }
}
</style>