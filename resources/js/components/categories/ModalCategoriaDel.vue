
<template>

  <!-- Modal -->
  <div
    class="modal fade"
    id="modalCategoriaDel"
    tabindex="-1"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">Eliminar Categoria 
            <strong>{{ categoria.nom }}</strong></h5>
          
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
          <button type="butoon" class="btn btn-primary" 
          @click="delCategoria(categoria.id)">
            <span><i class="fas fa-trash"></i> Elimina</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as categoriaService from "../../services/categoria_service.js";

export default {
  //name: 'App',
  data() {
    return {};
  },
  props: ["categoria"],
  created: function () {},
  emits: [],
  computed: {},
  methods: {

    //funció assíncrona
    delCategoria: async function (id) {
      console.log(id);

      //prova response
      try {
        const response = await categoriaService.eliminar(id);
        console.log(response);

        //actualitzem productes tornant a demanar a backend
        this.$parent.getCategories();

        //flash message
        this.$flashMessage.show({
          type: "success",
          text: response.data.message, //el missatge el dóna laravel
          time: 5000,
        });

        //al tenir 'objecte modal' podem tancar
        //this.myModal.hide();
        this.$parent.modalDel.hide();

      } catch (error) {
        //alert('ep algun error');
        //console.log(error.response.status);

        this.$flashMessage.show({
          type: "error",
          text: error.response.data.message, //el missatge el dóna laravel
          time: 5000,
        });
      }
    },
  },
};
</script>