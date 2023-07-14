<template>
  <v-menu offset-y left transition="slide-y-transition">
    <template v-slot:activator="{ on }">
      <v-badge
        bordered
        content="1"
        offset-x="22"
        offset-y="22"
      >
        <v-btn icon v-on="on">
          <v-icon>mdi-bell-outline</v-icon>
        </v-btn>
      </v-badge>
    </template>

    <!-- dropdown card -->
    <v-card>
      <v-list three-line dense max-width="400">
        <v-subheader class="pa-2 font-weight-bold">Notifications</v-subheader>
        <div v-for="(item, index) in notifications" :key="index">
          <v-divider v-if="index > 0 && index < notifications.length" inset></v-divider>

          <v-list-item @click="$router.push(item.link)">
            <v-list-item-avatar size="32" :color="'primary'">
              <v-icon dark small>{{ 'mdi-account-circle' }}</v-icon>
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title v-text="item.subject"></v-list-item-title>
              <v-list-item-subtitle class="caption" v-text="item.message"></v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action class="align-self-center">
              <v-list-item-action-text v-text="moment(item.created_at).startOf('hour').fromNow()"></v-list-item-action-text>
            </v-list-item-action>
          </v-list-item>
        </div>
      </v-list>

      <div class="text-center py-2">
        <v-btn small to="/notification">See all</v-btn>
      </div>
    </v-card>
  </v-menu>
</template>

<script>
/*
|---------------------------------------------------------------------
| Toolbar Notifications Component
|---------------------------------------------------------------------
|
| Quickmenu to check out notifications
|
*/
import {mapGetters} from "vuex";
import moment from "moment-timezone";
const stateName = 'notifications'
const storeName='user/notification'
export default {
  data() {
    return {
      pageInfo: {
        pageName: 'Notification',
        apiUrl: '/admin/notifications/',
      },
      state: {
        name: stateName,
        store_name: storeName,
      },
      moment,
      loader: {isLoading: true, isSubmitting: false, isDeleting: false},
      timeout: null,
      page:1,
      per_page:10,
      notifications: [
        {
          title: 'Brunch this weekend?',
          color: 'primary',
          icon: 'mdi-account-circle',
          subtitle: 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint, repudiandae?',
          time: '3 min'
        },
      ]
    }
  },
  async mounted() {

  },
  computed:{

  },
  methods: {

  }
}
</script>
