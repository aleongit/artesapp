<template>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="">
        <img
          :src="`${$store.state.serverPath}/img/TOMATO.png`"
          alt=""
          width="24"
          height="24"
          class="img-fluid pb-1"
        />
        L'Artesapp by aleon
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label=""
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav me-auto"></ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link to="/home" class="nav-link" exact> Home </router-link>
          </li>

          <!-- admin________________________________________ -->
          <li 
          class="nav-item dropdown"
          v-if="$store.state.profile.role == 'admin'">
            <a
              id=""
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              v-pre
            >
              Admin
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="">

              <router-link to="/home/admin/categories" class="dropdown-item" exact>
                Categories
              </router-link>

              <router-link to="/home/admin/productes" class="dropdown-item" exact>
                Productes
              </router-link>

              <a class="dropdown-item disabled" href=""> Obrador </a>

              <a class="dropdown-item disabled" href=""> Usuaris </a>

            </div>
          </li>

          <li class="nav-item">
            <router-link to="/home/clients" class="nav-link" exact>
              Clients
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/home/comandes" class="nav-link" exact>
              Comandes
            </router-link>
          </li>

          <li class="nav-item">
            <a class="nav-link disabled" href="">Producció</a>
          </li>

          <li class="nav-item">
            <a class="nav-link disabled" href="">Stock</a>
          </li>

          <li class="nav-item dropdown">
            <a
              id="navbarDropdown"
              class="nav-link dropdown-toggle btn-light fw-bold"
              role="button"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
            {{ nom_user }}
            </a>

            <div
              class="dropdown-menu dropdown-menu-end"
              aria-labelledby="navbarDropdown"
            >
              <!-- editar perfil config -->
              <router-link to="/home/user/profile" class="dropdown-item" exact>
                Profile
              </router-link>

              <!-- Change password -->
              <router-link to="/home/user/change-password" class="dropdown-item" exact>
                Change Password
              </router-link>

              <!-- logout -->
              <button class="dropdown-item" @click="logout">Logout</button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import * as auth from "../services/auth_service";

//per vuex4 usar store a component
import { computed } from 'vue';
import { useStore } from "vuex";

export default {
  //inicialitzar amb setup per store vuex4
  setup() {
    //per accedir a store amb vuex4 -> useStore() / No es fa servir $store.state.
    const store = useStore();
    //let nom_user = store.state.profile.name;
    return {
      //nom_user,
      //cal computar dins el setup per qualsevol canvi al profile de l'store, 
      //per exemple, si es refresca navegador es perdria profile
      nom_user: computed(() => store.state.profile.name),
    };
  },
  methods: {
    logout: async function () {
      auth.logoutUser();
      auth.delToken();
      this.$router.push("/login"); //un cop logout, ruta a login
    },
  },

};
</script>