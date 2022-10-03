
<template>
  <!-- Modal static-->
  <div
    class="modal fade"
    id="modalClient"
    tabindex="-1"
    data-bs-backdrop="static" 
    data-bs-keyboard="false"
    aria-labelledby="modalClientLabel"
    aria-hidden="true"
  >
  
    <!-- modal llarg a modal-dialog-->
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">

          <h5 class="modal-title" id="modalClientLabel"
          v-if="accio == 'add'">Nou Client</h5>
          <h5 class="modal-title" id="modalClientLabel"
          v-else-if="accio == 'show'">Info Client <mark>{{client.nom}}</mark></h5>
          <h5 class="modal-title" id="modalClientLabel"
          v-else-if="accio == 'edit'">Edita Client <mark>{{client.nom}}</mark></h5>
          
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

                <!-- nom -->
                <div class="mb-3">
                  <label for="nom" class="form-label">Nom</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nom"
                    v-model="clientData.nom"
                    :disabled="esInfo"
                  />
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.nom">
                    <strong>{{ errors.nom[0] }}</strong>
                  </span>
                </div>

                <!-- email -->
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="clientData.email"
                    :disabled="esInfo"
                  />
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.email">
                    <strong>{{ errors.email[0] }}</strong>
                  </span>
                </div>

                <!-- telèfon -->
                <div class="mb-3">
                  <label for="telefon" class="form-label">Telèfon</label>
                  <input
                    type="text"
                    class="form-control"
                    id="telefon"
                    maxlength="9"
                    v-model="clientData.telefon"
                    :disabled="esInfo"
                  />
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.telefon">
                    <strong>{{ errors.telefon[0] }}</strong>
                  </span>
                </div>

              </div>

              <!-- col 2 _______________________ -->
              <div class="col">

                <!-- tipus -->
                <div class="mb-3">
                  <label for="tipus" class="form-label">Tipus client</label>
                  <select
                    class="form-select"
                    id="tipus"
                    v-model="clientData.tipus"
                    :disabled="esInfo"
                  >
                    <option value="" selected>Selecciona tipus</option>
                    <option v-for="(el, index) in tipus" :key="index" :value="el">
                      {{ el }}
                    </option>
                  </select>
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.tipus">
                    <strong>{{ errors.tipus[0] }}</strong>
                  </span>
                </div>

                <!-- nota -->
                <div class="mb-3">
                  <label for="nota" class="form-label">Nota</label>
                  <textarea
                    class="form-control nota"
                    id="nota"
                    rows="5"
                    v-model="clientData.nota"
                    :disabled="esInfo"
                  ></textarea>
                  <!--retorn error -->
                  <span class="invalid-feedback" role="alert" v-if="errors.nota">
                    <strong>{{ errors.nota[0] }}</strong>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">

            <!--tancar-->
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
              @click="modalTancat"
            >
              Tanca
            </button>
            <button type="submit" class="btn btn-primary"
            :disabled="esInfo">
              <span><i class="fas fa-check"></i> Guarda</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import * as clientService from "../../services/client_service";

export default {
  //name: 'App',
  data() {
    return {
      clientData: {
        tipus: "",
        nom: "",
        email: "",
        telefon: "",
        nota: "",
      },
      errors: {}, //per errors validació form
      esInfo: false,
    };
  },
  props: ["accio","tipus","client"],
  emits: [],
  computed: {},
  //watch props
  watch: { 
      //copia del prop a data quan prop tingui valor excepte per 'add'
      client: function(newVal, oldVal) { // watch it
        console.log('Prop changed: ', newVal, ' | was: ', oldVal);
        if (this.accio != 'add') {
          this.clientData = {...newVal};
        }
      },
      //canvia boleana per 'disable' inputs quan acció és 'show'
      accio: function(newVal, oldVal) { // watch it
        console.log('Prop changed: ', newVal, ' | was: ', oldVal);
        this.accio == 'show' ? this.esInfo = true : this.esInfo = false;
        console.log(this.esInfo);
      },
    },
  methods: {
    accioForm() {
      switch (this.accio) {
        case 'add':
          this.creaClient();
          break;
        case 'edit':
          this.updateClient();
          break;
      }
    },

    //funció assíncrona
    creaClient: async function () {
      //console.log('form submitted');

      //creem nou formData
      let form_dades = new FormData();

      //afegim camps form
      form_dades.append("tipus", this.clientData.tipus);
      form_dades.append("nom", this.clientData.nom);
      form_dades.append("email", this.clientData.email);
      form_dades.append("telefon", this.clientData.telefon);
      form_dades.append("nota", this.clientData.nota);
      //console.log(this.categoriaData);

      //prova response
      try {
        const response = await clientService.crear(form_dades);
        console.log(response);

        //actualitzem productes tornant a demanar a backend
        this.$parent.getClients();

        //flash message
        this.$flashMessage.show({
          type: "success",
          //title: ":)",
          text: "Client stored ok! :)",
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
    updateClient: async function () {
      console.log(this.client.id);

      //nou formData
      let form_dades = new FormData();
  
      //afegim camps form
      form_dades.append("tipus", this.clientData.tipus);
      form_dades.append("nom", this.clientData.nom);
      form_dades.append("email", this.clientData.email);
      form_dades.append("telefon", this.clientData.telefon);
      form_dades.append("nota", this.clientData.nota);
      //console.log(this.categoriaData);

      form_dades.append('_method', 'put'); //indicar mètode put
      
      //prova response servei update, passem id props i dades
      try {
        const response = await clientService.update(
          this.client.id,
          form_dades
        );
        console.log(response);

        //amb this.$parent. s'accedeix a la propietat del pare
        //actualitzem clients
        this.$parent.getClients();

        //flash message
        this.$flashMessage.show({
          type: "success",
          text: "Client update ok! :)",
          time: 5000,
        });

        //tancar i netejar
        this.$parent.modal.hide();
        this.modalTancat();

      } catch (error) {
        //alert('ep algun error');
        //console.log(error);
        //console.log(error.response.status);
        //console.log(error.response.data);

        //depenent codi error
        switch (error.response.status) {
          //error validació
          case 422:
            this.errors = error.response.data.errors;
            //console.log(this.errors);
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
    console.log('modal client tancat');

    //neteja data
    this.clientData.tipus = "";
    this.clientData.nom = "";
    this.clientData.email = "";
    this.clientData.telefon = "";
    this.clientData.nota = "";
    
    //neteja errors
    this.errors = "";
    
    //neteja pare no!
    //this.$parent.clientSelected = "";
    //this.$parent.accio = "";
    },
  },
};
</script>