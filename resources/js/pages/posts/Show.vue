<template>
    <div >
    <h1>{{ post.title }}</h1>

    <div class="mt-3 text-end d-flex align-items-center">
      <span v-if="post.category" class="badge bg-dark me-2">{{ post.category.code }}</span>
      <span  v-for="tag in post.tags" :key="tag.id" class="badge bg-primary me-2">{{ tag.name }}</span>

      <em v-if="post.user" class="ms-auto">{{ post.user.name }}</em>
    </div>

    <img :src="post.coverImg" alt="" class="post-img img-fluid mt-3" />

    <div v-html="post.content" class="mt-5 lead"></div>

    <div class="mt-4">
      <div>Data creazione: {{ createdAt }}</div>
      <div>Data ultimo aggiornamento: {{ updatedAt }}</div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
export default {
  data() {
    return {
      post: {},
    };
  },
  computed: {
    createdAt() {
      return dayjs(this.post.created_at).format("DD/MM/YY HH:mm");
    },
    updatedAt() {
      return dayjs(this.post.updated_at).format("DD/MM/YY HH:mm");
    },
  },
  methods: {
    async fetchPost() {
      try {
        const resp = await axios.get("/api/posts/" + this.$route.params.post);
        this.post = resp.data;
      } catch (er) {
        this.$router.replace({ name: "error" });
      }
    },
  },
  mounted() {
    console.log(this.$route.params.post);
    this.fetchPost();
  },
};
</script>

<style>
.post-img {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
}
</style>