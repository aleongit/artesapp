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
              <li class="breadcrumb-item">User</li>
              <li class="breadcrumb-item active" aria-current="page">
                Change Password
              </li>
            </ol>
          </nav>

          <div class="row justify-content-center">
            <div class="col-lg-5">
              <!--card table-->
              <div class="card my-2">
                <div class="card-header d-flex">
                  <span><i class="fas fa-lock"></i> Change Password</span>
                  </div>

                <div class="card-body">
                  <h5 class="card-title">Change Password!</h5>

                  <form v-on:submit.prevent="passUpdate">
                    
                    <!--old password-->
                    <div class="form-floating mb-3">
                      <input
                        class="form-control"
                        id="old_password"
                        type="password"
                        placeholder="Password actual"
                        v-model="userData.old_password"
                      />
                      <label for="old_password">Password</label>
                      <!--retorn error -->
                      <div class="invalid-feedback" v-if="errors.old_password">
                        <strong>{{ errors.old_password[0] }}</strong>
                      </div>
                    </div>

                    <!--new password-->
                    <div class="form-floating mb-3">
                      <input
                        class="form-control"
                        id="new_password"
                        type="password"
                        placeholder="Password actual"
                        v-model="userData.new_password"
                      />
                      <label for="new_password">Nou Password</label>
                      <!--retorn error -->
                      <div class="invalid-feedback" v-if="errors.new_password">
                        <strong>{{ errors.new_password[0] }}</strong>
                      </div>
                    </div>

                    <!--new password confirmation-->
                    <div class="form-floating mb-3">
                      <input
                        class="form-control"
                        id="new_password_confirmation"
                        type="password"
                        placeholder="Password actual"
                        v-model="userData.new_password_confirmation"
                      />
                      <label for="new_password_confirmation">Confirma Nou Password</label>
                      <!--retorn error -->
                      <div class="invalid-feedback" v-if="errors.new_password_confirmation">
                        <strong>{{ errors.new_password_confirmation[0] }}</strong>
                      </div>
                    </div>

                    <div
                      class="
                        d-flex
                        align-items-center
                        justify-content-between
                        mt-4
                        mb-0
                      "
                    >
                      <button type="submit" class="btn btn-primary">
                        Update!
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as auth from "../../services/auth_service";
export default {
  name: "Change-Password",
  components: {},
  data() {
    return {
      userData: {
        old_password: "",
        new_password: "",
        new_password_confirmation: "",
      },
      errors: {},
    };
  },

  //al muntar component
  mounted() {},

  methods: {
    passUpdate: async function () {
      console.log("update password!");

      try {
        //await service auth
        const response = await auth.updatePassword(this.userData);
        console.log(response);

        //flash message
        this.$flashMessage.show({
          type: "success",
          text: "Password update ok! :)",
          time: 5000,
        });

        //netegem
        this.errors = {};
        this.userData = {};

      } catch (error) {
        console.log(error);
        //depenent codi error
        switch (error.response.status) {
          case 422: //validació
            this.errors = error.response.data.errors;
            break;
          case 401: //no autoritzat
            this.$flashMessage.show({
              type: "error",
              text: error.response.data.message,
              time: 5000,
            });
            break;
          case 500:
            this.$flashMessage.show({
              type: "error",
              text: error.response.data.message,
              time: 5000,
            });
            break;
          default:
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
  },
};
</script>