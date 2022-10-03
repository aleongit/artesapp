<template>
  <!--spinner-->
  <div class="px-4">
    <div v-show="loading" class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script>
export default {
  //name: 'App',
  data() {
    return {};
  },
  props: ["loading"],
  created: function () {
    console.log("component loading");
    console.log(this.loading); 
  },
}
</script>