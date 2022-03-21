<template>
  <div class="col">
    <div class="card">
      <img :src="getPostCover(post)" class="card-img-top" alt="..." />
      <div class="card-body">
        <h5 class="card-title">{{ post.title }}</h5>
        <p class="card-text" v-html="post.content"></p>
        
        <em>
          Autore: {{ post.user.name }}; Data:
          {{ formatDate(post.created_at) }}
        </em>

      <strong v-if="post.category">{{ post.category.code }}</strong>
      </div>
      <div v-if="post.tags">
          <span
            v-for="tag in post.tags"
            :key="tag.id"
            class="badge bg-secondary me-2"
            >{{ tag.name }}</span>
        </div>

      <div class="card-footer text-end">
        <router-link :to="{ name:'posts.show', params: { post: post.slug } }">Dettagli<i class="fas fa-chevron-right ms-2"></i></router-link>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from "dayjs";

export default {
  props: {
    post: Object,
  },
  methods: {
    getPostCover(post) {
      return (
        post.coverImg ??
        "https://blumagnolia.ch/wp-content/uploads/2021/05/placeholder-126.png"
      );
    },
    formatDate(date) {
      console.log(date);
      return dayjs(date).format("DD/MM/YYYY HH:mm");
    },
  },
};
</script>