(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[23],{"80ab":function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=t.$createElement,e=t._self._c||a;return t.appLoading?t._e():e("q-layout",{attrs:{view:"lHh Lpr lFf"}},[e("q-page-container",{staticClass:"bg-cyan-9"},[e("q-page",{style:{background:"url(apexion_logo.svg) no-repeat 50%",backgroundSize:"cover"},attrs:{padding:""}},[e("div",{staticClass:"row q-mt-lg"},[e("div",{staticClass:"col-12 col-md-4"}),e("div",{staticClass:"col-12 col-md-4 row"},[e("div",{staticClass:"col-12"},[e("div",{staticClass:"text-center text-h6 text-white"},[t._v("Welcome")]),e("div",{staticClass:"text-h3 text-center text-white q-mb-md"},[t._v("ApexionERP")]),e("q-card",{staticStyle:{width:"100%"}},[e("q-card-section",[e("div",{staticClass:"row"},[e("div",{staticClass:"col-12"},[e("q-input",{attrs:{name:"email",outlined:"",label:"Email",id:"email"},model:{value:t.email,callback:function(a){t.email=a},expression:"email"}})],1)]),e("div",{staticClass:"row q-mt-md"},[e("div",{staticClass:"col-12"},[e("q-input",{attrs:{name:"password",outlined:"",label:"Password",type:"password",id:"password"},model:{value:t.password,callback:function(a){t.password=a},expression:"password"}})],1)])]),e("q-separator"),e("q-card-section",[e("q-btn",{staticStyle:{width:"100%"},attrs:{color:"primary",label:"login"},on:{click:function(a){return t.login()}}})],1)],1)],1)]),e("div",{staticClass:"col-12 col-md-4"})])])],1)],1)},i=[],o=(e("e6cf"),e("a79d"),e("ded3")),n=e.n(o),r=e("2f62"),l={data(){return{email:"",password:""}},mounted(){this.$store.dispatch("init"),this.$store.getters.isAuthenticated&&this.$router.push({name:"Dashboard"})},computed:n()({},Object(r["b"])(["authenticated","appLoading"])),watch:{authenticated(t,a){this.$store.getters.isAuthenticated?this.$router.back():this.$router.push({name:"LoginPage"})}},methods:{login(){this.$q.loading.show(),this.$store.dispatch("login",{email:this.email,password:this.password}).then((()=>{this.$router.push("/")})).catch((t=>{"error-422"===t?this.$q.notify({type:"negative",message:"Incorrect credentials"}):"user-inactive"===t?this.$q.notify({type:"negative",message:"Cannot Login, You have been disabled by admin!!."}):this.$q.notify({type:"negative",message:"Something went wrong."})})).finally((()=>{this.$q.loading.hide()}))}}},c=l,d=e("2877"),p=e("4d5a"),h=e("09e3"),u=e("9989"),m=e("f09f"),g=e("a370"),w=e("27f9"),v=e("eb85"),b=e("9c40"),q=e("eebe"),f=e.n(q),y=Object(d["a"])(c,s,i,!1,null,null,null);a["default"]=y.exports;f()(y,"components",{QLayout:p["a"],QPageContainer:h["a"],QPage:u["a"],QCard:m["a"],QCardSection:g["a"],QInput:w["a"],QSeparator:v["a"],QBtn:b["a"]})}}]);