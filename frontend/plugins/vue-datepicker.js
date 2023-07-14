import Vue from 'vue'
import VueDatePicker from '@mathieustan/vue-datepicker';
import '@mathieustan/vue-datepicker/dist/vue-datepicker.min.css';

Vue.use(VueDatePicker);

// Options: You can set lang by default
Vue.use(VueDatePicker, {
  lang: 'en'
});
