<template>
  <p class="mb-0">
    {{ username | capitalize }}
    <span v-if="feeling && Object.keys(feeling).length">
      {{ `is ${feeling.icon} feeling ${feeling.name}` }}
    </span>
    <span v-if="activity && Object.keys(activity).length">
      is
      <img :src="activity.icon" style="height: 20px; margin: 0px" />
      {{ `${activity.parent_name} ${activity.name}` }}
    </span>

    <span v-if="tagged && Object.keys(tagged).length"
      >with
      <span>{{ tagged_names.join(", ") }}</span>
    </span>
  </p>
</template>

<script>
export default {
  props: {
    username: {
      type: String,
    },
    feeling: {
      type: Object,
    },
    activity: {
      type: Object,
    },
    tagged: {
      type: Array,
    },
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
};
</script>

<style>
</style>