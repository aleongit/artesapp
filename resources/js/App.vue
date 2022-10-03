<template>
  <main>
    <!--<h3>Hola Artesapp!</h3><button class="btn btn-primary">Funcionant la cosa!</button>-->
    
    <!-- agrupat a vista home -->
    <router-view></router-view>
    
    <!--per missatges flash-->
    <FlashMessage position="left top" />

  </main>
</template>

<script>
import * as auth from './services/auth_service';

export default {
  name: 'App',
  //abans de crear-se component, obtenir perfil usuari i cridar acció store
  beforeCreate: async function() {
    try {
      if (auth.isLoggedIn()) {
        const response = await auth.getProfile();
        console.log('before create in app');
        console.log(response);
        this.$store.dispatch('authenticate', response.data);
      }
      
    } catch (error) {
      //si error, fer logout
      auth.logoutUser();
    }
  }

};
</script>