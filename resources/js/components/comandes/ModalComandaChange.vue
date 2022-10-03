
<template>

  <!-- Modal -->
  <div
    class="modal fade"
    id="modalComandaChange"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">Canvi Comanda
            #<strong>{{ comanda.id }}</strong> a {{estat}}</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>

        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Tanca
          </button>
          <button type="butoon" class="btn btn-primary" @click="changeComanda(comanda.id)">
            <span><i class="fas fa-check"></i> Canvia</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as comandaService from "../../services/comanda_service";

export default {
  //name: 'App',
  data() {
    return {};
  },
  props: ["comanda","estat"],
  created: function () {},
  emits: [],
  computed: {},
  methods: {

  changeComanda: async function () {
    console.log(this.comanda.id);
    console.log(this.estat);

    //creem nou formData
    let form_dades = new FormData();

    //afegim camps form
    //només camps necessaris per backend
    form_dades.append("estat", this.estat);
    form_dades.append("_method", "put"); //indicar mètode put

    //prova response servei update, passem id props i dades
    try {

      const response = await comandaService.updateEstat(
        this.comanda.id,
        form_dades
      );
      console.log(response);
      
      //amb this.$parent. s'accedeix a la propietat del pare
      //actualitzem comandes
      this.$parent.getComandes();
      //flash message
      
      this.$flashMessage.show({
        type: "success",
        text: "Comanda update ok! :)",
        time: 5000,
      });
      
      //tancar i netejar
      this.$parent.modalChange.hide();

    } catch (error) {
      //alert('ep algun error');
      //console.log(error);
      //console.log(error.response.status);
      //console.log(error.response.data);

      //depenent codi error
      switch (error.response.status) {
        //error validació
        case 422:
          //this.errors = error.response.data.errors;
        this.$flashMessage.show({
            type: "error",
            text: "Mala gent!",
            time: 5000,
          });
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
  },
};
</script>