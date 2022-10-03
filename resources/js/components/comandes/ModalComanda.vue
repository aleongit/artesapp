<template>
  <!-- Modal static-->
  <div
    class="modal fade"
    id="modalComanda"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    aria-labelledby="modalComandaLabel"
    aria-hidden="true"
  >
    <!-- modal fullscreen a modal-dialog-->
    <div class="modal-dialog modal-fullscreen">
      <div class="container-fluid px-0">
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              id="modalComandaLabel"
              v-if="accio == 'add'"
            >
              Nova Comanda
            </h5>
            <h5
              class="modal-title"
              id="modalComandaLabel"
              v-else-if="accio == 'show'"
            >
              Info Comanda <mark>{{ comanda.id }}</mark>
            </h5>
            <h5
              class="modal-title"
              id="modalComandaLabel"
              v-else-if="accio == 'edit'"
            >
              Edita Comanda <mark>{{ comanda.id }}</mark>
            </h5>

            <!--missatges alert-->
            <div class="missatge" v-show="!missatges.alert_ini">
              <div
                class="
                  border-start border-warning border-5
                  d-flex
                  align-items-center
                  mx-2
                  mb-0
                  px-1
                "
                role="alert"
              >
                <div>
                  <span>
                    {{ missatges_text.alert_ini }}
                  </span>
                </div>
              </div>
            </div>

            <div class="ms-auto">
              
              <!--tancar-->
              <button
                type="button"
                class="btn btn-secondary mx-2"
                data-bs-dismiss="modal"
                @click="modalTancat"
              >
                Tanca
              </button>

              <!--guardar amb atribut form, per cridar form al estar fora-->
              <button
                type="submit"
                class="btn btn-primary"
                :class="classes.boto_guardar"
                :disabled="esInfo || !comandaOK"
                v-show="!esInfo"
                form="form"
              >
                <span><i class="fas fa-check"></i> Guarda Comanda</span>
              </button>
            </div>
          </div>

          <div class="modal-body pb-0">
            <div class="container-fluid">
              <form v-on:submit.prevent="accioForm" id="form">
                
                <!-- fila 1-- _______________________________________ -->
                <div class="row">
                  
                  <!-- client -->
                  <div class="col-6">
                    <div class="form-floating">
                      <select
                        class="form-select"
                        id="client_id"
                        v-model="comandaData.client"
                        :disabled="esInfo"
                      >
                        <option selected disabled>Selecciona client</option>
                        <option
                          v-for="client in clients"
                          :key="client.id"
                          :value="client"
                        >
                          {{ client.nom }}
                        </option>
                      </select>
                      <label for="client_id" class="form-label">Client</label>
                      <!--retorn error -->
                      <span
                        class="invalid-feedback"
                        role="alert"
                        v-if="errors.client_id"
                      >
                        <strong>{{ errors.client_id[0] }}</strong>
                      </span>
                    </div>
                  </div>

                  <!-- data comanda-->
                  <div class="col">
                    <div class="form-floating">
                      <input
                        type="date"
                        class="form-control"
                        id="data_comanda"
                        v-model="comandaData.data_comanda"
                        :disabled="true"
                      />
                      <label for="data_comanda" class="form-label"
                        >Data Comanda</label
                      >
                      <!--retorn error -->
                      <span
                        class="invalid-feedback"
                        role="alert"
                        v-if="errors.data_comanda"
                      >
                        <strong>{{ errors.data_comanda[0] }}</strong>
                      </span>
                    </div>
                  </div>

                  <!-- data expedició-->
                  <div class="col">
                    <div class="form-floating">
                      <input
                        type="date"
                        class="form-control"
                        id="data_expedicio"
                        v-model="comandaData.data_expedicio"
                        :disabled="esInfo"
                      />
                      <label for="data_expedicio" class="form-label"
                        >Data Enviament</label
                      >

                      <!--retorn error -->
                      <span
                        class="invalid-feedback"
                        role="alert"
                        v-if="errors.data_expedicio"
                      >
                        <strong> {{ errors.data_expedicio[0] }}</strong>
                      </span>
                    </div>
                  </div>

                  <!-- estat -->
                  <div class="col">
                    <div class="form-floating">
                      <select
                        class="form-select"
                        id="estat"
                        v-model="comandaData.estat"
                        :disabled="!esEdit"
                      >
                        <option
                          v-for="(estat, index) in estats"
                          :key="index"
                          :value="estat"
                        >
                          {{ estat }}
                        </option>
                      </select>
                      <label for="estat" class="form-label">Estat</label>

                      <!--retorn error -->
                      <span
                        class="invalid-feedback"
                        role="alert"
                        v-if="errors.client_id"
                      >
                        <strong>{{ errors.client_id[0] }}</strong>
                      </span>
                    </div>
                  </div>
                </div>

                <!-- fila alert -->
                <div class="row d-flex justify-content-end">
                  <div class="col col-md-4">
                    <!--missatges alert-->
                    <div class="missatge" v-show="!missatges.alert_data_exp">
                      <div
                        class="
                          border-start border-danger border-5
                          d-flex
                          align-items-center
                          mt-1
                          px-1
                        "
                        role="alert"
                      >
                        <div>
                          <span>
                            {{ missatges_text.alert_data_exp }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- fila 2-- _______________________________________ -->
                <div class="row py-2">
                  
                  <!-- nota client-->
                  <div class="col">
                    <div class="mb-3">
                      <label for="nota_client" class="form-label"
                        >Nota Client</label>
                      <p>{{comandaData.client.nota}}</p>

                    </div>
                  </div>

                  <!-- nota comanda-->
                  <div class="col">
                    <div class="form-floating mb-3">
                      <textarea
                        class="form-control nota"
                        id="nota"
                        rows="2"
                        placeholder="Nota Comanda"
                        v-model="comandaData.nota"
                        :disabled="esInfo"
                      ></textarea>
                      <label for="nota" class="form-label">Nota Comanda</label>
                      <!--retorn error -->
                      <span
                        class="invalid-feedback"
                        role="alert"
                        v-if="errors.nota"
                      >
                        <strong>{{ errors.nota[0] }}</strong>
                      </span>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!--form afegir línia -->
          <!--mostrem si entrat client/data exp o si no és acció info-->
          <div class="modal-body py-1 px-0" v-show="!esInfo && iniOK">
            <div class="modal-header pt-1">
              <h5 class="modal-title" id="modalComandaLabel">Detall Comanda</h5>

              <!--missatges alert-->
              <div class="missatge" v-show="!missatges.alert_detall">
                <div
                  class="
                    border-start border-warning border-5
                    d-flex
                    align-items-center
                    mx-2
                    mb-0
                    px-1
                  "
                  role="alert"
                >
                  <div>
                    <span>
                      {{ missatges_text.alert_detall }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- activar botó quan camps item omplerts -->
              <div class="ms-auto">
                <button
                  type="submit"
                  class="btn btn-primary"
                  :class="classes.boto_add"
                  :disabled="!itemOK"
                  form="form_item"
                >
                  <span class="fas fa-plus"></span> Afegir Detall
                </button>
              </div>
            </div>

            <div class="container-fluid">
              <form v-on:submit.prevent="addItem" id="form_item">
                <div class="row bg-light p-2">
                  <div class="col">
                    
                    <!-- categoria -->
                    <div class="form-floating">
                      <select
                        class="form-select"
                        id="categoria"
                        aria-label="Categoria"
                        v-model="itemData.categoria"
                        :disabled="esInfo"
                      >
                        <!-- prop categories_productes i guarda a 'item' data-->
                        <option selected disabled>Selecciona Categoria</option>
                        <option
                          v-for="categoria in categories_productes"
                          :key="categoria.id"
                          :value="categoria"
                        >
                          {{ categoria.nom }}
                        </option>
                      </select>
                      <label for="categoria">Categoria</label>
                    </div>
                  </div>

                  <div class="col">
                    <!-- producte -->
                    <div class="form-floating">
                      <select
                        class="form-select"
                        id="producte"
                        aria-label="Producte"
                        v-model="itemData.producte"
                        :disabled="esInfo"
                      >
                        <!--agafa de 'item' data, categoria guardada prèviament -->
                        <option selected disabled>Selecciona Producte</option>
                        <option
                          v-for="producte in itemData.categoria.productes"
                          :key="producte.id"
                          :value="producte"
                        >
                          {{ producte.nom }}
                        </option>
                      </select>
                      <label for="producte">Producte</label>
                    </div>
                  </div>

                  <div class="col">
                    <!-- unitats -->
                    <div class="form-floating">
                      <input
                        type="number"
                        step="1"
                        class="form-control"
                        id="unitats"
                        placeholder="Unitats"
                        v-model="itemData.unitats"
                        :disabled="esInfo"
                      />
                      <label for="floatingInput">Unitats</label>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!--llista línies-->
          <div class="modal-body">
            <div class="container-fluid">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Categoria</th>
                    <th>Producte</th>
                    <th></th>
                    <th>Unitats</th>
                    <th>Preu</th>
                    <th>Import</th>
                    <th class="text-end">Acció</th>
                  </tr>
                </thead>
                <tbody>
                  <!--index no és categoria id, és un comptador --><!--v-for="(categoria, index)-->
                  <tr v-for="(item, index) in comandaData.comanda_items" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.producte.categoria.nom }}</td>
                    <td>{{ item.producte.nom }}</td>
                    <td>
                      <img
                      class="h-50px"
                      :src="`${$store.state.serverPath}/${item.producte.imatge}`"
                      :alt="item.producte.nom">
                    </td>
                    <td>{{ item.unitats }}</td>
                    <td>{{ item.preu.toFixed(2) }}</td>
                    <td>{{ item.import.toFixed(2) }}</td>
                    <td class="text-end">
                      <button
                        class="btn btn-sm btn-danger mx-1"
                        :disabled="esInfo"
                        v-show="!esInfo"
                        @click="delItem(index)"
                      >
                        <span class="fa fa-trash"></span>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr class="fw-bolder">
                  <td>Totals</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>{{ totalUnitats }}</td>
                  <td></td>
                  <td>{{ totalImport.toFixed(2) }}</td>
                  <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <div class="modal-footer">* MODAL FOOTER *</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as comandaService from "../../services/comanda_service";

