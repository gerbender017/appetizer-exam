<template>
  <v-form
    ref="form"
    @submit="submit"
  >

    <br/><br/>
    <v-row style="text-align: center;">
      <v-col columns="2">

        <h1>Event Calendar</h1>
      </v-col>
    </v-row>
    <hr/>
    <v-row>
      <v-col columns="2">
        <v-text-field
          v-model="form_data.event_name"
          class="mt-3"
          label="Name"
          placeholder="Event Name"
          dense
          outlined
          hide-details
          v-on="on"
        ></v-text-field>
      </v-col>
    </v-row>

    <v-row>
      <v-col columns="2">
        <v-menu
          v-model="form_data.date_from_menu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="form_data.date_from"
              label="From"
              prepend-icon="event"
              outlined
              dense
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="form_data.date_from" @input="form_data.date_from_menu = false"></v-date-picker>
        </v-menu>
      </v-col>

      <v-col>
        <v-menu
          v-model="form_data.date_to_menu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="form_data.date_to"
              label="To"
              prepend-icon="event"
              outlined
              dense
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="form_data.date_to" @input="form_data.date_to_menu = false"></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>
    <v-row>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Monday" value=1></v-checkbox>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Tuesday" value=2></v-checkbox>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Wednesday" value=3></v-checkbox>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Thursday" value=4></v-checkbox>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Friday" value=5></v-checkbox>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Saturday" value=6></v-checkbox>
      <v-checkbox v-model="form_data.days" class="mx-2" label="Sunday" value=0></v-checkbox>
    </v-row>

    <hr/>
    <div class="float-right">
      <v-row>
        <v-col cols="1">
          <v-btn
            color="primary"
            class="md-5"
            type="submit"
          >
            Create
          </v-btn>
        </v-col>
      </v-row>
    </div>
  </v-form>
</template>

<script>
    // import axios from 'axios';
    import Vue from "vue";
    import {mapState} from 'vuex';

    export default {
        data: () => ({
            on: null,
            date: null,
            validated: false,
            form_data: {
                date_from: new Date().toISOString().substr(0, 10),
                date_to: new Date().toISOString().substr(0, 10),
                date_from_menu: false,
                date_to_menu: false,
                days: [],
                event_name: null,
            }
        }),

        methods: {
            submit(e) {

                e.preventDefault();
                this.validate();

                if (this.validated) {

                    this.$http.post(process.env.VUE_APP_API_URL + '/events/create', this.form_data).then(response => {

                        if (response.data.success === true) {

                            this.form_data.days = this.form_data.days.length === 0 ? [0, 1, 2, 3, 4, 5, 6] : this.form_data.days;
                            this.$store.dispatch('setCalendarValues', this.form_data);
                            this.form_data.days = [];

                            Vue.$toast.success(response.data.message, {});
                        } else {
                            Vue.$toast.error(response.data.message, {});
                        }
                        // // get status
                        // response.status;
                        //
                        // // get status text
                        // response.statusText;
                        //
                        // // get 'Expires' header
                        // response.headers.get('Expires');
                        //
                        // // get body data
                        // this.someData = response.body;

                    }).catch((error) => {
                        alert(error);
                    });
                    // axios.post(process.env.VUE_APP_API_URL + '/events/create', this.form_data).then((response) => {
                    //     if (response.data.success === true) {
                    //
                    //         this.form_data.days = this.form_data.days.length === 0 ? [0, 1, 2, 3, 4, 5, 6] : this.form_data.days;
                    //         this.$store.dispatch('setCalendarValues', this.form_data);
                    //         this.form_data.days = [];
                    //
                    //         Vue.$toast.success(response.data.message, {
                    //         });
                    //     } else {
                    //         Vue.$toast.error(response.data.message, {
                    //         });
                    //     }
                    // }).catch(function (error) {
                    //     console.log(error);
                    //     Vue.$toast.error("Unable to Connect to API", {
                    //     });
                    // });
                }
            },

            validate() {

                if (!this.form_data.event_name) {
                    Vue.$toast.error("Input Event Name", {
                        timeout: 2000,
                    });
                    this.validated = false;
                    return false;
                }
                if (this.form_data.date_from > this.form_data.date_to) {
                    Vue.$toast.error("Date from must be earlier than Date To", {
                        timeout: 2000,
                    });
                    this.validated = false;
                    return false;

                } else {
                    this.validated = true;

                }
            }
        },
        computed: {
            ...mapState({
                calendar: state => state.calendar
            })
        }
    }
</script>
