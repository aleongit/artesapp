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
                Clients
              </li>
            </ol>
          </nav>

          <!--card table-->
          <div class="card my-2">
            <div class="card-header d-flex">
              <span><i class="fas fa-list"></i> Clients</span>

              <!--loading spinner-->
              <SpinnerLoading :loading="loading"/>
              
              <!--crida modal amb mètode i objecte 'modal'-->
              <button
                type="button"
                class="btn btn-primary btn-sm ms-auto"
                @click="showModalAdd()"
              >
                <span class="fas fa-plus"></span> Nou Client
              </button>

              <!-- component modal client (nou, show, edit) -->
              <ModalClient
                :accio="this.accio" 
                :tipus="this.tipus" 
                :client="this.clientSelected" />
              
              <!-- component modal del client-->
              <ModalClientDel :client="this.clientDeleted" />

            </div>

            <div class="card-body">
              <h5 class="card-title">Clients!</h5>

              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Tipus</th>
                    <th>Acció</th>
                  </tr>
                </thead>
                <tbody>
                  <!--index no és client id, és un comptador -->
                  <!--v-for="(client, index)-->
                  <tr v-for="client in clients" :key="client.id">
                    <!--<td>{{index+1}}</td>-->
                    <td>{{ client.id }}</td>
                    <td>{{ client.nom }}</td>
                    <td>{{ client.tipus }}</td>
                    <td>

                      <!--show-->
                      <button
                        class="btn btn-sm btn-light mx-1"
                        @click="showModalShow(client)"
                      >
                        <span class="fa fa-eye"></span>
                      </button>

                      <!--editar-->
                      <button
                        class="btn btn-sm btn-primary mx-1"
                        @click="showModalEdit(client)"
                      >
                        <span class="fa fa-edit"></span>
                      </button>
                      
                      <!--eliminar-->
                      <button
                        class="btn btn-sm btn-danger mx-1"
                        @click="showModalDel(client)"
                      >
                        <span class="fa fa-trash"></span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer text-center text-muted bg-white border-0">
              <!--laravel-vue-pagination-->
              <Paginacio
                class = "justify-content-start"
                :data="laravelData"
                @pagination-change-page="getClients"
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

import ModalClient from "../components/clients/ModalClient.vue";
import ModalClientDel from "../components/clients/ModalClientDel.vue";

import * as clientService from "../services/client_service.js";

import { Modal } from "bootstrap"; //importar modal com a objecte
import SpinnerLoading from '../components/SpinnerLoading.vue';

export default {
  name: "clients",
  components: {
    Paginacio,
    ModalClient,
    ModalClientDel,
    SpinnerLoading,
  },
  data() {
    return {
      laravelData: {}, // Our data object that holds the Laravel paginator data
      clients: [],
      tipus: [],
      modal: "", //per obj modal crear
      modalDel: "", //per obj modal eliminar
      clientSelected: "", //per comunicar amb props
      clientDeleted: "", //per comunicar amb props
      loading: "",
      accio: "",
    };
  },
  //al muntar component
  mounted() {
    this.getClients();
    this.getTipusClients();
  },
  methods: {

    getTipusClients: async function () {
      try {
        const response = await clientService.getTipus();
        console.log(response);
        this.tipus = response.data;
        //console.log('tipus___' + this.tipus);

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },

    //adaptat a paginació
    getClients: async function (pag = 1) {

      //activem loading
      this.loading = true;

      try {
        const response = await clientService.mostra(pag);
        console.log(response);
        this.laravelData = response.data
        this.clients = response.data.data;
        //console.log(this.clients);

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
      this.modal = new Modal(document.getElementById("modalClient"), {});
      this.modal.show();
    },
    //modal show
    showModalShow(sel) {
      this.accio = "show";
      //{...} fa còpia
      this.clientSelected = {...sel}; //passo client al cridar modal i l'assigno a props de fill
      this.modal = new Modal(document.getElementById("modalClient"), {});
      this.modal.show();
    },
    showModalEdit(sel) {
      this.accio = "edit";
      //{...} fa còpia
      this.clientSelected = {...sel}; //passo client al cridar modal i l'assigno a props de fill
      //agafar i mostrar modal al fer click botó
      this.modal = new Modal(document.getElementById("modalClient"), {});
      this.modal.show();
    },
    showModalDel(sel) {
      //console.log(sel);
      this.clientDeleted = sel; //pont amb props
      this.modalDel = new Modal(document.getElementById("modalClientDel"), {});
      this.modalDel.show();
    },
  },
};
</script>

