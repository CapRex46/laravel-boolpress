<template>
  <footer class="border-top bg-light py-5">
    <div class="container">
      <h6>The footer</h6>
      <h4 v-if="user">Benvenuto {{ user.name }}</h4>
    </div>
  </footer>
</template>


<script>
export default {
  name: "TheFooter",
  data() {
    return {
      user: null,
    };
  },
  methods: {
    getStoredUser() {
      const storedUser = localStorage.getItem("user");
      if (storedUser) {
        this.user = JSON.parse(storedUser);
      } else {
        this.user = null;
      }
    },
  },
  mounted() {
    this.getStoredUser();
    window.addEventListener("storedUserChanged", () => {
      this.getStoredUser();
    });
  },
};
</script>

<style></style>