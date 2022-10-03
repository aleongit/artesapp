<template>
  <div>
    <header>
        <Menu /> <!-- component menú-->
    </header>

    <!--content-->
    <div id="wrapper" class="shadow-sm">
      <router-view></router-view> <!--vistes filles home-->
      <!--vistes de rutes-->
    </div>

    <Footer /> <!-- component footer -->
  </div>
</template>

<script>
import Menu from "../../components/Menu.vue";
import Footer from "../../components/Footer.vue";

export default {
  components: {
    Menu,
    Footer,
  },
};
</script>