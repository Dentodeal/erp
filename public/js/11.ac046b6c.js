(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[11],{"6eac":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",[s("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{save:function(e){return t.saveFn()}}}),s("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),s("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[s("q-card",[s("q-card-section",[s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-12 col-md-6 q-mt-md"},[s("q-input",{ref:"name",attrs:{dense:"",label:"Template Name",outlined:"",square:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.name,callback:function(e){t.name=e},expression:"name"}})],1),s("div",{staticClass:"col-12 col-md-3 q-mt-md"},[s("q-select",{ref:"created_by_id",attrs:{dense:"",label:"Created By","emit-value":"","map-options":"",readonly:"","option-value":"id","option-label":"name",outlined:"",square:"",rules:[function(t){return!!t||"Required"}],options:t.createdByOptions},model:{value:t.created_by_id,callback:function(e){t.created_by_id=e},expression:"created_by_id"}})],1),s("div",{staticClass:"col-md-3 col-12 q-mt-md"},[s("q-select",{ref:"representative",attrs:{dense:"",label:"Representative","emit-value":"","map-options":"","option-value":"id","option-label":"name",outlined:"",square:"",options:t.createdByOptions},model:{value:t.representative_id,callback:function(e){t.representative_id=e},expression:"representative_id"}})],1)]),s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-md-6 col-12 q-mt-md"},[s("q-select",{ref:"customer",attrs:{outlined:"",dense:"",square:"",label:"Customer/Lead","use-input":"","fill-input":"","hide-selected":"","option-value":"id","option-label":"name",options:t.customerOptions},on:{filter:t.customerFilterFn},scopedSlots:t._u([{key:"no-option",fn:function(){return[s("q-item",[s("q-item-section",{staticClass:"text-grey"},[t._v("\n                    No results\n                  ")])],1)]},proxy:!0}]),model:{value:t.customer,callback:function(e){t.customer=e},expression:"customer"}})],1),s("div",{staticClass:"col-12 col-md-3 q-mt-md"},[s("q-input",{ref:"valid_until",attrs:{filled:"",dense:"",readonly:"",label:"Valid Until",hint:"Click on calender icon to enter date"},scopedSlots:t._u([{key:"append",fn:function(){return[s("q-icon",{staticClass:"cursor-pointer",attrs:{name:"event"}},[s("q-popup-proxy",{ref:"qDateProxy",attrs:{"transition-show":"scale","transition-hide":"scale"}},[s("q-date",{model:{value:t.valid_until,callback:function(e){t.valid_until=e},expression:"valid_until"}},[s("div",{staticClass:"row items-center justify-end"},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Close",color:"primary",flat:""}})],1)])],1)],1)]},proxy:!0}]),model:{value:t.valid_until,callback:function(e){t.valid_until=e},expression:"valid_until"}})],1),s("div",{staticClass:"col-12 col-md-3 q-mt-md"},[s("q-input",{attrs:{filled:"",dense:"",readonly:"",label:"Ship Date",hint:"Click on calender icon to enter date"},scopedSlots:t._u([{key:"append",fn:function(){return[s("q-icon",{staticClass:"cursor-pointer",attrs:{name:"event"}},[s("q-popup-proxy",{ref:"qDateProxy",attrs:{"transition-show":"scale","transition-hide":"scale"}},[s("q-date",{model:{value:t.ship_date,callback:function(e){t.ship_date=e},expression:"ship_date"}},[s("div",{staticClass:"row items-center justify-end"},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Close",color:"primary",flat:""}})],1)])],1)],1)]},proxy:!0}]),model:{value:t.ship_date,callback:function(e){t.ship_date=e},expression:"ship_date"}})],1)]),s("div",{staticClass:"row q-col-gutter-md q-mt-md"},[s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{label:"Ship Via",outlined:"",square:""},model:{value:t.ship_via,callback:function(e){t.ship_via=e},expression:"ship_via"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{label:"PO Number",outlined:"",square:""},model:{value:t.po_number,callback:function(e){t.po_number=e},expression:"po_number"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{label:"FOB Point",outlined:"",square:""},model:{value:t.fob_point,callback:function(e){t.fob_point=e},expression:"fob_point"}})],1),s("div",{staticClass:"col-md col-12"},[s("q-select",{ref:"pricelist",attrs:{label:"Pricelist","emit-value":"","map-options":"","option-value":"id","option-label":"name",outlined:"",square:"",options:t.pricelists},model:{value:t.pricelist_id,callback:function(e){t.pricelist_id=e},expression:"pricelist_id"}})],1),s("div",{staticClass:"col-md col-12"},[s("q-select",{ref:"warehouse",attrs:{label:"Warehouse","emit-value":"","map-options":"","option-value":"id","option-label":"name",outlined:"",square:"",options:t.warehouses},model:{value:t.warehouse_id,callback:function(e){t.warehouse_id=e},expression:"warehouse_id"}})],1)]),s("div",{staticClass:"row q-mt-md q-col-gutter-md"},[s("div",{staticClass:"col-12"},[s("q-checkbox",{attrs:{label:"Rate Includes GST ?","true-value":1,"false-value":0,color:"green-10"},model:{value:t.rate_includes_gst,callback:function(e){t.rate_includes_gst=e},expression:"rate_includes_gst"}}),s("q-checkbox",{attrs:{label:"Include Flood Cess ?","true-value":1,"false-value":0,color:"orange-10"},model:{value:t.flood_cess_included,callback:function(e){t.flood_cess_included=e},expression:"flood_cess_included"}})],1)]),s("div",{staticClass:"row q-mt-md"},[s("div",{staticClass:"col"},[s("q-table",{attrs:{columns:t.columns,title:"Items",data:t.items,"row-key":"sl_no","wrap-cells":"",loading:t.table_loading,"rows-per-page-options":[0],filter:t.search},scopedSlots:t._u([{key:"body",fn:function(e){return[s("q-tr",{attrs:{props:e}},[s("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),s("q-td",[s("div",{staticClass:"text"},[t._v("\n                      "+t._s(e.row.product_name)+"\n                      "),e.row.product_id?t._e():s("q-badge",{attrs:{align:"top",outline:"",color:"primary",label:"New"}})],1),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close"},on:{hide:function(){0==e.row.product_id&&(e.row.product_name=t.capitaliseFn(e.row.product_name)),t.product=null},show:function(){t.product=e.row.product_name}},model:{value:e.row.product_name,callback:function(s){t.$set(e.row,"product_name",s)},expression:"props.row.product_name"}},[s("q-select",{attrs:{outlined:"",square:"",dense:"",label:"Product",rules:[function(t){return!!t||"Required"}],"lazy-rules":"ondemand",options:t.productOptions,"use-input":"","fill-input":"","hide-selected":"",loading:t.prodLoading,"option-value":"id","option-label":"name"},on:{filter:t.productFilter,"input-value":function(s){return e.row.product_name=t.capitaliseFn(s)},input:function(s){e.row.product_name=t.product.name,e.row.product_id=t.product.id}},scopedSlots:t._u([{key:"no-option",fn:function(){return[s("q-item",[s("q-item-section",{staticClass:"text-grey"},[t._v("\n                              No results\n                            ")])],1)]},proxy:!0}],null,!0),model:{value:t.product,callback:function(e){t.product=e},expression:"product"}})],1)],1),s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.gst)+"%\n                    "),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close"},on:{hide:function(s){return t.updateRow(e.rowIndex)}},model:{value:e.row.gst,callback:function(s){t.$set(e.row,"gst",s)},expression:"props.row.gst"}},[s("q-select",{attrs:{options:t.gstOptions,"map-options":"","emit-value":"",dense:"",autofocus:""},model:{value:e.row.gst,callback:function(s){t.$set(e.row,"gst",s)},expression:"props.row.gst"}})],1)],1),s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.qty)+"\n                    "),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close",validate:t.qtyEditValidation},on:{hide:function(s){t.updateRow(e.rowIndex),e.row.qty=parseInt(e.row.qty)}},model:{value:e.row.qty,callback:function(s){t.$set(e.row,"qty",s)},expression:"props.row.qty"}},[s("q-input",{attrs:{error:t.errorQty,"error-message":t.errorMessageQty,dense:"",autofocus:""},on:{input:t.qtyEditValidation},model:{value:e.row.qty,callback:function(s){t.$set(e.row,"qty",s)},expression:"props.row.qty"}})],1)],1),s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.is_free?"Yes":"No")+"\n                  ")]),s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.rate)+"\n                    "),e.row.is_free?t._e():s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close",validate:t.rateEditValidation},on:{hide:function(s){t.updateRow(e.rowIndex),e.row.rate=parseFloat(e.row.rate).toFixed(2)}},model:{value:e.row.rate,callback:function(s){t.$set(e.row,"rate",s)},expression:"props.row.rate"}},[s("q-input",{attrs:{error:t.errorRate,"error-message":t.errorMessageRate,dense:"",autofocus:""},on:{input:t.rateEditValidation},model:{value:e.row.rate,callback:function(s){t.$set(e.row,"rate",s)},expression:"props.row.rate"}})],1)],1),t.rate_includes_gst?t._e():s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.taxable)+"\n                  ")]),t.rate_includes_gst?t._e():s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.tax_amount)+"\n                  ")]),s("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.total)+"\n                  ")]),s("q-td",{staticClass:"text-right"},[s("q-btn",{attrs:{round:"",icon:"delete",flat:"",color:"black"},on:{click:function(s){return t.deleteFn(e.rowIndex)}}})],1)],1)]}},{key:"top-right",fn:function(){return[s("q-input",{attrs:{borderless:"",dense:"",debounce:"300",placeholder:"Search"},scopedSlots:t._u([{key:"append",fn:function(){return[s("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})]},proxy:!0}])})],1)]),s("div",{staticClass:"row q-col-gutter-xs q-mt-md"},[s("div",{staticClass:"col-4"},[s("q-select",{ref:"product",attrs:{outlined:"",square:"",dense:"",label:"Product",rules:[function(t){return!!t||"Required"}],"lazy-rules":"ondemand",options:t.productOptions,"use-input":"","fill-input":"","hide-selected":"",loading:t.prodLoading,"option-value":"id","option-label":"name"},on:{filter:t.productFilter,"input-value":function(e){return t.product=t.capitaliseFn(e)}},scopedSlots:t._u([{key:"no-option",fn:function(){return[s("q-item",[s("q-item-section",{staticClass:"text-grey"},[t._v("\n                    No results\n                  ")])],1)]},proxy:!0}]),model:{value:t.product,callback:function(e){t.product=e},expression:"product"}})],1),s("div",{staticClass:"col"},[s("q-select",{ref:"gst",attrs:{outlined:"",square:"",dense:"",rules:[function(t){return!!t||"Required"}],"lazy-rules":"ondemand",options:t.gstOptions,label:"GST","map-options":"","emit-value":""},model:{value:t.gst,callback:function(e){t.gst=e},expression:"gst"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"qty",staticClass:"input-right",attrs:{outlined:"",square:"",dense:"",label:"Qty",rules:[function(t){return!!t||"Required"},function(t){return Number.isInteger(Number(t))||"Must be an Integer"},function(t){return Number(t)>0||"Invalid"}],"lazy-rules":"ondemand",hint:t.product?t.stock&&t.stock>0?t.stock+" Available":"Out of stock":""},on:{focus:function(e){t.qty&&e.target.select()},blur:function(){t.qty=t.qty?parseInt(t.qty).toString():null}},model:{value:t.qty,callback:function(e){t.qty=e},expression:"qty"}})],1),s("div",{},[s("q-checkbox",{staticClass:"q-mt-xs",attrs:{label:"Free?",size:"xs"},model:{value:t.is_free,callback:function(e){t.is_free=e},expression:"is_free"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"rate",staticClass:"input-right",attrs:{outlined:"",readonly:t.is_free,square:"",dense:"",label:"Rate",rules:[function(t){return!!t||"Required"},function(t){return!isNaN(t)||"Invalid"},t.rateValidation],"lazy-rules":"ondemand"},on:{focus:function(e){t.rate&&e.target.select()},blur:function(){t.rate=t.rate?parseFloat(t.rate).toFixed(2):null}},model:{value:t.rate,callback:function(e){t.rate=e},expression:"rate"}})],1),t.rate_includes_gst?t._e():s("div",{staticClass:"col"},[s("q-input",{attrs:{outlined:"",square:"",dense:"",readonly:"",label:"Taxable"},model:{value:t.row_taxable,callback:function(e){t.row_taxable=e},expression:"row_taxable"}})],1),t.rate_includes_gst?t._e():s("div",{staticClass:"col"},[s("q-input",{attrs:{outlined:"",square:"",dense:"",readonly:"",label:"Tax Amount"},model:{value:t.row_tax_amount,callback:function(e){t.row_tax_amount=e},expression:"row_tax_amount"}})],1),s("div",{staticClass:"col"},[s("q-input",{attrs:{outlined:"",square:"",dense:"",readonly:"",label:"Total"},model:{value:t.row_total,callback:function(e){t.row_total=e},expression:"row_total"}})],1),s("div",{},[s("q-btn",{attrs:{icon:"add",round:"",flat:"",color:"teal"},on:{click:function(e){return t.addRow()}}})],1)]),s("div",{staticClass:"row q-mt-xs"},[s("div",{staticClass:"col-12 col-md-10 q-px-md"}),s("div",{staticClass:"col-12 col-md-2"},[s("div",{staticClass:"column"},[s("div",{staticClass:"col"},[s("q-input",{ref:"subtotal",staticClass:"input-right",attrs:{label:"Subtotal(Incl GST)",dense:"",outlined:"",square:"",readonly:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},model:{value:t.subtotal,callback:function(e){t.subtotal=e},expression:"subtotal"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"discount",staticClass:"input-right",attrs:{label:"Discount",dense:"",outlined:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.discount,callback:function(e){t.discount=e},expression:"discount"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"freight",staticClass:"input-right",attrs:{label:"Freight",dense:"",outlined:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.freight,callback:function(e){t.freight=e},expression:"freight"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"round",staticClass:"input-right",attrs:{label:"Round",dense:"",outlined:"",readonly:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.round,callback:function(e){t.round=e},expression:"round"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"total",staticClass:"input-right",attrs:{label:"Total",dense:"",outlined:"",readonly:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},model:{value:t.total,callback:function(e){t.total=e},expression:"total"}})],1)])])]),s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{label:"Contact Person",hint:"If left blank, Representative set above will be used",outlined:"",square:""},model:{value:t.contact_person,callback:function(e){t.contact_person=e},expression:"contact_person"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{ref:"contact_phone",attrs:{label:"Contact Phone",outlined:"",square:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.contact_phone,callback:function(e){t.contact_phone=e},expression:"contact_phone"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{ref:"contact_email",attrs:{label:"Contact Email",outlined:"",square:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.contact_email,callback:function(e){t.contact_email=e},expression:"contact_email"}})],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle1 q-mt-md"},[t._v("Instructions")]),s("q-editor",{attrs:{"min-height":"5rem"},model:{value:t.instructions,callback:function(e){t.instructions=e},expression:"instructions"}})],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle1 q-mt-md"},[t._v("Terms")]),s("q-editor",{attrs:{"min-height":"5rem"},model:{value:t.terms,callback:function(e){t.terms=e},expression:"terms"}})],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle1 q-mt-md"},[t._v("Remarks")]),s("q-editor",{attrs:{"min-height":"5rem"},model:{value:t.remarks,callback:function(e){t.remarks=e},expression:"remarks"}})],1)])])],1)],1),s("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":"",buttons:t.toolbarButtons},on:{save:function(e){return t.saveFn()}}})],1)},i=[],o=(s("a434"),s("e6cf"),s("a79d"),s("ddb0"),s("a89c")),l=s("b6c6"),n={name:"QuotationsCreate",components:{PageToolbar:o["a"],Breadcrumbs:l["a"]},data(){return{name:null,rate_includes_gst:1,serial_no:null,customer:null,customerOptions:[],warehouses:[],pricelists:[],warehouse_id:null,pricelist_id:null,items:[],subtotal:0,total:0,round:0,discount:0,freight:0,flood_cess_included:0,representative_id:null,representativeOptions:[],remarks:"",instructions:"",terms:"",status:"Draft",search:null,table_loading:!1,product:null,gst:null,qty:null,rate:null,is_free:!1,row_taxable:null,row_tax_amount:null,row_total:null,prodLoading:!1,productOptions:[],errorQty:!1,errorMessageQty:null,errorRate:!1,errorMessageRate:null,gstOptions:[],created_by_id:null,createdByOptions:[],stock:null,valid_until:null,fob_point:null,po_number:null,contact_person:null,contact_phone:null,contact_email:null,ship_date:null,ship_via:null,stockOptions:[],cost:null,min_margin:0}},computed:{toolbarButtons(){const t=[];return this.$store.getters.hasPermissionTo("create_quotation")&&t.push({label:"Save",id:"save",type:"button",color:"teal",icon:"save"}),t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Quotations",link:"/quotations"},{label:this.$route.params.id?"Edit":"Create",link:"/quotation_templates/view/"+this.$route.params.id}];return this.$route.params.id&&t.push({label:"Edit",link:""}),t},columns(){const t=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:"prouduct_name",label:"Product",sortable:!0,align:"left"},{name:"gst",field:"gst",label:"GST",sortable:!0},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"is_free",field:"is_free",label:"Free",sortable:!0},{name:"rate",field:"rate",label:"Rate",sortable:!0}];return this.rate_includes_gst||t.push({name:"taxable",field:"taxable",label:"Taxable",sortable:!0},{name:"tax_amount",field:"tax_amount",label:"Tax Amount",sortable:!0}),t.push({name:"total",field:"total",label:"Total",sortable:!0},{name:"actions",field:"actions",label:"",sortable:!1}),t}},mounted(){if(this.$store.getters.hasPermissionTo("create_quotation")){this.$q.loading.show();const t=["create_quotation","update_quotation"];Promise.all([this.$axios.get("warehouses").then((t=>{this.warehouses=t.data.model,1===this.warehouses.length&&(this.warehouse_id=this.warehouses[0].id)})),this.$axios.get("pricelists").then((t=>{this.pricelists=t.data.model,1===this.pricelists.length&&(this.pricelist_id=this.pricelists[0].id)})),this.$axios.get("gst_options").then((t=>{this.gstOptions=t.data})),this.$axios.get("users/has_permission?permissions="+encodeURIComponent(JSON.stringify(t))).then((t=>{this.createdByOptions=t.data,this.created_by_id=this.$store.getters.user.id}))]).finally((()=>{this.$q.loading.hide()})),this.$route.params.id&&(this.$q.loading.show(),this.$axios.get("quotation_templates/"+this.$route.params.id).then((t=>{this.name=t.data.name,this.customer=t.data.customer,this.representative_id=t.data.representative?t.data.representative.id:null,this.created_by_id=t.data.created_by.id,this.status=t.data.status,this.remarks=t.data.remarks?t.data.remarks:"",this.instructions=t.data.instructions?t.data.instructions:"",this.terms=t.data.terms?t.data.terms:"",this.warehouse_id=t.data.warehouse_id,this.pricelist_id=t.data.pricelist_id,this.rate_includes_gst=t.data.rate_includes_gst,this.flood_cess_included=t.data.flood_cess_included,this.items=t.data.items,this.subtotal=t.data.subtotal,this.discount=t.data.discount,this.freight=t.data.freight,this.round=t.data.round,this.total=t.data.total,this.valid_until=t.data.valid_until,this.ship_date=t.data.ship_date,this.ship_via=t.data.ship_via,this.po_number=t.data.po_number,this.fob_point=t.data.fob_point,this.contact_person=t.data.contact_person,this.contact_phone=t.data.contact_phone,this.contact_email=t.data.contact_email})).finally((()=>{this.$q.loading.hide()})))}else this.$router.push({name:"Error403"})},methods:{addRow(){this.$refs.product.validate()&this.$refs.gst.validate()&this.$refs.qty.validate()&this.$refs.rate.validate()&&(this.items.push({product_name:"object"===typeof this.product?this.product.name:this.product,product_id:"object"===typeof this.product?this.product.id:0,gst:this.gst,qty:this.qty,is_free:this.is_free,rate:this.rate,taxable:this.row_taxable,tax_amount:this.row_tax_amount,total:this.row_total,stock:this.stock}),this.addtoSubtotal(this.row_total),this.product=null,this.gst=null,this.qty=null,this.is_free=!1,this.rate=null,this.row_discount=null,this.row_taxable=null,this.row_tax_amount=null,this.row_total=null,this.stock=null,this.$nextTick((()=>{this.$refs.product.focus()})))},addtoSubtotal(t){this.subtotal=(parseFloat(this.subtotal)+parseFloat(t)).toFixed(2)},deleteFn(t){this.$q.dialog({title:"Confirm",message:"Would you like to delete this record?",cancel:!0,persistent:!0}).onOk((()=>{const e=parseFloat(this.items[t].total);this.subtotal=parseFloat(this.subtotal)-e,this.items.splice(t,1)})).onCancel((()=>{}))},updateRow(t){const e=parseFloat(this.items[t].total),s=parseInt(this.items[t].qty),a=parseFloat(this.items[t].rate),i=parseFloat(this.items[t].gst);let o=0;if(this.rate_includes_gst)o=a*s,this.items[t].total=o.toFixed(2);else{let e=0;e=a*s,this.items[t].taxable=e.toFixed(2);const l=e*(i/100);this.items[t].taxAmount=l.toFixed(2),o=e+l,this.items[t].total=o.toFixed(2)}const l=o-e;this.addtoSubtotal(l)},updateTotal(){const t=parseFloat(this.subtotal),e=parseFloat(this.discount),s=parseFloat(this.freight);let a=0;a=t-e+s,this.round=(a-Math.round(a)).toFixed(2),this.total=Math.round(a).toFixed(2)},customerFilterFn(t,e,s){e((()=>{t&&this.$axios.get("customers_search?billable=1&search="+encodeURIComponent(t)).then((t=>{this.customerOptions=t.data}))}))},qtyEditValidation(t){return t&&Number.isInteger(Number(t))?(this.errorQty=!1,this.errorMessageQty="",!0):(this.errorQty=!0,this.errorMessageQty="Invalid Entry",!1)},rateEditValidation(t){return t&&!isNaN(t)&&parseFloat(t)>0?(this.errorRate=!1,this.errorMessageRate="",!0):(this.errorRate=!0,this.errorMessageRate="Invalid Entry",!1)},rateValidation(t){return!!this.is_free||(parseFloat(this.rate)>0?!(this.cost&&t<parseFloat(this.cost)*(1+parseFloat(this.min_margin)/100))||"Below Min Margin":"Invalid")},updateFields(){const t=this.qty?parseInt(this.qty):0,e=this.rate?parseFloat(this.rate):0,s=this.gst?parseFloat(this.gst):0;if(this.rate_includes_gst)this.row_total=(e*t).toFixed(2);else{let a=0;a=e*t,this.row_taxable=a.toFixed(2);const i=a*(s/100);this.row_tax_amount=i.toFixed(2);const o=a+i;this.row_total=o.toFixed(2)}},productFilter(t,e,s){e((()=>{t&&(this.prodLoading=!0,this.$axios.get("products/search?&keyword="+encodeURIComponent(t)).then((t=>{this.productOptions=t.data})).finally((()=>{this.prodLoading=!1})))}))},saveFn(t="Draft"){if(this.$refs.created_by_id.validate()&this.$refs.name.validate()){const e={_method:this.$route.params.id?"PUT":"POST",name:this.name,serial_no:this.serial_no,status:t,customer_id:this.customer?this.customer.id:null,created_by_id:this.created_by_id,representative_id:this.representative_id,pricelist_id:this.pricelist_id,warehouse_id:this.warehouse_id,items:this.items,remarks:this.remarks,instructions:this.instructions,terms:this.terms,valid_until:this.valid_until,fob_point:this.fob_point,po_number:this.po_number,subtotal:this.subtotal,discount:this.discount,freight:this.freight,rate_includes_gst:this.rate_includes_gst,flood_cess_included:this.flood_cess_included,round:this.round,total:this.total,contact_person:this.contact_person,contact_email:this.contact_email,contact_phone:this.contact_phone,ship_date:this.ship_date,ship_via:this.ship_via};let s="quotation_templates";this.$route.params.id&&(s="quotation_templates/"+this.$route.params.id),this.$q.loading.show(),this.$axios.post(s,e).then((t=>{"success"===t.data.message&&(this.$q.loading.hide(),this.$q.notify({type:"positive",message:"Saved Succesfully."}),this.$router.back())})).catch((t=>{this.$q.loading.hide(),422===t.response.status&&(this.$q.notify({type:"negative",message:t.response.data.message}),t.response.data.errors.forEach((t=>{this.$q.notify({type:"negative",message:t})})))})).finally((()=>{this.$q.loading.hide()}))}else this.$q.notify({type:"negative",message:"Invalid data given !!!"})},validateItemsCount(){return this.items.length>0||(this.$q.notify({type:"negative",message:"There should be atleast one item"}),!1)},capitaliseFn(t){if(t){const e=t.split(" ");for(let t=0;t<e.length;t++)e[t]=e[t].charAt(0).toUpperCase()+e[t].substring(1);return e.join(" ")}return""}},watch:{is_free(t,e){t&&(this.rate="0.00",this.$nextTick((()=>{this.$refs.rate.validate()})))},product(t,e){t&&"object"===typeof t?this.$axios.get("products/basic/"+t.id+"/"+this.warehouse_id).then((t=>{this.gst=t.data.gst,this.expirable=t.data.expirable,this.row_weight=t.data.weight,this.hsn=t.data.hsn,this.mrp=t.data.mrp,this.stock=t.data.available_stock,this.stockOptions=t.data.stock_options,this.cost=t.data.cost?t.data.cost:0,this.min_margin=t.data.min_margin?t.data.min_margin:0})):(this.gst=null,this.stock=null)},qty(t,e){this.updateFields()},rate(t,e){this.updateFields()},row_discount(t,e){this.updateFields()},gst(t,e){this.updateFields()},subtotal(){this.updateTotal()},discount(){this.updateTotal()},freight(){this.updateTotal()},rate_includes_gst(t,e){if(t){this.$q.loading.show();let t=0;this.items.forEach(((e,s)=>{let a=0;const i=parseFloat(e.rate),o=parseInt(e.qty);a=o*i,t+=parseFloat(a.toFixed(2)),this.items[s].total=a.toFixed(2)})),this.subtotal=t,this.$q.loading.hide()}else{this.$q.loading.show();let t=0;this.items.forEach(((e,s)=>{let a=0,i=0,o=0;const l=parseFloat(e.rate),n=parseInt(e.qty);let r=parseFloat(e.gst);this.flood_cess_included&&(r+=1),i=l*n,o=i*(r/100),a=i+o,this.items[s].taxable=i.toFixed(2),this.items[s].tax_amount=o.toFixed(2),this.items[s].total=a.toFixed(2),t+=parseFloat(a.toFixed(2))})),this.subtotal=t,this.$q.loading.hide()}},flood_cess_included(t,e){if(!this.rate_includes_gst){this.$q.loading.show();let t=0;this.items.forEach(((e,s)=>{let a=0,i=0,o=0;const l=parseFloat(e.rate),n=parseInt(e.qty);let r=parseFloat(e.gst);this.flood_cess_included&&(r+=1),i=l*n,o=i*(r/100),a=i+o,this.items[s].taxable=i.toFixed(2),this.items[s].tax_amount=o.toFixed(2),this.items[s].total=a.toFixed(2),t+=parseFloat(a.toFixed(2))})),this.subtotal=t,this.$q.loading.hide()}}}},r=n,d=s("2877"),c=s("9989"),u=s("f09f"),p=s("a370"),h=s("27f9"),m=s("ddd8"),_=s("66e5"),b=s("4074"),f=s("0016"),v=s("7cbe"),q=s("52ee"),g=s("9c40"),x=s("8f8e"),w=s("eaac"),y=s("bd08"),C=s("db86"),k=s("58a81"),F=s("42a1"),$=s("d66b"),I=s("7f67"),R=s("eebe"),S=s.n(R),Q=Object(d["a"])(r,a,i,!1,null,null,null);e["default"]=Q.exports;S()(Q,"components",{QPage:c["a"],QCard:u["a"],QCardSection:p["a"],QInput:h["a"],QSelect:m["a"],QItem:_["a"],QItemSection:b["a"],QIcon:f["a"],QPopupProxy:v["a"],QDate:q["a"],QBtn:g["a"],QCheckbox:x["a"],QTable:w["a"],QTr:y["a"],QTd:C["a"],QBadge:k["a"],QPopupEdit:F["a"],QEditor:$["a"]}),S()(Q,"directives",{ClosePopup:I["a"]})}}]);