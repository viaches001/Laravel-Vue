require('./bootstrap');

import Vue from 'vue';

import { Inertia } from '@inertiajs/inertia'
import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueLuxon from "vue-luxon";
import VueGoodTablePlugin from 'vue-good-table';
import { extend } from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';
import store from './Store';

Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueLuxon, {
  input: {
      zone: "America/Toronto",
      format: "iso"
  },
  output: {
      zone: "local",
      format: "date_medd",
  }
});
Vue.use(VueGoodTablePlugin);

// Icons used throughout the project
import WifiAlertIcon from 'vue-material-design-icons/WifiAlert.vue';
Vue.component('wifi-alert-icon', WifiAlertIcon);
import HelpCircleOutlineIcon from 'vue-material-design-icons/HelpCircleOutline.vue';
Vue.component('help-circle-outline-icon', HelpCircleOutlineIcon);
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue';
Vue.component('alert-circle-outline-icon', AlertCircleOutlineIcon);
import MenuIcon from 'vue-material-design-icons/Menu.vue';
Vue.component('menu-icon', MenuIcon);
import CloseIcon from 'vue-material-design-icons/Close.vue';
Vue.component('close-icon', CloseIcon);
import FilterIcon from 'vue-material-design-icons/Filter.vue';
Vue.component('filter-icon', FilterIcon);
import GoogleMapsIcon from 'vue-material-design-icons/GoogleMaps.vue';
Vue.component('google-maps-icon', GoogleMapsIcon);
import CheckIcon from 'vue-material-design-icons/Check.vue';
Vue.component('check-icon', CheckIcon);
import PrinterIcon from 'vue-material-design-icons/Printer.vue';
Vue.component('printer-icon', PrinterIcon);
import AlertDecagramIcon from 'vue-material-design-icons/AlertDecagram.vue';
Vue.component('alert-decagram-icon', AlertDecagramIcon);
import DecagramIcon from 'vue-material-design-icons/Decagram.vue';
Vue.component('decagram-icon', DecagramIcon);
import ArrowRightIcon from 'vue-material-design-icons/ArrowRight.vue';
Vue.component('arrow-right-icon', ArrowRightIcon);
import CheckboxBlankCircleOutlineIcon from 'vue-material-design-icons/CheckboxBlankCircleOutline.vue';
Vue.component('checkbox-blank-circle-outline-icon', CheckboxBlankCircleOutlineIcon);
import CheckboxMarkedCircleOutlineIcon from 'vue-material-design-icons/CheckboxMarkedCircleOutline.vue';
Vue.component('checkbox-marked-circle-outline-icon', CheckboxMarkedCircleOutlineIcon);
import UploadIcon from 'vue-material-design-icons/Upload.vue';
Vue.component('upload-icon', UploadIcon);
import DownloadIcon from 'vue-material-design-icons/Download.vue';
Vue.component('download-icon', DownloadIcon);
import CameraIcon from 'vue-material-design-icons/Camera.vue';
Vue.component('camera-icon', CameraIcon);
import ClockOutlineIcon from 'vue-material-design-icons/ClockOutline.vue';
Vue.component('clock-outline-icon', ClockOutlineIcon);
import ContentSaveOutlineIcon from 'vue-material-design-icons/ContentSaveOutline.vue';
Vue.component('content-save-outline-icon', ContentSaveOutlineIcon);
import ChevronDownIcon from 'vue-material-design-icons/ChevronDown.vue';
Vue.component('chevron-down-icon', ChevronDownIcon);
import ChevronUpIcon from 'vue-material-design-icons/ChevronUp.vue';
Vue.component('chevron-up-icon', ChevronUpIcon);
import TrashCanOutlineIcon from 'vue-material-design-icons/TrashCanOutline.vue';
Vue.component('trash-can-outline-icon', TrashCanOutlineIcon);
import PlusIcon from 'vue-material-design-icons/Plus.vue';
Vue.component('plus-icon', PlusIcon);
import PencilIcon from 'vue-material-design-icons/Pencil.vue';
Vue.component('pencil-icon', PencilIcon);


// Vee Validate extends
extend('required', {
  ...required,
  message: 'This field is required.',
});

extend('email', {
  ...email,
  message: 'This field must be a valid email.'
});

const app = document.getElementById('app');

new Vue({
    store,
    mounted () {
      console.log('mounted');
      Inertia.on('before', (event) => {
          console.log('before');
          return confirm('Are you sure you want to leave this page? Any unsaved changes will be lost.')
      });
    },
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
