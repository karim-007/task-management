<template>
<v-menu
        offset-y
        origin="center center"
        :nudge-bottom="10"
        transition="scale-transition"
        >
        <template v-slot:activator="{on}">
          <v-btn text class="pa-0"  v-on="on">
<!--            <div class="deep-orange lighten-5 mr-2 justify-center align-center primary&#45;&#45;text d-flex" style="width: 40px; height: 40px; border-radius: 50%;">
              <v-img v-if="$auth.user.image" :src="$auth.user.image"></v-img>
              A
            </div>-->
            <div class="avater-wraper">
              <v-img v-if="!$auth.user.image"
              style="    border-radius: 50%;"
              lazy-src="/images/logo.png"
              height="40"
              width="40"
              src="/images/logo.png"
            ></v-img>
              <v-img v-else
                style="border-radius: 50%"
                lazy-src="https://picsum.photos/id/11/10/6"
                height="40"
                width="40"
                :src="$auth.user.image"
              ></v-img>
            </div>
            <div class="greeting-text mr-3 d-none d-md-block" style="color: white !important;"> Hi, <span>{{ $auth.user.name }}</span></div>
          </v-btn>
        </template>
        <!-- user menu list -->
        <v-list nav>
          <v-list-item
        v-for="(item, index) in menu"
        :key="index"
        :to="localePath(item.link)"
        :exact="item.exact"
        :disabled="item.disabled"
        link
      >
        <v-list-item-icon>
          <v-icon small :class="{ 'grey--text': item.disabled }">{{ item.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>{{ item.key ? $t(item.key) : item.text }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
          <v-divider class="my-1"></v-divider>
          <v-list-item @click="logout()">
            <v-list-item-icon>
              <v-icon small>mdi-logout-variant</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{ $t('menu.logout') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>

</template>

<script>
import config from '../../configs'
export default {
  name: 'HeaderProfile',
  data() {
    return {
      avater:null,
      menu: config.toolbar.user
    }
  },
  methods:{
    async logout() {
      await this.$auth.logout()
    },
  }
}
</script>
