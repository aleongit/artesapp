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
              <li class="breadcrumb-item">Admin</li>
              <li class="breadcrumb-item active" aria-current="page">
                Productes
              </li>
            </ol>
          </nav>

          <!--card table-->
          <div class="card my-2">
            <div class="card-header d-flex">
              <span><i class="fas fa-list"></i> Productes </span>

              <!--loading spinner-->
              <SpinnerLoading :loading="loading"/>

              <!--crida modal amb mètode i objecte 'modal'-->
              <button
                type="button"
                class="btn btn-primary btn-sm ms-auto"
                @click="showModalAdd()"
              >
                <span class="fas fa-plus"></span> Nou Producte
              </button>

              <!-- component modal producte (nou, edit) -->
              <ModalProducte
                :accio="this.accio" 
                :categories="this.categories"
                :producte="this.producteSelected" />
              
              <!-- component modal eliminar producte-->
              <ModalProducteDel :producte="this.producteDeleted" />

            </div>

            <div class="card-body">
              <h5 class="card-title">Productes!</h5>

              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th class="col-2">
                      <div class="form-floating">
                        <select
                          class="form-select"
                          id="categoria"
                          v-model="categoria"
                          @change="getProductes"
                        > 
                        <option selected >totes</option>
                          <option
                            v-for="(categoria, index) in categories"
                            :key="index"
                            :value="categoria"
                          >
                            {{ categoria.nom }}
                          </option>
                        </select>
                        <label for="categoria" class="form-label">Categoria</label>
                      </div>
                    </th>
                    <th>Nom</th>
                    <th>Preu</th>
                    <th>Imatge</th>
                    <th>Acció</th>
                  </tr>
                </thead>
                <tbody>
                  <!--index no és categoria id, és un comptador -->
                  <!--v-for="(categoria, index)-->
                  <template v-for="producte in productes" :key="producte.id">
                    <tr v-if="producte.categoria.nom == categoria.nom 
                                  || categoria=='totes'">
                      <!--<td>{{index+1}}</td>-->
                      <td>{{ producte.id }}</td>
                      <td>{{ producte.categoria.nom }}</td>
                      <td>{{ producte.nom }}</td>
                      <td>{{ producte.preu.toFixed(2) }}</td>
                      <td>
                        <img
                        class="h-50px"
                        :src="`${$store.state.serverPath}/${producte.imatge}`"
                        :alt="producte.nom">
                      </td>
  
                      <td>
                        <button
                          class="btn btn-sm btn-primary mx-1"
                          @click="showModalEdit(producte)"
                        >
                          <span class="fa fa-edit"></span>
                        </button>

                        <button
                          class="btn btn-sm btn-danger mx-1"
                          @click="showModalDel(producte)"
                        >
                          <span class="fa fa-trash"></span>
                        </button>
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
                @pagination-change-page="getProductes"
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

import ModalProducte from "../components/productes/ModalProducte.vue";
import ModalProducteDel from "../components/productes/ModalProducteDel.vue";

import * as producteService from "../services/producte_service.js";

import { Modal } from "bootstrap"; //importar modal com a objecte
import SpinnerLoading from '../components/SpinnerLoading.vue';

export default {
  name: "productes",
  components: {
    Paginacio,
    ModalProducte,
    ModalProducteDel,
    SpinnerLoading,
  },
  data() {
    return {
      laravelData: {}, // Our data object that holds the Laravel paginator data
      productes: [],
      categories: [],
      categoria: "totes", //per filtratge categoria
      modal: "", //per obj modal crear
      modalDel: "", //per obj modal eliminar
      categoriaSelected: "", //per comunicar amb props
      producteSelected: "", //
      producteDeleted: "", //
      loading: "",
      accio: "",
    };
  },
  //al muntar component
  mounted() {
    this.getCategories();
    this.getProductes();
    //this.getResults(); // paginació fetch initial results
  },
  methods: {

    getCategories: async function () {
      try {
        const response = await producteService.getCategories();
        console.log(response);
        this.categories = response.data;
        //console.log(this.categories);

      } catch (error) {
        this.$flashMessage.show({
          type: "error",
          text: "alguna cosa ha anat malament :(",
          time: 5000,
        });
      }
    },

    //adaptat a paginació
    getProductes: async function (pag = 1) {

      console.log('change filtre a '+ this.categoria.id );

      //activem loading
      this.loading = true;

      try {
        if(this.categoria == 'totes') {
          const response = await producteService.mostra(pag);
          console.log(response);
          this.laravelData = response.data
          this.productes = response.data.data;
          //console.log(this.categories);
        } else {
          const response = await producteService.mostraAmbCategoria(pag, this.categoria.id);
          console.log(response);
          this.laravelData = response.data
          this.productes = response.data.data;

        }

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
      this.modal = new Modal(document.getElementById("modalProducte"), {});
      this.modal.show();
    },
    showModalEdit(sel) {
      this.accio = "edit";
      this.producteSelected = {...sel}; //passo categoria al cridar modal i l'assigno a props de fill
      //agafar i mostrar modal al fer click botó
      this.modal = new Modal(document.getElementById("modalProducte"), {});
      this.modal.show();
    },
    showModalDel(sel) {
      this.producteDeleted = sel; //pont amb props
      this.modalDel = new Modal(document.getElementById("modalProducteDel"), {});
      this.modalDel.show();
    },
  },
};
</script>

