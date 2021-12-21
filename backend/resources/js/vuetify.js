import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

import colors from 'vuetify/lib/util/colors'
import { reduce } from 'lodash'

Vue.use(Vuetify)

const opts = {
    icons: {
        iconfont: 'mdiSvg',
        
    }
}


export default new Vuetify({
    opts,
    theme: {
      themes: {
        light: {
          primary: '#009688',
          secondary: '#E0F2F1',
          accent: '#B2DFDB',
          error:'#80CBC4',
          warning: '#4DB6AC',
          info: colors.grey,
          success: "#795548",
          mycolor: "#e0e0e0",
        },
        dark: {
          primary: "#ff0000",
          secondary: colors.green,
        },
      },
    },
})
