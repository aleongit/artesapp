
<template>

  <!-- Modal static -->
  <div
    class="modal fade"
    id="modalCategoria"
    tabindex="-1"
    data-bs-backdrop="static" 
    data-bs-keyboard="false"
    aria-labelledby="modalCategoriaLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">

           <h5 class="modal-title" id="modalCategoriaLabel"
            v-if="accio == 'add'">Nova Categoria</h5>
          <h5 class="modal-title" id="modalCategoriaLabel"
            v-else-if="accio == 'edit'">Edita Categoria <mark>{{categoria.nom}}</mark></h5>
          
          <!-- botó x -->
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            @click="modalTancat"
          ></button>

        </div>

        <form v-on:submit.prevent="accioForm">
          <div class="modal-body">

            <!-- nom -->
            <div class="mb-3">
              <label for="nom" class="form-label">Nom</label>
              <input
                type="text"
                class="form-control"
                id="nom"
                v-model="categoriaData.nom"
              />
              <!--retorn error nom -->
              <span class="invalid-feedback" role="alert" v-if="errors.nom">
                <strong>{{ errors.nom[0] }}</strong>
              </span>
            </div>

          </div>
          <div class="modal-footer">

            <!-- botó tancar -->
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="modalTancat"
            >
              Tanca
            </button>

            <button type="submit" class="btn btn-primary">
              <span><i class="fas fa-check"></i> Guarda</span>
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import * as categoriaService from "../../services/categoria_service.js";

export default {
  //name: 'App',
  data() {
    return {
      categoriaData: {
        nom: "",
      },
      errors: {}, //per errors validació form
    };
  },
  props: ["accio","categoria"],
  emits: [],
  computed: {},
    watch: {
      //copia del prop a data quan prop tingui valor excepte per 'add'
      categoria: function(newVal, oldVal) { // watch it
        console.log('Prop changed: ', newVal, ' | was: ', oldVal);
        if (this.accio != 'add') {
          console.log("CANVI PROP! es copia a categoria: " + newVal)
          this.categoriaData = {...newVal};
        }
      },
  },
  methods: {

    accioForm() {
      switch (this.accio) {
        case 'add':
          this.creaCategoria();
          break;
        case 'edit':
          this.updateCategoria();
          break;
      }
    },

    //funció assíncrona
    creaCategoria: async function () {
      //console.log('form submitted');

      //creem nou formData
      let form_dades = new FormData();

      //afegim camps form
      form_dades.append("nom", this.categoriaData.nom);
      //console.log(this.categoriaData);

      //prova response
      try {
        const response = await categoriaService.crear(form_dades);
        console.log(response);
        
        //amb this.$parent. s'accedeix a la propietat del pare
        //this.$parent.categories.push(response.data);

        //actualitzem productes tornant a demanar a backend
        this.$parent.getCategories();

        //neteja form
        this.categoriaData.nom = '';
        this.errors = '';

        //flash message
        this.$flashMessage.show({
          type: "success",
          //title: ":)",
          text: "Categoria stored ok! :)",
          time: 5000,
        });

        //tancar i netejar
        this.$parent.modal.hide();
        this.modalTancat();

      } catch (error) {
        //alert('ep algun error');
        console.log(error.response.status);

        //depenent codi error
        switch (error.response.status) {
          //error validació
          case 422:
            this.errors = error.response.data.errors;
            break;
          //error 500
          default: 
            //alert("ep algun error ha passat :(");
            //flash message
            this.$flashMessage.show({
              type: "error",
              //title: ":(",
              text: "alguna cosa ha anat malament :(",
              time: 5000,
            });
            break;
        }
      }
    },

    updateCategoria: async function () {
      //console.log('form submitted');
      console.log(this.categoriaData.nom);
      console.log(this.categoria.id); //prop

      //nou formData
      let form_dades = new FormData();
      form_dades.append('nom', this.categoriaData.nom);
      form_dades.append('_method', 'put'); //indicar mètode put
      
      //passem id props i dades
      try {
        const response = await categoriaService.update(this.categoria.id,form_dades);
        console.log(response);

        //actualitzem productes tornant a demanar a backend
        this.$parent.getCategories();

        //flash message
        this.$flashMessage.show({
          type: "success",
          text: "Categoria update ok! :)",
          time: 5000,
        });

        //tancar i netejar
        this.$parent.modal.hide();
        this.modalTancat();

      } catch (error) {
        //alert('ep algun error');
        console.log(error.response.status);

        //depenent codi error
        switch (error.response.status) {
          //error validació
          case 422:
            this.errors = error.response.data.errors;
            break;
          //error 500
          default: 
            this.$flashMessage.show({
              type: "error",
              text: error.response.data.message, //el missatge el dóna laravel
              time: 5000,
            });
        }
      }
    },

    //per quan es tanca modal bootstrap netegem
    modalTancat() {
    //quan tanca modal
    console.log('modal tancat');

    //neteja form
    this.categoriaData.nom = "";
    
    //neteja errors
    this.errors = "";
    
    //neteja producte seleccionat del pare no!
    //this.$parent.categoriaSelected = "";
    },
  },
};
</script>