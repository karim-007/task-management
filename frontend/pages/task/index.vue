<template>
  <v-container fluid>
    <bread-crumbs
      :page-title="`${pageInfo.pageName}`"
      :items="[{text: `${pageInfo.pageName}`,disabled: true, href: '/task'}]"
    />
    <v-card>
      <v-data-table
        dense
        v-model="selected"
        item-key="id"
        show-select
        :headers="headers"
        :items="tasks"
        :options.sync="options"
        :server-items-length="totalItems"
        :search="search"
        :footer-props="footerProps"
        :items-per-page="20"
        class="elevation-1"
        :loading="loader.isLoading"
      >
        <template v-slot:top>
          <v-toolbar
            flat
          >
<!--            <v-toolbar-title>
              <div>
                <export-excel :url="`${pageInfo.apiUrl}export?format=excel&ids=${selectedIds ? selectedIds : ''}`"
                              :file_name="pageInfo.pageName"/>
                <export-csv :url="`${pageInfo.apiUrl}export?format=csv&ids=${selectedIds ? selectedIds : ''}`"
                            :file_name="pageInfo.pageName"/>
                <export-pdf :url="`${pageInfo.apiUrl}export?format=pdf&ids=${selectedIds ? selectedIds : ''}`"
                            :file_name="pageInfo.pageName"/>
              </div>
            </v-toolbar-title>-->
