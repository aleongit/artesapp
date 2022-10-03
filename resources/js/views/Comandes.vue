<template>
  <div id="content-wrapper">
    <div class="container-fluid py-4">
      <div class="row justify-content-center">
        <div class="col">
          
          <!--breadcrumb-->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <i class="fas fa-angle-double-right px-2"></i>
                <router-link to="/home" exact>Home </router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">
                Comandes
              </li>
            </ol>
          </nav>

          <!--card table-->
          <div class="card my-2">
            <div class="card-header d-flex">
              <span><i class="fas fa-list"></i> Comandes</span>

              <!--loading spinner-->
              <SpinnerLoading :loading="loading"/>
              
              <!--crida modal amb mètode i objecte 'modal'-->
              <button
                type="button"
                class="btn btn-primary btn-sm ms-auto"
                @click="showModalAdd()"
              >
                <span class="fas fa-plus"></span> Nova Comanda
              </button>

              <!-- component modal comanda (nou, show, edit) -->
              <ModalComanda
                :accio="this.accio"
                :clients="this.clients" 
                :estats="this.estats"
                :categories_productes="this.categories_productes"
                :comanda="this.comandaSelected" />

              <!-- component modal comanda canvi estat-->
              <ModalComandaChange 
                :comanda="this.comandaChange"
                :estat="this.estatChange" />

            </div>

            <div class="card-body">
              <h5 class="card-title">Comandes!</h5>

              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th class="col-2">
                      <div class="form-floating">
                        <select
                          class="form-select"
                          id="estat"
                          v-model="estat"
                        > 
                        <option selected >totes</option>
                          <option
                            v-for="(estat, index) in estats"
                            :key="index"
                            :value="estat"
                          >
                            {{ estat }}
                          </option>
                        </select>
                        <label for="estat" class="form-label">Estat</label>
                      </div>
                    </th>
                    <th>Client</th>
                    <th>Data enviament</th>
                    <th>Import</th>
                    <th>Acció</th>
                  </tr>
                </thead>
                <tbody>
                  <!--index no és comanda id, és un comptador -->
                  <!--v-for="(comanda, index)-->
                  <template v-for="comanda in comandes" :key="comanda.id">
                   <tr v-if="comanda.estat==estat || estat=='totes'" 
                      :class="comanda.estat=='descartada'?classes.descartada:''">
                    <!--<td>{{index+1}}</td>-->
                    <td>{{ comanda.id }}</td>
                    <td v-if="comanda.estat=='pendent'">
                      <p :class="classes.pendent">{{ comanda.estat }}</p>
                    </td>
                    <td v-else>
                      <p class="text-center">{{ comanda.estat }}</p>
                    </td>
                    <td>{{ comanda.client.nom }}</td>
                    <td>{{ new Date(comanda.data_expedicio).toLocaleDateString() }}</td>
                    <td>{{ comanda.import.toFixed(2) }}</td>
                    <td>

                      <!--show-->
                      <button
                        class="btn btn-sm btn-light mx-1"
                        @click="showModalShow(comanda)"
                      >
                        <span class="fa fa-eye"></span>
                      </button>

                      <!--editar-->
                      <button
                        class="btn btn-sm btn-primary mx-1"
                        @click="showModalEdit(comanda)"
                      >
                        <span class="fa fa-edit"></span>
                      </button>

                      <!--completar-->
                      <button v-if="comanda.estat=='pendent'"
                        class="btn btn-sm btn-success mx-1"
                        @click="showModalChange(comanda, 'completada')"
                      >
                        <span class="fa fa-check"></span>
                      </button>
                      
                      <!--descartar-->
                      <button v-if="comanda.estat=='pendent'"
                        class="btn btn-sm btn-danger mx-1"
                        @click="showModalChange(comanda, 'descartada')"
                      >
                        <span class="fa fa-ban"></span>
                      </button>

                      <!--{{ comanda.comanda_items }}-->

                    </td>
                   </tr>
                  </template>
                </tbody>
              </table>
            </div>
            <div class="card-footer text-center text-muted bg-white border-0">
              <!--laravel-vue-pagination-->
              <Paginacio
                class = "justify-content-start"
                :data="laravelData"
                @pagination-change-page="getComandes"
              >
              </Paginacio>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import Paginacio from "laravel-vue-pagination"; //paginació

