<template>
  <v-container>
    <v-row>
      <v-col md="4">
        <Form/>
      </v-col>
      <v-col md="8">
        <Calendar/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
    import Calendar from "./calendar/Calendar";
    import Form from "./calendar/Form/Form";

    export default {
        name: "App",

        components: {
            Calendar,
            Form
        },

        data: () => ({})
    };


</script>