<!--            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>-->
            <div class="mr-2">
              <v-text-field
                v-model="search"
                label="Search"
                class="mt-3"
              ></v-text-field>
            </div>
            <v-spacer></v-spacer>
            <v-dialog fullscreen
                      hide-overlay
                      transition="dialog-bottom-transition" v-model="dialog" persistent>
              <template v-slot:activator="{ on, attrs }">
                <!--                color="primary"-->
                <v-btn v-if="$can('read',pageInfo.permission)"
                       style="background: #a31010;"
                       class="mx-2 white--text"
                       small
                       v-bind="attrs"
                       v-on="on"
                       @click="createOrUpdate()"
                >
                 Add New <v-icon>mdi-plus</v-icon>
                </v-btn>
              </template>
              <v-card>
                <v-toolbar
                  dark
                  color="primary"
                >
                  <v-btn
                    icon
                    dark
                    @click="dialog = false"
                  >
                    <v-icon>mdi-close</v-icon>
                  </v-btn>
                  <v-toolbar-title>{{ editIndex > -1 ? 'Update ' : 'Add' }} {{ pageInfo.pageName | capitalize }}</v-toolbar-title>
                  <v-spacer></v-spacer>
                </v-toolbar>
                <validation-observer ref="observer" v-slot="{ invalid }">
                  <v-form ref="form" @submit.prevent="submitData()">
                    <v-card-text>
                      <v-container>
                        <v-row dense>
                          <v-col cols="12" md="6">
                            <validation-provider
                              v-slot="{ errors }"
                              name="Title"
                              vid="title"
                              rules="required|max:191"
                            >
                              <v-text-field
                                v-model="form.title"
                                :error-messages="errors"
                                label="title"
                                required
                                dense
                                hide-details="auto"
                                outlined
                              ></v-text-field>
                            </validation-provider>
                          </v-col>
                          <v-col cols="12" md="6" sm="4">
                            <validation-provider
                              v-slot="{ errors }"
                              name="deadline"
                              vid="deadline"
                              rules="max:191"
                            >
                              <v-text-field
                                v-model="form.deadline"
                                :error-messages="errors"
                                label="Deadline"
                                type="datetime-local"
                                dense
                                hide-details="auto"
                                outlined
                              ></v-text-field>
                            </validation-provider>
                          </v-col>
                          <v-col cols="12" md="6">
                            <validation-provider
                              v-slot="{ errors }"
                              name="assign to"
                              vid="assign_to"
                              rules=""
                            >
                              <v-select
                                v-model="form.assign_to"
                                :items="users"
                                :error-messages="errors"
                                item-text="name"
                                item-value="id"
                                label="Select User"
                                dense
                                clearable
                                hide-details="auto"
                                outlined
                              ></v-select>
                            </validation-provider>
                          </v-col>
                          <v-col cols="12">
                            <validation-provider
                              v-slot="{ errors }"
                              name="description"
                              vid="description"
                              rules="required|max:191"
                            >
                              <v-textarea
                                v-model="form.description"
                                :error-messages="errors"
                                label="Description"
                                dense
                                hide-details="auto"
                                outlined
                              ></v-textarea>
                            </validation-provider>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                        color="green darken-1"
                        class="mx-2 white--text"
                        type="submit"
                        :disabled="invalid"
                        :loading="loader.isSubmitting"
                        depressed
                      >
                        {{ editIndex > -1 ? 'Update' : 'Submit' }}
                      </v-btn>
                      <v-btn class="mr-2 error darken-1 white--text" depressed @click="closeModal">Close</v-btn>
                    </v-card-actions>
                  </v-form>
                </validation-observer>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.sl="{item,index }">
          {{ index+1 }}
        </template>
        <template v-slot:item.created_at="{item }">
          {{ moment(item.created_at).format('lll') }}
        </template>
        <template v-slot:item.status="{item }">
          <span v-if="item.status == 0">Pending</span>
          <span v-else-if="item.status == 1">Processing</span>
          <span v-else-if="item.status == 3">Completed</span>
        </template>
        <template v-slot:item.actions="{index, item }">
          <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark v-bind="attrs" v-on="on">
                Action
                <v-icon> mdi-menu-down </v-icon>
              </v-btn>
            </template>
            <v-list>
              <template v-if="$auth.user.id == item.created_by">
                <v-list-item-group>
                  <v-list-item
                      class="action-dropdown"
                      @click="createOrUpdate(item)"
                      v-if="$can('read',pageInfo.permission)"
                  >
                    <v-list-item-icon class="action-dropdown-icon">
                      <v-icon> mdi-pencil </v-icon>
                    </v-list-item-icon>
                    <v-list-item-content class="action-dropdown-text">
                      <v-list-item-title>Edit</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
                <v-list-item-group>
                  <v-list-item
                      class="action-dropdown"
                      @click="deleteItem(item, index, state.store_name)"
                      v-if="$can('read',pageInfo.permission)"
                  >
                    <v-list-item-icon class="action-dropdown-icon">
                      <v-icon> mdi-delete </v-icon>
                    </v-list-item-icon>
                    <v-list-item-content class="action-dropdown-text">
                      <v-list-item-title>Delete</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </template>
              <v-list-item-group v-if="item.status != 1">
                <v-list-item
                    class="action-dropdown"
                    @click="statusChangeF(item.id,1,index)"
                >
                  <v-list-item-icon class="action-dropdown-icon">
                    <v-icon> mdi-timer-sand-complete </v-icon>
                  </v-list-item-icon>
                  <v-list-item-content class="action-dropdown-text">
                    <v-list-item-title>Processing</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
              <v-list-item-group v-if="item.status != 2">
                <v-list-item
                    class="action-dropdown"
                    @click="statusChangeF(item.id,2,index)"
                >
                  <v-list-item-icon class="action-dropdown-icon">
                    <v-icon> mdi-store-check-outline </v-icon>
                  </v-list-item-icon>
                  <v-list-item-content class="action-dropdown-text">
                    <v-list-item-title>Completed</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-menu>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import ExportExcel from '@/components/common/ExportExcel'
import ExportCsv from '@/components/common/ExportCsv'
import ExportPdf from '@/components/common/ExportPdf'
import BreadCrumbs from "~/components/common/BreadCrumbs";
import {common as commonMixin} from "@/mixins/common";
import FormImagePreview from '@/components/form/formImagePreview';
import {mapGetters} from "vuex";
import moment from "moment-timezone";
const permission = 'Auth'
const stateName = 'tasks'
const storeName='task'

