
<template>
  <v-app>
    <div
      v-shortkey="['ctrl', '/']"
      class="d-flex flex-grow-1"
      @shortkey="onKeyup"
    >
      <v-navigation-drawer
        v-model="drawer"
        :right="$vuetify.rtl"
        app
        floating
        class="elevation-1 Sidebar-area"
        :light="menuTheme === 'light'"
        :dark="menuTheme === 'dark'"
      >
        <!-- Navigation menu info -->
        <template v-slot:prepend>
          <div class="pa-2">
            <div class="title font-weight-bold text-uppercase text-white">
              <v-img
                     class="mx-2"
                     :src="'/logo.png'"
                     max-height="100"
                     max-width="150"
                     contain
              ></v-img>
            </div>
            <!--            <div class="overline grey--text">{{ hotel.sub_title }}</div>-->
          </div>
        </template>

        <!-- Navigation menu -->
        <main-menu :menu="navigation.menu" />
        <!-- Navigation menu footer -->
        <template v-slot:append>
          <!-- Footer navigation links -->
          <div class="pa-1 text-center">
            <v-btn
              v-for="(item, index) in navigation.footer"
              :key="index"
              :href="item.href"
              :target="item.target"
              small
              text
            >
              {{ item.key ? $t(item.key) : item.text }}
            </v-btn>
          </div>
        </template>
      </v-navigation-drawer>
      <!-- Toolbar -->
      <v-app-bar
        app
        :color="isToolbarDetached ? 'surface' : undefined"
        :flat="isToolbarDetached"
        :light="toolbarTheme === 'light'"
        :dark="toolbarTheme === 'dark'"
        class="primary"
      >
        <div class="d-flex flex-grow-1 align-center ">
          <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

          <v-spacer class="d-none d-lg-block"></v-spacer>
          <div :class="[$vuetify.rtl ? 'ml-1' : 'mr-1']">
            <toolbar-notifications/>
          </div>
          <Header_profile></Header_profile>
<!--          <toolbar-user/>-->
        </div>
      </v-app-bar>
      <v-main>
        <v-container class="fill-height" :fluid="!isContentBoxed">
          <v-layout>
              <nuxt class="mb-2"/>
          </v-layout>
        </v-container>
      </v-main>
    </div>
  </v-app>
</template>

<script>
import MainMenu from "~/components/navigation/MainMenu";
import Notification from "../components/header/notification.vue";
import ToolbarUser from '../components/toolbar/ToolbarUser'
import ToolbarApps from '../components/toolbar/ToolbarApps'
import ToolbarLanguage from '../components/toolbar/ToolbarLanguage'
import ToolbarCurrency from '../components/toolbar/ToolbarCurrency'
import ToolbarNotifications from '../components/toolbar/ToolbarNotifications'
import Header_profile from "../components/header/header_profile.vue";
import config from "../configs";
import { mapGetters, mapMutations, mapState } from "vuex";
export default {
  middleware: ["auth",],
  components: { Notification, Header_profile,MainMenu,
    ToolbarUser,ToolbarApps, ToolbarNotifications},
  name: "DefaultLayout",
  data() {
    return {
      theme: 0,
      apilink: "",
      select_chat_group: 2,
      chat_groups: [
        { name: "Live Chat With Badhan Team", id: 1 },
        { name: "Live Chat With Central", id: 2 },
      ],

      fileDialog: false,

      image_types: ["png", "jpg", "jpeg", "webp", "svg", "gif"],
      video_types: ["mp4", "video/ogg", "webm", "quicktime"],
      audio_types: ["mp3", "audio/ogg", "wav", "mpeg"],
      drawer: null,
      showSearch: false,
      moved: true,
      navigation: config.navigation,

      count: 0,
      clipped: false,
      fixed: false,
      miniVariant: false,
      right: true,
      searchclose: true,
      rightDrawer: false,
      title: "Task",
    };
  },
  async fetch() {

  },
  fetchOnServer: false,
  computed: {
    ...mapState('app', ['product', 'isContentBoxed', 'menuTheme', 'toolbarTheme', 'isToolbarDetached']),
  },
  watch: {
    theme(val) {
      this.setGlobalTheme((val === 0 ? 'light' : 'dark'))
    },
  },
  async mounted() {

  },
  created() {
    if (process.client) this.checkActiveTheme()
  },
  methods: {
    sidebarHandler() {
      var sideBar = document.getElementById("mobile-nav");
      sideBar.style.transform = "translateX(-260px)";
      if (this.$data.moved) {
        sideBar.style.transform = "translateX(0px)";
        this.$data.moved = false;
      } else {
        sideBar.style.transform = "translateX(-260px)";
        this.$data.moved = true;
      }
    },
    ...mapMutations('app', ['setGlobalTheme']),
    setTheme() {
      this.$vuetify.theme.dark = this.theme === 'dark'
    },
    checkActiveTheme() {
      if(process.client){
        this.theme = null
        const currentTheme = window.localStorage.getItem('theme')

        currentTheme === 'dark' ? this.theme = 1 : this.theme = 0
      }
    },
    onKeyup(e) {
      this.$refs.search.focus()
    },
  },
};
</script>


