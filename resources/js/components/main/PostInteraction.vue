<template>
  <div class="post-interaction">
    <template v-if="likes.length || comments.length">
      <div class="interaction-details px-4 py-2">
        <div class="likes">
          <template v-if="likesCategorized.like && likesCategorized.like.length">👍</template>
          <template v-if="likesCategorized.love && likesCategorized.love.length">❤️</template>
          <template v-if="likesCategorized.care && likesCategorized.care.length">🥰</template>
          <template v-if="likesCategorized.haha && likesCategorized.haha.length">😂</template>
          <template v-if="likesCategorized.wow && likesCategorized.wow.length">😯</template>
          <template v-if="likesCategorized.sad && likesCategorized.sad.length">😢</template>
          <template v-if="likesCategorized.angry && likesCategorized.angry.length">😡</template>
          <span class="mx-1">{{ likes.length }}</span>
        </div>
        <div class="comments" @click="viewMore(comments)">{{ comments.length }} Comments</div>
      </div>
    </template>

    <div class="actions">
      <button class="btn"><i class="far fa-thumbs-up"></i> Like</button>
      <button class="btn"><i class="far fa-comment-alt"></i> Comment</button>
      <button class="btn"><i class="fas fa-share"></i> Share</button>
    </div>

    <div class="all-comments" v-show="isAnyCommentsShown(comments)">
      <a class="link" @click="viewMore(comments)" v-if="isStillCommentsToBeShown(comments)">
        View more comments
      </a>
      <template v-for="(comment, index) in comments">
        <div v-if="comment.shown" :key="index" class="comment">
          <img :src="comment.user.image_url" />
          <div>
            <div class="details">
              <div class="user-name">{{ comment.user.name }}</div>
              <div class="text">{{ comment.text }}</div>
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
                    <div class="text">{{ reply.text }}</div>
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
    </div>
  </div>
</template>

<script>
export default {
  props: {
    commentsProp: {
      type: Array,
      required: true,
    },
    likesProp: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {};
  },
  computed: {
    comments() {
      return this.commentsProp;
    },
    likes() {
      return this.likesProp;
    },
    likesCategorized() {
      let obj = {};
      for (let like of this.likes) {
        if (!obj[like.type]) {
          obj[like.type] = [];
        }
        obj[like.type].push(like);
      }
      return obj;
    },
  },
  watch: {},
  methods: {
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
    viewMore(comments) {
      this.flagCommentsToShow(comments, 2);
    },
    flagCommentsToShow(comments, number) {
      let flagged_no = 0;
      let i = 0;
      while (i < comments.length && flagged_no < number) {
        if (!comments[i].shown) {
          comments[i].shown = true;
          flagged_no++;
        }
        i++;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.post-interaction {
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
  }
  a.link {
    color: gray;
    cursor: pointer;
    &:hover {
      text-decoration: underline;
    }
  }
}
</style>