export default {
  name: 'Task',
  head: {
    title: 'task',
    meta: [
      {
        hid: 'description',
        name: 'description',
        content: 'description'
      }
    ],
  },
  meta:{
    action: 'read',
    subject: permission
  },
  components: {BreadCrumbs, ExportPdf, ExportCsv, ExportExcel, FormImagePreview},
  mixins: [commonMixin],
  data() {
    return {
      pageInfo: {
        pageName: 'Task Management',
        apiUrl: '/task/',
        permission
      },
      state: {
        name: stateName,
        store_name: storeName,
      },
      moment,
      selected: [],
      options: {},
      dialog: false,
      editIndex: -1,
      selectedItem: {},
      editMode:false,
      loader: {isLoading: false, isSubmitting: false, isDeleting: false},
      form: {
        title: null,
        description: null,
        deadline: null,
        assign_to: null,
        created_at: null,
      },
      search: '',
      timeout: null,
      headers: [
        {
          text: 'SL',
          align: 'start',
          value: 'sl'
        },
        {
          text: 'Title',
          align: 'start',
          value: 'title'
        },
        {
          text: 'Deadline',
          align: 'start',
          value: 'deadline'
        },
        {
          text: 'Assign to',
          align: 'start',
          value: 'assign_to'
        },
        {
          text: 'Created At',
          align: 'start',
          value: 'created_at'
        },
        {
          text: 'Status',
          value: 'status'
        },
        {text: 'Actions', value: 'actions', sortable: false}
      ],
      footerProps: {
        itemsPerPageOptions: [20, 50, 100, 500]
      }
    }
  },
  computed: {
    ...mapGetters('task',['tasks','totalItems']),
    ...mapGetters('user/user',['users','totalItems']),
    selectedIds() {
      return this.selected.map((a) => a.id)
    },
  },
  watch: {
    options: {
      handler() {
        this.init()
      },
      deep: true
    },
    search(value, oldVal) {
      if ((value.length >= 2) || oldVal.length >= 2) {
        if (this.timeout) {
          clearTimeout(this.timeout)
        }
        this.timeout = setTimeout(() => {
          this.init()
        }, 500)
      }
    },

  },
  async mounted() {
    this.loader.isLoading =true
    await this.init()
    const payload0 = { apiUrl: "/users", stateName: "users" };
    if(!this.users.length) await this.$store.dispatch("user/user/getItems", payload0);

    this.loader.isLoading =false
  },
  methods: {
    async init() {
      this.loader.isLoading = true
      let url = `${this.pageInfo.apiUrl}?per_page=${this.options.itemsPerPage}&page=${this.options.page}`
      if (this.search.length >= 3) {
        url += `&search=${this.search}`
      }
      const payload = {apiUrl: url, stateName}
      await this.$store.dispatch(storeName+'/getItems', payload)
      this.loader.isLoading = false
    },

    createOrUpdate(item = null) {
      if (item) {
        this.editIndex = this.tasks.indexOf(item)
        this.editMode=true;
      }
      else {
        this.editIndex = -1
        this.editMode=false;
      }
      if (this.editMode) {
        this.selectedItem = item
        Object.keys(this.form).map((field) => {
          this.form[field]=this.selectedItem[field] ?? null;
        });
      } else {
        this.resetForm()
      }
      this.dialog = true
    },
    async submitData() {
      this.loader.isSubmitting = true
      const formData = this.$generateFormData(this.form, this.editIndex > -1)
      let url = this.pageInfo.apiUrl

      if (this.editMode) url = url + this.selectedItem.id

      await this.$axios.post(url, formData).then((response) => {
        this.addOrUpdateState(response, stateName, storeName)
        this.$toaster.success(`${this.pageInfo.pageName} ${this.editMode ? 'updated' : 'created'} successfully!!`)
        if (this.editMode) this.closeModal()
        else this.closeModal2()

      }).catch((error) => {
        if (error.response.status === 422) {
          this.$refs.observer.setErrors(error?.response?.data?.errors)
          Object.keys(error.response.data.errors).map((field) => {
            this.$toaster.error(error.response.data.errors[field][0]);
          });
        } else this.$toaster.error(error.response.data.message);
      }).finally(() => {
        this.loader.isSubmitting = false
      })
    },

    async statusChangeF(id,status,index){
      this.$axios.put(`/task/status/change/${id}/${status}`).then((response)=>{
          this.$toaster.success(response.data.message);
      }).catch((error)=>{
        if (error.response.status === 422) {
          this.$refs.observer.setErrors(error?.response?.data?.errors)
          Object.keys(error.response.data.errors).map((field) => {
            this.$toaster.error(error.response.data.errors[field][0]);
          });
        } else this.$toaster.error(error.response.data.message);
      })
    },
    closeModal() {
      this.dialog = false
      this.clear()
    },
    closeModal2(){
      this.clear()
    },
    clear() {
      this.editIndex = -1
      //this.$refs.form.reset()
      this.resetForm()
      this.$refs.observer.reset()
      this.form.is_doner = true
    },
    resetForm(){
      this.selectedItem = {}
      this.form = {
        title: null,
        description: null,
        deadline: null,
        assign_to: null,
        created_at: null,
      }
    }
  }
}
</script>
