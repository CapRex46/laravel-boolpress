<template>
  <div>
    <TheNavbar></TheNavbar>

    <div class="container py-4">
      <h1>Benvenuto in vue.js</h1>

      <ul>
        <li v-for="i in 10" :key="i">
          {{ i }}
        </li>
      </ul>

      <ExampleComponentVue></ExampleComponentVue>
    </div>

    <TheFooter></TheFooter>
  </div>
</template>

<script>
import ExampleComponentVue from "../components/ExampleComponent.vue";
import TheNavbar from "../components/TheNavbar.vue";
import TheFooter from "../components/TheFooter.vue";
export default {
  components: { ExampleComponentVue, TheNavbar, TheFooter },
};
</script>

<style></style>