import ModalComanda from "../components/comandes/ModalComanda.vue";
import ModalComandaChange from "../components/comandes/ModalComandaChange.vue";

import * as comandaService from "../services/comanda_service.js";

import { Modal } from "bootstrap"; //importar modal com a objecte
import SpinnerLoading from '../components/SpinnerLoading.vue';

export default {
  name: "comandes",
  components: {
    Paginacio,
    ModalComanda,
    ModalComandaChange,
    SpinnerLoading,
  },
  data() {
    return {
      laravelData: {}, // Our data object that holds the Laravel paginator data
      estats: [],
      estat: "pendent",
      clients: [],
      categories_productes: [],
      comandes: [],
      modal: "", //per obj modal crear
      modalChange: "", //per obj modal eliminar
//TODO_compte al passar comanda per prop, veure com passar el detall comanda____________
      comandaSelected: "", //per comunicar amb props
      
      comandaChange: "", //per comunicar amb props
      estatChange: "",
      
      loading: "",
      accio: "",
      classes: {
        pendent: "bg-warning rounded-pill text-center",
        descartada: "text-decoration-line-through",
      }
    };
  },
  //al muntar component
  mounted() {
        this.getEstats();
        this.getClients();
        this.getCategoriesProductes();
        this.getComandes();
  },
  watch: {},
  methods: {
    getEstats: async function () {
      try {
        const response = await comandaService.getEstats();
        console.log(response);
        this.estats = response.data;
        console.log('estats___' + this.estats);

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },

    getClients: async function () {
      try {
        const response = await comandaService.getClients();
        console.log(response);
        this.clients = response.data;
        console.log('clients___' + this.clients);

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },

    getCategoriesProductes: async function () {
      try {
        const response = await comandaService.getCategoriesProductes();
        console.log(response);
        this.categories_productes = response.data;
        console.log('cats amb productes___' + this.categories_productes);

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },
    
    //TODO_____________________________segurament get comandes amb detall
    //adaptat a paginació
    getComandes: async function (pag = 1) {

      //activem loading
      this.loading = true;

      try {
        const response = await comandaService.mostra(pag);
        console.log(response);
        this.laravelData = response.data
        this.comandes = response.data.data;
        console.log('comandes___' + this.comandes);

        //parem spinner
        this.loading = false;

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },
    //modal crear
    showModalAdd() {
      //utilitzem obj Modal bootstrap per poder utilitzar mètodes .hide i .show
      this.accio = "add";
      this.modal = new Modal(document.getElementById("modalComanda"), {});
      this.modal.show();
    },
    //modal show
    showModalShow(sel) {
      this.accio = "show";
      //{...} fa còpia
      this.comandaSelected = {...sel}; //passo comanda al cridar modal i l'assigno a props de fill
      this.modal = new Modal(document.getElementById("modalComanda"), {});
      this.modal.show();
    },
    showModalEdit(sel) {
      this.accio = "edit";
      //{...} fa còpia
      this.comandaSelected = {...sel}; //passo comanda al cridar modal i l'assigno a props de fill
      //agafar i mostrar modal al fer click botó
      this.modal = new Modal(document.getElementById("modalComanda"), {});
      this.modal.show();
    },
    showModalChange(sel, estat) {
      console.log('canvi estat a '+ estat);
      this.comandaChange = sel; //pont amb props
      this.estatChange = estat;
      this.modalChange = new Modal(document.getElementById("modalComandaChange"), {});
      this.modalChange.show();
    },
  },
};
</script>

