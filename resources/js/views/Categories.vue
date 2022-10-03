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
                Categories
              </li>
            </ol>
          </nav>

          <!--card table-->
          <div class="card my-2">
            <div class="card-header d-flex">
              <span><i class="fas fa-list"></i> Categories</span>

              <!--loading spinner-->
              <SpinnerLoading :loading="loading"/>
              
              <!--crida modal amb mètode i objecte 'modal'-->
              <button
                type="button"
                class="btn btn-primary btn-sm ms-auto"
                @click="showModal()"
              >
                <span class="fas fa-plus"></span> Nova Categoria
              </button>

              <!-- component modal producte (nou, edit) -->
              <ModalCategoria
                :accio="this.accio" 
                :categoria="this.categoriaSelected"/>

              <!-- component modal delete categoria-->
              <ModalCategoriaDel :categoria="this.categoriaDeleted" />

            </div>

            <div class="card-body">
              <h5 class="card-title">Categories!</h5>

              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Acció</th>
                  </tr>
                </thead>
                <tbody>
                  <!--index no és categoria id, és un comptador -->
                  <!--v-for="(categoria, index)-->
                  <tr v-for="categoria in categories" :key="categoria.id">
                    <!--<td>{{index+1}}</td>-->
                    <td>{{ categoria.id }}</td>
                    <td>{{ categoria.nom }}</td>
                    <td>
                      <button
                        class="btn btn-sm btn-primary mx-1"
                        @click="showModalEdit(categoria)"
                      >
                        <span class="fa fa-edit"></span>
                      </button>

                      <button
                        class="btn btn-sm btn-danger mx-1"
                        @click="showModalDel(categoria)"
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
                @pagination-change-page="getCategories"
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

import ModalCategoria from "../components/categories/ModalCategoria.vue";
import ModalCategoriaDel from "../components/categories/ModalCategoriaDel.vue";

import * as categoriaService from "../services/categoria_service.js";

import { Modal } from "bootstrap"; //importar modal com a objecte
import SpinnerLoading from '../components/SpinnerLoading.vue';

export default {
  name: "categories",
  components: {
    Paginacio,
    ModalCategoria,
    ModalCategoriaDel,
    SpinnerLoading,
  },
  data() {
    return {
      laravelData: {}, // Our data object that holds the Laravel paginator data
      categories: [],
      modal: "", //per obj modal crear
      modalDel: "", //per obj modal eliminar
      categoriaSelected: "", //per comunicar amb props
      categoriaDeleted: "", //per comunicar amb props
      loading: "",
      accio: "",
    };
  },
  //al muntar component
  mounted() {
    this.getCategories();
  },
  methods: {
    //adaptat a paginació
    getCategories: async function (pag = 1) {

      //activem loading
      this.loading = true;

      try {
        const response = await categoriaService.mostrar(pag);
        console.log(response);
        this.laravelData = response.data
        this.categories = response.data.data;
        //console.log(this.categories);

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
    showModal() {
      //utilitzem obj Modal bootstrap per poder utilitzar mètodes .hide i .show
      this.accio = "add";
      this.modal = new Modal(document.getElementById("modalCategoria"), {});
      this.modal.show();
    },
    showModalEdit(sel) {
      this.accio = "edit";
      //{...} fa còpia
      this.categoriaSelected = {...sel}; //passo categoria al cridar modal i l'assigno a props de fill
      //agafar i mostrar modal al fer click botó
      this.modal = new Modal(document.getElementById("modalCategoria"), {});
      this.modal.show();
    },
    showModalDel(sel) {
      //console.log(sel);
      this.categoriaDeleted = sel; //pont amb props
      this.modalDel = new Modal(document.getElementById("modalCategoriaDel"), {});
      this.modalDel.show();
    },

  },
};
</script>

