<template>
  <div id="content-wrapper">
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col">
          <!--breadcrumb-->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-angle-double-right px-2"></i>Home
              </li>
            </ol>
          </nav>

          <!--profile-->
          <div class="container-fluid my-2">
            <p class="fs-4">
              Welcome <strong>{{ profile.name }}</strong> amb rol
              <strong>{{ profile.role }}</strong
              >!
            </p>
          </div>

          <div class="container-fluid my-2">
            <div class="row justify-content-center align-items-center py-2">
              <div class="col-2">
                <img
                  :src="`${$store.state.serverPath}/img/TOMATO.png`"
                  alt=""
                  width="300"
                  height="300"
                  class="img-fluid pb-1 tomato"
                />
              </div>
              <div class="col-10">
                <p class="artesapp">L'Artesapp!</p>
                <p class="aleon">by aleon</p>
              </div>
            </div>
          </div>

          <div class="container-fluid my-2">
            <!--categories-->
            <div class="row align-items-center">
              <div class="col-2 text-end pe-4">
              <router-link 
              to="/home/admin/categories" exact
              class="text-decoration-none text-black">
                Categories</router-link>
                <i class="fas fa-boxes ms-2"></i>
              </div>
              <div class="col">
                <div class="progress">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    :style="`width:${comptadors.categories}%;`"
                    aria-valuenow="{{comptadors.categories}}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  >
                    {{ comptadors.categories }}
                  </div>
                </div>
              </div>
            </div>

            <!--productes-->
            <div class="row align-items-center">
              <div class="col-2 text-end pe-4">
                <router-link 
                to="/home/admin/productes" exact
                class="text-decoration-none text-black">
                Productes</router-link>
                <i class="fas fa-apple-alt ms-2"></i>
              </div>
              <div class="col">
                <div class="progress">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    :style="`width:${comptadors.productes}%;`"
                    aria-valuenow="{{comptadors.productes}}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  >
                    {{ comptadors.productes }}
                  </div>
                </div>
              </div>
            </div>

            <!--clients-->
            <div class="row align-items-center">
              <div class="col-2 text-end pe-4">
                <router-link 
                to="/home/clients" exact
                class="text-decoration-none text-black">
                Clients</router-link>
                <i class="fas fa-users ms-2"></i>
              </div>
              <div class="col">
                <div class="progress">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    :style="`width:${comptadors.clients}%;`"
                    aria-valuenow="{{comptadors.clients}}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  >
                    {{ comptadors.clients }}
                  </div>
                </div>
              </div>
            </div>

            <!--comandes-->
            <div class="row align-items-center">
              <div class="col-2 text-end pe-4">
                <router-link 
                to="/home/comandes" exact
                class="text-decoration-none text-black">
                Comandes</router-link>
                <i class="fas fa-paper-plane ms-2"></i>
              </div>
              <div class="col">
                <div class="progress">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    :style="`width:${comptadors.comandes}%;`"
                    aria-valuenow="{{comptadors.comandes}}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                  >
                    {{ comptadors.comandes }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
//per vuex4 usar store a component
import { useStore } from "vuex";

import * as dashboardService from "../services/dashboard_service";

export default {
  name: "dashboard",

  //setup per store vuex4
  setup() {
    //per accedir a store amb vuex4 -> useStore() / no es fa servir $store.state.
    const store = useStore();
    //si es fa d'aquesta manera, al refrescar navegador perdem 'test'
    //cal computar-ho al return, veure 'menu.vue'
    let profile = store.state.profile;

    return {
      profile,
      // access a state in computed function
      //count: computed(() => store.state.count),

      // access a getter in computed function
      //double: computed(() => store.getters.double)
    };
  },

  data() {
    return {
      comptadors: {
        categories: "",
        productes: "",
        clients: "",
        comandes: "",
      },
    };
  },

  //al muntar component
  mounted() {
    this.getComptadors();
  },

  methods: {
    getComptadors: async function () {
      try {
        const response = await dashboardService.getComptadors();
        console.log(response);
        this.comptadors.categories = response.data[0];
        this.comptadors.productes = response.data[1];
        this.comptadors.clients = response.data[2];
        this.comptadors.comandes = response.data[3];

        console.log("comptadors___" + this.comptadors);
      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },
  },
};
</script>

