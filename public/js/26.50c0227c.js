(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[26],{1001:function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",[s("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{sendforapproval:t.sendForApproval,approve:t.approve,activate:t.activate,revert:t.revert,disable:t.disable}}),s("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),s("q-card",[s("q-card-section",[s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-xs-12 col-md-6"},[s("q-markup-table",{attrs:{separator:"cell","wrap-cells":""}},[s("tbody",[s("tr",[s("td",[t._v("Name")]),s("td",[t._v(t._s(t.name))])]),s("tr",[s("td",[t._v("Type")]),s("td",[t._v(t._s(t.type))])]),s("tr",[s("td",[t._v("Status")]),s("td",[t._v(t._s(t.status))])]),s("tr",[s("td",[t._v("GST No.")]),s("td",[t._v(t._s(t.gst_number))])]),s("tr",[s("td",[t._v("Initial Balance")]),s("td",[t._v(t._s(t.initial_balance))])]),s("tr",[1==t.representatives.length?s("td",[t._v("\n                                  representatives\n                              ")]):t._e(),s("td",[t._v("\n                                  "+t._s(t.getRepresentatives())+"\n                              ")])]),t._l(t.emails,(function(e,a){return s("tr",{key:"email-"+a},[s("td",[0==a?s("div",[t._v("Emails")]):t._e()]),s("td",[t._v(t._s(e.content))])])})),s("tr",[s("td",[t._v("Non Billable Account ? ")]),s("td",[t._v(t._s(t.non_billable_account?"Yes":"No"))])]),t.addresses.length>0?s("tr",[s("td",[t._v("Billing Address")]),s("td",[t._v(t._s(t.getBillingAddress()))])]):t._e(),t.addresses.length>0?t._l(t.addresses,(function(e,a){return s("tr",{key:"tag-"+a},["billing"!=e.tag_name?[s("td",[s("div",[t._v("Shipping Address"),t.default_shipping==e.id?s("span",[t._v(" (Default)")]):t._e()])]),s("td",[t._v(t._s(t.addressFormat(e)))])]:t._e()],2)})):t._e(),s("tr",[s("td",[t._v("Tags")]),s("td",[t._v(t._s(t.getTags()))])])],2)])],1),s("div",{staticClass:"col-xs-12 col-md-6"},[t.linked.length>0?s("div",{staticClass:"row"},[s("div",{staticClass:"text-h5 q-mb-md col-12"},[t._v("Relations")]),t.linked.length>0?s("q-markup-table",{staticStyle:{width:"100%"},attrs:{separator:"cell","wrap-cells":""}},[s("thead",[s("th",{staticClass:"text-left"},[t._v("Customer Name")]),s("th",{staticClass:"text-left"},[t._v("Relation Type")]),s("th",{staticClass:"text-left"},[t._v("Specification")])]),s("tbody",t._l(t.linked,(function(e,a){return s("tr",{key:"linked"+a},[s("td",[s("router-link",{attrs:{to:"/customers/view/"+e.id}},[t._v(t._s(e.name))])],1),s("td",[t._v(t._s(e.pivot.relation_type))]),s("td",[t._v(t._s(e.pivot.specification))])])})),0)]):t._e()],1):t._e(),s("q-separator",{staticClass:"q-my-sm"}),s("div",{staticClass:"row"},[s("div",{staticClass:"col-12"},[t.$store.getters.hasPermissionTo("view_sale_order")?s("q-btn",{staticClass:"q-ml-md q-mt-md",attrs:{label:"Sales",color:"blue-10",to:"/customers/sales/"+t.$route.params.id}}):t._e(),t.$store.getters.hasPermissionTo("view_sale_order")?s("q-btn",{staticClass:"q-ml-md q-mt-md",attrs:{label:"Ledger",color:"blue-7",to:"/customers/ledger/"+t.$route.params.id}}):t._e()],1)]),s("q-separator",{staticClass:"q-my-sm"})],1)])])],1),s("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":"",buttons:t.toolbarButtons},on:{sendforapproval:t.sendForApproval,approve:t.approve,activate:t.activate,revert:t.revert,disable:t.disable}})],1)},r=[],i=(s("e6cf"),s("a79d"),s("a89c")),o=s("b6c6"),l={name:"CustomersView",components:{PageToolbar:i["a"],Breadcrumbs:o["a"]},data(){return{name:null,status:null,type:null,gst_number:null,addresses:[],linked:[],representatives:[],tags:[],emails:[],default_shipping:null,non_billable_account:null,prevRoute:null,initial_balance:null,balance:0,transactions:[]}},beforeRouteEnter(t,e,s){s((t=>{t.prevRoute=e}))},mounted(){this.$q.loading.show(),this.$axios.get("customers/"+this.$route.params.id).then((t=>{this.name=t.data.name,this.status=t.data.status,this.type=t.data.type,this.gst_number=t.data.gst_number,this.addresses=t.data.addresses,this.linked=t.data.linked,this.representatives=t.data.representatives,this.tags=t.data.tags,this.emails=t.data.emails,this.default_shipping=t.data.default_shipping,this.non_billable_account=t.data.non_billable_account,this.initial_balance=t.data.initial_balance,this.balance=t.data.balance,this.transactions=t.data.transactions})).finally((()=>{this.$store.commit("setPageTitle","Customer: "+this.name),this.$q.loading.hide()}))},computed:{toolbarButtons(){const t=[];return!this.$store.getters.hasPermissionTo("create_sale_order")||"Active"!==this.status&&"Approved"!==this.status||t.push({label:"Create Sale Order",id:"createso",type:"a",link:"/sale_orders/create/"+this.$route.params.id,color:"blue-10",icon:"add"}),this.$store.getters.hasPermissionTo("create_sale_order")&&"Disabled"!==this.status&&t.push({label:"Create Quotation",id:"createqo",type:"a",link:"/quotations/create/"+this.$route.params.id,color:"blue-7",icon:"add"}),this.$store.getters.hasPermissionTo("approve_customer")&&"Disabled"!==this.status&&t.push({label:"Disable",id:"disable",type:"button",color:"red-10",icon:"close"}),this.$store.getters.hasPermissionTo("create_customer")&&"Draft"===this.status&&t.push({label:"Send for Approval",id:"sendforapproval",type:"button",color:"orange-10",icon:"pan_tool"}),this.$store.getters.hasPermissionTo("revert_customer")&&"Draft"!==this.status&&t.push({label:"Revert",id:"revert",type:"button",color:"grey-10",icon:"undo"}),this.$store.getters.hasPermissionTo("approve_customer")&&"Pending Approval"===this.status&&t.push({label:"Approve",id:"approve",type:"button",color:"green-10",icon:"check"}),this.$store.getters.hasPermissionTo("activate_customer")&&"Active"!==this.status&&t.push({label:"Activate",id:"activate",type:"button",color:"green-10",icon:"check"}),this.$store.getters.hasPermissionTo("update_customer")&&t.push({label:"Edit",id:"edit",type:"a",link:"/customers/edit/"+this.$route.params.id,color:"teal",icon:"edit"}),t},breadcrumbs(){if(this.prevRoute&&"PhonesView"===this.prevRoute.name)return[{label:"Dashboard",link:"/"},{label:"Phones",link:"/phones"},{label:this.$route.params.name,link:"/phones/view/"+this.prevRoute.params.id},{label:"Customers",link:"/customers"},{label:this.name}];{const t=[{label:"Dashboard",link:"/"},{label:"Customers",link:"/customers"},{label:this.name}];return t}}},methods:{getBillingAddress(){let t=null;return this.addresses.forEach(((e,s)=>{"billing"===e.tag_name&&(t=s)})),null!=t?this.addressFormat(this.addresses[t]):""},addressFormat(t){let e="";return e+=t.line_1+", ",t.line_2&&(e+=t.line_2+", "),t.district&&(e+=t.district+", "),t.pin&&(e+="PIN: "+t.pin+", "),e+=t.state+", ",e+=t.country+", ",t.phones.forEach((t=>{e+="("+t.country_code+")"+t.content+", "})),e=e.substr(0,e.length-2),e},getRepresentatives(){let t="";return this.representatives.forEach(((e,s)=>{t+=e.name+", "})),t=t.substr(0,t.length-2),t},getTags(){let t="";return this.tags.forEach(((e,s)=>{t+=e.content+", "})),t.length>0&&(t=t.substr(0,t.length-2)),t},sendForApproval(){this.$axios.get("customers/request_approval/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/customers"))}))},approve(){this.$axios.get("customers/approve/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/customers"))}))},activate(){this.$axios.get("customers/activate/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/customers"))}))},revert(){this.$axios.get("customers/revert/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/customers"))}))},disable(){this.$q.dialog({title:"Confirm",message:"Would you like disable this customer?",cancel:!0,persistent:!0}).onOk((()=>{this.$axios.get("customers/disable/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/customers"))}))}))}}},n=l,d=s("2877"),c=s("9989"),u=s("f09f"),p=s("a370"),h=s("2bb1"),m=s("eb85"),v=s("9c40"),_=s("eebe"),b=s.n(_),g=Object(d["a"])(n,a,r,!1,null,null,null);e["default"]=g.exports;b()(g,"components",{QPage:c["a"],QCard:u["a"],QCardSection:p["a"],QMarkupTable:h["a"],QSeparator:m["a"],QBtn:v["a"]})}}]);