export default {
  //name: 'App',
  components: {},
  data() {
    return {
      comandaData: {
        client_id: "",
        estat: "",
        data_comanda: "",
        data_expedicio: "",
        import: "",
        nota: "",
        //TODO_______compte items, veure com laravel retorna la relació entre comanda i detall
        comanda_items: [],
        client: "Selecciona client", //obj info client
      },

      //per guardar info form detall (categoria, producte) provinent de props
      itemData: {  
        categoria: "Selecciona Categoria", //obj
        producte: "Selecciona Producte", //obj
        unitats: "",
      },
      errors: {}, //per errors validació form
      
      esInfo: false,
      esEdit: false,

      missatges: {
        alert_ini: false,
        alert_detall: false,
        alert_data_exp: true,
      },
      missatges_text: {
        alert_ini: "Selecciona Client i Data d'enviament",
        alert_detall: "",
        alert_data_exp: "Data Expedició >= Avui",
      },
      classes: {
        boto_guardar: "btn-light text-muted",
        boto_add: "btn-light text-muted"
      }
    };
  },
  props: ["accio", "estats", "comanda", "clients", "categories_productes"],
  emits: [],
  computed: {
    //controlem si afegit client i data exp per activar detall
    iniOK() {
      return this.comandaData.client.id && this.comandaData.data_expedicio
        ? true
        : false;
    },

    //controlem si item preparat
    //activem botó afegir item
    itemOK() {
      return this.itemData.categoria.id &&
        this.itemData.producte.id &&
        this.itemData.unitats > 0
        ? true
        : false;
    },
    comandaOK() {
      //controlem si comanda preparada
      //activem botó guardar comanda
      return this.comandaData.client.id &&
        this.comandaData.data_expedicio &&
        this.comandaData.comanda_items.length > 0
        ? true
        : false;
    },
    totalImport() {
      //Sum of values in an object array
      /*
      let initialValue = 0
      let sum = [{x: 1}, {x: 2}, {x: 3}].reduce(
        (previousValue, currentValue) => previousValue + currentValue.x
        , initialValue)
        console.log(sum) // logs 6
      */
      let ini = 0;
      let sum = this.comandaData.comanda_items.reduce(
        (pre, current) => pre + current.import,
        ini
      );
      console.log(sum);
      return sum;
    },
    totalUnitats() {
      return this.comandaData.comanda_items.reduce(
        (pre, current) => pre + current.unitats,
        0
      );
    },
  },
  //watch props
  watch: {
    //watch prop comanda
    comanda: function (newVal, oldVal) {
      // watch it
      console.log("Prop changed: ", newVal, " | was: ", oldVal);
      if (this.accio != "add") {
        this.comandaData = { ...newVal };
      }
    },

    //watch prop accio
    accio: function (newVal, oldVal) {
      // watch it
      console.log("Prop changed: ", newVal, " | was: ", oldVal);
      //canvia boleana per 'disable' inputs quan acció és 'show'

      //add
      if (this.accio == "add") {
        //ini comanda
        this.comandaData.client = "Selecciona client";
        this.comandaData.estat = "pendent";
        this.comandaData.data_comanda = new Date().toISOString().substr(0, 10);
        this.esInfo = false;
        this.esEdit = false;
        this.missatges_text.alert_ini = "Selecciona Client i Data d'enviament";
        //this.comandaData.comanda_items = [];

        //edit
      } else if (this.accio == "edit") {
        this.esInfo = false;
        this.esEdit = true;
        this.missatges_text.alert_ini = "Edita Comanda";

        //show
      } else if (this.accio == "show") {
        this.esInfo = true;
        this.esEdit = false;
        this.missatges_text.alert_ini = "Info Comanda";
      }
      //this.accio == "show" ? (this.esInfo = true) : (this.esInfo = false);
      //console.log(this.esInfo);
    },
    //watch d'un COMPUTED
    iniOK(newVal) {
      // si acció ADD
      if (this.accio == "add") {
        if (newVal) {
          //si nou val és true
          this.missatges_text.alert_detall = "Afegeix detall de comanda";
          this.missatges.alert_ini = true;
        } else {
          //si fals
          this.missatges.alert_ini = false;
        }

        // si acció EDIT
      } else if (this.accio == "edit") {
        if (newVal) {
          //si nou val és true
          this.missatges_text.alert_detall = "Edita detall de comanda";
          //this.missatges.alert_ini = true;
        } else {
          //si fals
          //this.missatges.alert_ini = false;
        }
      }
    },
    //watch d'un COMPUTED
    itemOK(newVal) {
      if (newVal) {
        this.classes.boto_add = "btn-primary";
      } else {
        this.classes.boto_add = "btn-light text-muted";
      }
    },

    //watch d'un COMPUTED
    comandaOK(newVal) {
      if (newVal) {
        //alert("new valor TRUE comandaOK!");
        this.missatges.alert_detall = true;
        this.classes.boto_guardar = "btn-primary";
      } else {
        //alert("new valor FALSE comandaOK!");
        this.missatges.alert_detall = false;
        this.classes.boto_guardar = "btn-light text-muted";
      }
    },

    //!!! whatch d'element dins objecte! entre cometes i function
    "comandaData.data_expedicio": function (newVal, oldVal) {
      console.log("canvi data expedició!");
      console.log(newVal);
      //console.log(oldVal);
      //.setHours(0, 0, 0, 0) without hours
      let data = new Date(newVal).setHours(0, 0, 0, 0);
      let avui = new Date().setHours(0, 0, 0, 0); //avui

      if (data < avui || newVal == "") {
        this.comandaData.data_expedicio = "";
        this.missatges.alert_data_exp = false;
      } else {
        this.missatges.alert_data_exp = true;
      }
      console.log("data exp" + this.missatges.alert_data_exp);
    },
  },
  methods: {
    accioForm() {
      switch (this.accio) {
        case "add":
          this.creaComanda();
          break;
        case "edit":
          this.updateComanda();
          break;
      }
    },

    //afegir item form detall a llista items
    addItem() {
      console.log("add item!");
      //validació prèvia computant activació botó

      //push item a llista (valors per bd + d'altres per mostrar a front)
      //creem mateixa estructura que el que retorna laravel
      const nouItem = {
        producte: {
            id: this.itemData.producte.id,
            nom: this.itemData.producte.nom,
            imatge: this.itemData.producte.imatge,
            categoria : {
               id: this.itemData.categoria.id,
               nom: this.itemData.categoria.nom,
            }
        },
        unitats: this.itemData.unitats,
        preu: this.itemData.producte.preu,
        import: this.itemData.unitats * this.itemData.producte.preu,
      };
      console.log(nouItem);

      //neteja form detall
      this.itemData.categoria = "Selecciona Categoria";
      this.itemData.producte = "Selecciona Producte";
      this.itemData.unitats = "";

      //add a array items data comanda, passant per còpia valors
      let newArray = [...this.comandaData.comanda_items];
      newArray.push(nouItem);
      this.comandaData.comanda_items = newArray;

    },

    delItem(index) {
      //console.log("del item! " + item + " index " + index);
      //this.comandaData.comanda_items.splice(index); // Removes all elements starting at index 2

      //amb splice modificava l'array i modificava prop!!!
      //canvi a filter i tornaf a copiar a data (sembla que ok!)

      let newArray = this.comandaData.comanda_items.filter( (item, i) => i !== index);
      console.log(newArray);

      this.comandaData.comanda_items = newArray;

    },

    //preparar FormData per enviar dades a laravel (per crear i editar)
    preparaDades() {
      
      //creem nou formData
      let form_dades = new FormData();

      //afegim camps form
      //només camps necessaris per backend
      form_dades.append("client_id", this.comandaData.client.id);
      form_dades.append("data_expedicio", this.comandaData.data_expedicio);
      form_dades.append("import", this.totalImport); //computed
      form_dades.append("nota", this.comandaData.nota);
      form_dades.append("estat", this.comandaData.estat);

      //preparo nova array d'objectes detall (items comanda)
      //només elements que interessen enviar a laravel
      let items = [];
      this.comandaData.comanda_items.forEach(element => {
        const item = {
          producte_id: element['producte']['id'],
          unitats: element['unitats'],
          preu: element['preu'],
          import: element['import'],
        };
        items.push(item);
      });
      
      //per afegir una array d'objectes a formData() i que ho rebi laravel de la mateixa manera
      for (let i = 0; i < items.length; i++) {
        for (let key of Object.keys(items[i])) {
           console.log("hello...", items[i][key]);
           form_dades.append(`items[${i}][${key}]`,items[i][key]);
        }
      }
      console.log(form_dades);
      return form_dades;
    },

    //funció assíncrona
    creaComanda: async function () {
      console.log("form submitted");

      //preparem dades
      let form_dades = this.preparaDades();
      console.log(form_dades);

      //prova response
      try {
        const response = await comandaService.crear(form_dades);
        console.log(response);
        
        //actualitzem productes tornant a demanar a backend
        this.$parent.getComandes();

        //flash message

        this.$flashMessage.show({
          type: "success",
          //title: ":)",
          text: "Comanda stored ok! :)",
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
          //error validació retornem genèric, no resposta a camps
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
            //alert("ep algun error ha passat :(");
            //flash message
            this.$flashMessage.show({
              type: "error",
              text: "alguna cosa ha anat malament :(",
              time: 5000,
            });
            break;
        }
      }
    },
    updateComanda: async function () {
      console.log(this.comanda.id);

       //preparem dades
      let form_dades = this.preparaDades();
      console.log(form_dades);

      form_dades.append("_method", "put"); //indicar mètode put

      //prova response servei update, passem id props i dades
      try {

        const response = await comandaService.update(
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

    //per quan es tanca modal bootstrap netegem
    modalTancat() {
      //quan tanca modal
      console.log("modal comanda tancat");

      //neteja data
      this.comandaData.client = "";
      //this.comandaData.estat = "";
      //this.comandaData.data_comanda = "";
      this.comandaData.data_expedicio = "";
      this.comandaData.import = "";
      this.comandaData.comanda_items = [];
      this.comandaData.nota = "";

      //neteja errors
      this.errors = "";

      //neteja pare no!
      //this.$parent.comandaSelected = "";
      //this.$parent.accio = "";
    },
  },
};
</script>