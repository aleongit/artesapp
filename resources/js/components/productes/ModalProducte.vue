
<template>

  <!-- Modal static -->
  <div
    class="modal fade"
    id="modalProducte"
    tabindex="-1"
    data-bs-backdrop="static" 
    data-bs-keyboard="false"
    aria-labelledby="modalProducteLabel"
    aria-hidden="true"
  >
    <!-- modal llarg a modal-dialog-->
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">

          <h5 class="modal-title" id="modalProducteLabel"
            v-if="accio == 'add'">Nou Producte</h5>
          <h5 class="modal-title" id="modalProducteLabel"
            v-else-if="accio == 'edit'">Edita Producte <mark>{{producte.nom}}</mark></h5>
          
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
            
            <!-- fila -- ____________________ -->
            <div class="row">
              <!-- col 1 _______________________ -->
              <div class="col">

                <!-- categoria -->
                <div class="col md-4">
                  <label for="categoria_id" class="form-label">Categoria</label>
                  <select class="form-select" 
                    id="categoria_id"
                    v-model="producteData.categoria_id">
                    <option value="" selected>Selecciona categoria</option>
                    <option v-for="categoria in categories" 
                      :key="categoria.id"
                      :value="categoria.id">{{categoria.nom}}</option>
                  </select>
                  
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.categoria_id">
                    <strong>{{ errors.categoria_id[0] }}</strong>
                  </span>
                </div>

                <!-- nom -->
                <div class="col md-4">
                  <label for="nom" class="form-label">Nom</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nom"
                    v-model="producteData.nom"
                  />
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.nom">
                    <strong>{{ errors.nom[0] }}</strong>
                  </span>
                </div>

                <!-- preu -->
                <div class="col md-4">
                  <label for="preu" class="form-label">Preu</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    id="preu"
                    v-model="producteData.preu"
                  />
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.preu">
                    <strong>{{ errors.preu[0] }}</strong>
                  </span>
                </div>
              
              </div>

              <!-- col 2 _______________________ -->
              <div class="col align-self-end">

                <!-- imatge -->
                <div class="col md-3">
                  <label for="imatge" class="form-label">Imatge</label>

                  <!-- si acció add -->
                  <div v-if="accio == 'add' && producteData.imatge" class="mb-2">
                    <img src="" ref="imgProducte_display" class="w-25">
                  </div>

                  <!-- si acció edit -->
                  <div v-if=" accio == 'edit' && producte.imatge" class="mb-2">
                    <img 
                    :src="`${$store.state.serverPath}/${producte.imatge}`"
                    ref="imgProducte_display" class="w-25">
                  </div>

                  <input
                    type="file"
                    class="form-control"
                    id="imatge"
                    v-on:change="addImatge"
                    ref="imgProducte"
                  />
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.imatge">
                    <strong>{{ errors.imatge[0] }}</strong>
                  </span>
                </div>

            </div>

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
import * as producteService from "../../services/producte_service.js";

export default {
  //name: 'App',
  data() {
    return {
      producteData: {
        categoria_id: "",
        nom: "",
        preu: 0 ,
        imatge: "",
      },
      errors: {}, //per errors validació form
    };
  },
  props: ["accio","categories","producte"],
  emits: [],
  computed: {},
  //watch prop
  watch: {
      //copia del prop a data quan prop tingui valor excepte per 'add'
      producte: function(newVal, oldVal) { // watch it
        console.log('Prop changed: ', newVal, ' | was: ', oldVal);
        if (this.accio != 'add') {
          console.log("CANVI PROP! es copia a producte: " + newVal)
          this.producteData = {...newVal};
        }
      },
  },
  methods: {

     accioForm() {
      switch (this.accio) {
        case 'add':
          this.creaProducte();
          break;
        case 'edit':
          this.updateProducte();
          break;
      }
    },

    addImatge() {
      //per adjuntar imatge
      //utilitzem referència de l'input html amb $refs
      this.producteData.imatge = this.$refs.imgProducte.files[0];
      let reader = new FileReader();
      reader.addEventListener('load', function() {
        this.$refs.imgProducte_display.src = reader.result;
      }.bind(this), false);
      reader.readAsDataURL(this.producteData.imatge);
    },

    //funció assíncrona
    creaProducte: async function () {
      //console.log('form submitted');

      //creem nou formData
      let form_dades = new FormData();

      //afegim camps form
      form_dades.append("categoria_id", this.producteData.categoria_id);
      form_dades.append("nom", this.producteData.nom);
      form_dades.append("preu", this.producteData.preu);
      form_dades.append("imatge", this.producteData.imatge);
      //console.log(this.catData);

      //prova response
      try {
        const response = await producteService.crear(form_dades);
        console.log(response);
        
        //amb this.$parent. s'accedeix a la propietat del pare
        //actualitzem productes tornant a demanar a backend
        this.$parent.getProductes();

        //flash message
        this.$flashMessage.show({
          type: "success",
          //title: ":)",
          text: "Producte stored ok! :)",
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
    
    updateProducte: async function () {
      //console.log('form submitted');
      console.log(this.producteData);
      console.log(this.producte.id);

      //nou formData
      let form_dades = new FormData();
  
      //afegim camps form
      form_dades.append("categoria_id", this.producteData.categoria_id);
      form_dades.append("nom", this.producteData.nom);
      form_dades.append("preu", this.producteData.preu);
      form_dades.append("imatge", this.producteData.imatge);

      form_dades.append('_method', 'put'); //indicar mètode put
      
      //prova response servei update, passem id props i dades
      try {
        const response = await producteService.update(
          this.producte.id,
          form_dades
        );
        console.log(response);
        console.log('id____' + response.data.id);

        //amb this.$parent. s'accedeix a la propietat del pare
        //actualitzem productes tornant a demanar a backend
        this.$parent.getProductes();

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
        console.log(error);
        //console.log(error.response.status);

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
            break;
        }
      }
    },

    //per quan es tanca modal bootstrap netegem
    modalTancat() {
    //quan tanca modal
    console.log('modal producte tancat');
    
    //neteja form
    this.producteData.categoria_id = "";
    this.producteData.nom = "";
    this.producteData.preu = "";
    this.producteData.imatge = "";

    //neteja files carregat
    document.getElementById('imatge').value = null;

    //neteja errors
    this.errors = "";
    
    //neteja pare no!! sinó watch prop fa coses rares
    //this.$parent.producteSelected = "";
    //this.$parent.accio = "";
    },
  },
};
</script>