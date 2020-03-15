<template>
  <v-app>
    <Header/>
    <v-content>
      <Exam/>
    </v-content>
    <Footer/>
  </v-app>
</template>

<script>
    import Exam from "./components/Exam";
    import Header from './components/layout/Header'
    import Footer from './components/layout/Footer'

    export default {
        name: "App",

        components: {
            Exam,
            Header,
            Footer
        },

        data: () => ({
            //
        })
    };
</script>
