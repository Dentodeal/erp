(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[12],{cc36:function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",[s("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{save:function(e){return t.saveFn()},load:function(e){t.templateDialog=!0}}}),s("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),s("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[s("q-card",[s("q-card-section",[s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-md-3 col-12 q-mt-md"},[s("q-select",{attrs:{options:["Standard","Export"],filled:"",dense:"",disable:t.$route.params.id&&t.$route.params.id>0,square:"",label:"Type"},on:{input:t.onTypeChange},model:{value:t.model.type,callback:function(e){t.$set(t.model,"type",e)},expression:"model.type"}})],1),s("div",{staticClass:"col-md-3 col-12 q-mt-md"},[s("q-input",{attrs:{readonly:"",dense:"",filled:"",square:"",label:"Status"},model:{value:t.model.status,callback:function(e){t.$set(t.model,"status",e)},expression:"model.status"}})],1),s("div",{staticClass:"col-12 col-md-3 q-mt-md"},[s("q-select",{ref:"created_by",attrs:{label:"Created By",dense:"",readonly:"","option-value":"id","option-label":"name",filled:"",square:"",rules:[function(t){return!!t||"Required"}],options:t.createdByOptions},model:{value:t.model.created_by,callback:function(e){t.$set(t.model,"created_by",e)},expression:"model.created_by"}})],1),s("div",{staticClass:"col-md-3 col-12 q-mt-md"},[s("q-select",{ref:"representative",attrs:{label:"Representative",dense:"","option-value":"id","option-label":"name",filled:"",square:"",options:t.createdByOptions,rules:[function(t){return!!t||"Required"}]},model:{value:t.model.representative,callback:function(e){t.$set(t.model,"representative",e)},expression:"model.representative"}})],1)]),s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-md-6 col-12 q-mt-md"},[s("q-select",{ref:"customer",attrs:{filled:"",square:"",dense:"",label:"Customer/Lead","use-input":"","fill-input":"","hide-selected":"","option-value":"id","option-label":"name",options:t.customerOptions,rules:[function(t){return!!t||"Required"}]},on:{filter:t.customerFilterFn,input:function(e){return t.loadAddress()}},scopedSlots:t._u([{key:"no-option",fn:function(){return[s("q-item",[s("q-item-section",{staticClass:"text-grey"},[t._v("\n                    No results\n                  ")])],1)]},proxy:!0}]),model:{value:t.model.customer,callback:function(e){t.$set(t.model,"customer",e)},expression:"model.customer"}})],1),s("div",{staticClass:"col-12 col-md-3 q-mt-md"},[s("q-input",{ref:"valid_until",attrs:{filled:"",dense:"",readonly:"",label:"Valid Until",hint:"Click on calender icon to enter date",rules:[function(t){return!!t||"Required"}]},scopedSlots:t._u([{key:"append",fn:function(){return[s("q-icon",{staticClass:"cursor-pointer",attrs:{name:"event"}},[s("q-popup-proxy",{ref:"qDateProxy",attrs:{"transition-show":"scale","transition-hide":"scale"}},[s("q-date",{model:{value:t.model.valid_until,callback:function(e){t.$set(t.model,"valid_until",e)},expression:"model.valid_until"}},[s("div",{staticClass:"row items-center justify-end"},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Close",color:"primary",flat:""}})],1)])],1)],1)]},proxy:!0}]),model:{value:t.model.valid_until,callback:function(e){t.$set(t.model,"valid_until",e)},expression:"model.valid_until"}})],1),s("div",{staticClass:"col-12 col-md-3 q-mt-md"},[s("q-input",{attrs:{filled:"",dense:"",readonly:"",label:"Ship Date",hint:"Click on calender icon to enter date"},scopedSlots:t._u([{key:"append",fn:function(){return[s("q-icon",{staticClass:"cursor-pointer",attrs:{name:"event"}},[s("q-popup-proxy",{ref:"qDateProxy",attrs:{"transition-show":"scale","transition-hide":"scale"}},[s("q-date",{model:{value:t.model.ship_date,callback:function(e){t.$set(t.model,"ship_date",e)},expression:"model.ship_date"}},[s("div",{staticClass:"row items-center justify-end"},[s("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{label:"Close",color:"primary",flat:""}})],1)])],1)],1)]},proxy:!0}]),model:{value:t.model.ship_date,callback:function(e){t.$set(t.model,"ship_date",e)},expression:"model.ship_date"}})],1)]),t.model.billingAddress?s("div",{staticClass:"row"},[s("div",{staticClass:"col-12 col-md-6"},[t._v("\n            "+t._s(t.model.billingAddress)+"\n          ")])]):t._e(),s("div",{staticClass:"row q-col-gutter-md q-mt-md"},[s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{dense:"",label:"Ship Via",filled:"",square:""},model:{value:t.model.ship_via,callback:function(e){t.$set(t.model,"ship_via",e)},expression:"model.ship_via"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{dense:"",label:"PO Number",filled:"",square:""},model:{value:t.model.po_number,callback:function(e){t.$set(t.model,"po_number",e)},expression:"model.po_number"}})],1),s("div",{staticClass:"col-12 col-md-auto"},[s("q-select",{attrs:{"emit-value":"","map-options":"",dense:"",options:t.incoTerms,label:"Inco Term",filled:"",square:"",hint:"International Commercial Term"},model:{value:t.model.inco_term,callback:function(e){t.$set(t.model,"inco_term",e)},expression:"model.inco_term"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{dense:"",label:"Shipping Point",filled:"",square:""},model:{value:t.model.fob_point,callback:function(e){t.$set(t.model,"fob_point",e)},expression:"model.fob_point"}})],1),s("div",{staticClass:"col-md col-12"},[s("q-select",{ref:"pricelist",attrs:{label:"Pricelist",dense:"","option-value":"id","option-label":"name",filled:"",square:"",options:t.pricelists,rules:[function(t){return!!t||"Required"}]},model:{value:t.model.pricelist,callback:function(e){t.$set(t.model,"pricelist",e)},expression:"model.pricelist"}})],1),s("div",{staticClass:"col-md col-12"},[s("q-select",{ref:"warehouse",attrs:{label:"Warehouse",dense:"","option-value":"id","option-label":"name",filled:"",square:"",options:t.warehouses,rules:[function(t){return!!t||"Required"}]},model:{value:t.model.warehouse,callback:function(e){t.$set(t.model,"warehouse",e)},expression:"model.warehouse"}})],1)]),s("div",{staticClass:"row q-mt-md q-col-gutter-md"},["Export"!==t.model.type?s("div",{staticClass:"col-12 col-md-3"},[s("q-checkbox",{attrs:{label:"Rate Includes GST ?",color:"green-10"},model:{value:t.model.rate_includes_gst,callback:function(e){t.$set(t.model,"rate_includes_gst",e)},expression:"model.rate_includes_gst"}})],1):t._e(),"Export"!==t.model.type?s("div",{staticClass:"col-12 col-md-3"},[s("q-checkbox",{attrs:{label:"Include Flood Cess ?","true-value":1,"false-value":0,color:"orange-10",disable:""},model:{value:t.model.flood_cess_included,callback:function(e){t.$set(t.model,"flood_cess_included",e)},expression:"model.flood_cess_included"}})],1):t._e(),s("div",{staticClass:"col-12 col-md-3"},[s("q-select",{ref:"bank",attrs:{options:t.bankAccounts,dense:"",filled:"","option-label":"name",label:"Bank Account",rules:[function(t){return!!t||"Required"}]},scopedSlots:t._u([{key:"option",fn:function(e){return[s("q-item",t._g(t._b({},"q-item",e.itemProps,!1),e.itemEvents),[s("q-item-section",[s("q-item-label",[t._v(t._s(e.opt.name))]),s("q-item-label",{attrs:{caption:""}},[t._v("Account Name: "+t._s(e.opt.acc_name))]),s("q-item-label",{attrs:{caption:""}},[t._v("Bank: "+t._s(e.opt.bank_name))]),s("q-item-label",{attrs:{caption:""}},[t._v("Acc No. "+t._s(e.opt.acc_no))]),s("q-item-label",{attrs:{caption:""}},[t._v("IFSC: "+t._s(e.opt.ifsc))]),s("q-item-label",{attrs:{caption:""}},[t._v("UPI: "+t._s(e.opt.upi))])],1)],1)]}}]),model:{value:t.model.bank,callback:function(e){t.$set(t.model,"bank",e)},expression:"model.bank"}})],1)]),s("div",{staticClass:"row q-mt-md"},[s("div",{staticClass:"col"},[s("q-markup-table",[s("thead",[s("tr",[s("th",{staticClass:"text-left"},[t._v("#")]),s("th",{staticClass:"text-left"},[t._v("Product")]),"Export"!==t.model.type?s("th",{staticClass:"text-right"},[t._v("GST")]):t._e(),s("th",{staticClass:"text-right"},[t._v("Qty")]),s("th",{staticClass:"text-right"},[t._v("Rate")]),t.rate_includes_gst||"Export"===t.model.type?t._e():s("th",{staticClass:"text-right"},[t._v("Taxable")]),t.rate_includes_gst||"Export"===t.model.type?t._e():s("th",{staticClass:"text-right"},[t._v("Tax Amount")]),s("th",{staticClass:"text-right"},[t._v("Total")]),s("th",{staticClass:"text-right"})])]),s("draggable",{attrs:{handle:".handle",tag:"tbody"},model:{value:t.model.items,callback:function(e){t.$set(t.model,"items",e)},expression:"model.items"}},t._l(t.model.items,(function(e,a){return s("tr",{key:a},[s("td",{staticClass:"handle"},[s("q-icon",{staticClass:"text-grey",staticStyle:{"font-size":"32px",cursor:"pointer"},attrs:{name:"drag_indicator"}}),t._v(t._s(a+1))],1),s("td",[t._v(t._s(e.product_name)+"\n                      "),e.product_id?t._e():s("q-badge",{attrs:{align:"top",outline:"",color:"primary",label:"New"}}),s("q-btn",{attrs:{flat:"",icon:"visibility",round:"",size:"sm"},on:{click:function(s){return s.stopPropagation(),t.showMaskDetails(e,a)}}},[s("q-tooltip",[t._v("View Masking Details")])],1),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close"},on:{hide:function(){0==e.product_id&&(t.model.items[a].product_name=t.capitaliseFn(t.model.items[a].product_name)),t.product=null},show:function(){t.product=e.product_name}},model:{value:t.model.items[a].product_name,callback:function(e){t.$set(t.model.items[a],"product_name",e)},expression:"model.items[i].product_name"}},[s("q-select",{attrs:{filled:"",square:"",dense:"",label:"Product",rules:[function(t){return!!t||"Required"}],"lazy-rules":"ondemand",options:t.productOptions,"use-input":"","fill-input":"","hide-selected":"",loading:t.prodLoading,"option-value":"id","option-label":"name"},on:{filter:t.productFilter,"input-value":function(e){return t.model.items[a].product_name=t.capitaliseFn(e)},input:function(e){t.model.items[a].product_name=t.product.name,t.model.items[a].product_id=t.product.id}},scopedSlots:t._u([{key:"no-option",fn:function(){return[s("q-item",[s("q-item-section",{staticClass:"text-grey"},[t._v("\n                                No results\n                              ")])],1)]},proxy:!0}],null,!0),model:{value:t.product,callback:function(e){t.product=e},expression:"product"}})],1)],1),"Export"!==t.model.type?s("td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.gst)+"%\n                      "),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close"},on:{hide:function(e){return t.updateRow(a)}},model:{value:t.model.items[a].gst,callback:function(e){t.$set(t.model.items[a],"gst",e)},expression:"model.items[i].gst"}},[s("q-select",{attrs:{options:t.gstOptions,"map-options":"","emit-value":"",dense:"",autofocus:""},model:{value:t.model.items[a].gst,callback:function(e){t.$set(t.model.items[a],"gst",e)},expression:"model.items[i].gst"}})],1)],1):t._e(),s("td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.qty)+"\n                      "),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close",validate:function(e){return!0===t.qtyEditValidation(e)}},on:{hide:function(e){t.updateRow(a),t.model.items[a].qty=parseInt(t.model.items[a].qty)}},model:{value:t.model.items[a].qty,callback:function(e){t.$set(t.model.items[a],"qty",e)},expression:"model.items[i].qty"}},[s("q-input",{ref:"qty_edit",refInFor:!0,attrs:{rules:[t.qtyEditValidation],dense:"",autofocus:""},model:{value:t.model.items[a].qty,callback:function(e){t.$set(t.model.items[a],"qty",e)},expression:"model.items[i].qty"}})],1)],1),s("td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.rate)+"\n                      "),s("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close",validate:function(e){return!0===t.rateEditValidation(e)}},on:{hide:function(e){t.updateRow(a),t.model.items[a].rate=parseFloat(t.model.items[a].rate).toFixed(2)}},model:{value:t.model.items[a].rate,callback:function(e){t.$set(t.model.items[a],"rate",e)},expression:"model.items[i].rate"}},[s("q-input",{ref:"rate_edit",refInFor:!0,attrs:{rules:[t.rateEditValidation],dense:"",autofocus:""},model:{value:t.model.items[a].rate,callback:function(e){t.$set(t.model.items[a],"rate",e)},expression:"model.items[i].rate"}})],1)],1),t.rate_includes_gst?t._e():s("td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.taxable)+"\n                    ")]),t.rate_includes_gst?t._e():s("td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.tax_amount)+"\n                    ")]),s("td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.total)+"\n                    ")]),s("td",{staticClass:"text-right"},[s("q-btn",{attrs:{round:"",icon:"delete",flat:"",color:"black"},on:{click:function(e){return t.deleteFn(a)}}})],1)])})),0)],1)],1)]),s("q-form",{ref:"rowForm"},[s("div",{staticClass:"row q-col-gutter-xs q-mt-md"},[s("div",{staticClass:"col-4"},[s("q-select",{ref:"product",attrs:{filled:"",square:"",dense:"",label:"Product",rules:[function(t){return!!t||"Required"}],"lazy-rules":"ondemand",options:t.productOptions,"use-input":"","fill-input":"","hide-selected":"",loading:t.prodLoading,"option-value":"id","option-label":"name"},on:{filter:t.productFilter,"input-value":function(e){return t.product=t.capitaliseFn(e)}},scopedSlots:t._u([{key:"no-option",fn:function(){return[s("q-item",[s("q-item-section",{staticClass:"text-grey"},[t._v("\n                      No results\n                    ")])],1)]},proxy:!0}]),model:{value:t.product,callback:function(e){t.product=e},expression:"product"}})],1),"Export"===t.model.type?s("div",{},[s("q-checkbox",{attrs:{label:"Use Mask?"},model:{value:t.useMask,callback:function(e){t.useMask=e},expression:"useMask"}})],1):t._e(),"Export"!==t.model.type?s("div",{staticClass:"col"},[s("q-select",{ref:"gst",attrs:{filled:"",square:"",dense:"",rules:[function(t){return!!t||"Required"}],"lazy-rules":"ondemand",options:t.gstOptions,label:"GST","map-options":"","emit-value":""},model:{value:t.gst,callback:function(e){t.gst=e},expression:"gst"}})],1):t._e(),s("div",{staticClass:"col"},[s("q-input",{ref:"qty",staticClass:"input-right",attrs:{filled:"",square:"",dense:"",label:"Qty",rules:[function(t){return!!t||"Required"},function(t){return Number.isInteger(Number(t))||"Must be an Integer"},function(t){return Number(t)>0||"Invalid"}],"lazy-rules":"ondemand",hint:t.product?t.stock&&t.stock>0?t.stock+" Available":"Out of stock":""},on:{focus:function(e){t.qty&&e.target.select()},blur:function(){t.qty=t.qty?parseInt(t.qty).toString():null}},model:{value:t.qty,callback:function(e){t.qty=e},expression:"qty"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"rate",staticClass:"input-right",attrs:{filled:"",square:"",dense:"",label:"Rate",rules:[t.rateValidation],"lazy-rules":"ondemand"},on:{focus:function(e){t.rate&&e.target.select()},blur:function(){t.rate=t.rate?parseFloat(t.rate).toFixed(2):null}},model:{value:t.rate,callback:function(e){t.rate=e},expression:"rate"}})],1),t.rate_includes_gst?t._e():s("div",{staticClass:"col"},[s("q-input",{attrs:{filled:"",square:"",dense:"",readonly:"",label:"Taxable"},model:{value:t.row_taxable,callback:function(e){t.row_taxable=e},expression:"row_taxable"}})],1),t.rate_includes_gst?t._e():s("div",{staticClass:"col"},[s("q-input",{attrs:{filled:"",square:"",dense:"",readonly:"",label:"Tax Amount"},model:{value:t.row_tax_amount,callback:function(e){t.row_tax_amount=e},expression:"row_tax_amount"}})],1),s("div",{staticClass:"col"},[s("q-input",{attrs:{filled:"",square:"",dense:"",readonly:"",label:"Total"},model:{value:t.row_total,callback:function(e){t.row_total=e},expression:"row_total"}})],1),s("div",{},[s("q-btn",{attrs:{icon:"add",round:"",flat:"",color:"teal"},on:{click:function(e){return t.addRowValidation()}}})],1)]),s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-12 col-md-4"},[t.useMask?s("q-input",{attrs:{filled:"",dense:"",label:"Mask Name"},model:{value:t.maskName,callback:function(e){t.maskName=e},expression:"maskName"}}):t._e()],1)])]),s("div",{staticClass:"row q-mt-xs"},[s("div",{staticClass:"col-12 col-md-10 q-px-md"},[t._v("\n          Total Weight: "+t._s(t.totalWeight)+"gm\n          ")]),s("div",{staticClass:"col-12 col-md-2"},[s("div",{staticClass:"column"},[s("div",{staticClass:"col"},[s("q-input",{ref:"subtotal",staticClass:"input-right",attrs:{label:"Subtotal(Incl GST)",dense:"",filled:"",square:"",readonly:"",rules:[function(t){return t>0||"Invalid"},function(t){return!isNaN(t)||"Invalid"}]},model:{value:t.model.subtotal,callback:function(e){t.$set(t.model,"subtotal",e)},expression:"model.subtotal"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"discount",staticClass:"input-right",attrs:{label:"Discount",dense:"",filled:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.model.discount,callback:function(e){t.$set(t.model,"discount",e)},expression:"model.discount"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"freight",staticClass:"input-right",attrs:{label:"Freight",dense:"",filled:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.model.freight,callback:function(e){t.$set(t.model,"freight",e)},expression:"model.freight"}})],1),"Export"===t.model.type?s("div",{staticClass:"col"},[s("q-input",{ref:"prev_balance",staticClass:"input-right",attrs:{label:"Prev. Balance",dense:"",filled:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.model.prev_balance,callback:function(e){t.$set(t.model,"prev_balance",e)},expression:"model.prev_balance"}})],1):t._e(),"Export"===t.model.type?s("div",{staticClass:"col"},[s("q-input",{ref:"bank_charges",staticClass:"input-right",attrs:{label:"Bank Charges",dense:"",filled:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.model.bank_charges,callback:function(e){t.$set(t.model,"bank_charges",e)},expression:"model.bank_charges"}})],1):t._e(),s("div",{staticClass:"col"},[s("q-input",{ref:"round",staticClass:"input-right",attrs:{label:"Round",dense:"",filled:"",readonly:"",square:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(t){return t.target.select()}},model:{value:t.model.round,callback:function(e){t.$set(t.model,"round",e)},expression:"model.round"}})],1),s("div",{staticClass:"col"},[s("q-input",{ref:"total",staticClass:"input-right",attrs:{label:"Total",dense:"",filled:"",readonly:"",square:"",rules:[function(t){return t>0||"Invalid"},function(t){return!isNaN(t)||"Invalid"}]},model:{value:t.model.total,callback:function(e){t.$set(t.model,"total",e)},expression:"model.total"}})],1)])])]),s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-12 col-md"},[s("q-input",{attrs:{label:"Contact Person",hint:"If left blank, Representative set above will be used",filled:"",square:"",dense:""},model:{value:t.model.contact_person,callback:function(e){t.$set(t.model,"contact_person",e)},expression:"model.contact_person"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{ref:"contact_phone",attrs:{label:"Contact Phone",filled:"",dense:"",square:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.model.contact_phone,callback:function(e){t.$set(t.model,"contact_phone",e)},expression:"model.contact_phone"}})],1),s("div",{staticClass:"col-12 col-md"},[s("q-input",{ref:"contact_email",attrs:{label:"Contact Email",filled:"",dense:"",square:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.model.contact_email,callback:function(e){t.$set(t.model,"contact_email",e)},expression:"model.contact_email"}})],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle1 q-mt-md"},[t._v("Instructions")]),s("q-editor",{attrs:{"min-height":"5rem"},model:{value:t.model.instructions,callback:function(e){t.$set(t.model,"instructions",e)},expression:"model.instructions"}})],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle1 q-mt-md"},[t._v("Terms")]),s("q-editor",{attrs:{"min-height":"5rem"},model:{value:t.model.terms,callback:function(e){t.$set(t.model,"terms",e)},expression:"model.terms"}}),s("div",{staticClass:"text-caption"},[t._v("The terms in this text box will be added along with default terms & conditions in quotation print")])],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle1 q-mt-md"},[t._v("Remarks")]),s("q-editor",{attrs:{"min-height":"5rem"},model:{value:t.model.remarks,callback:function(e){t.$set(t.model,"remarks",e)},expression:"model.remarks"}})],1)])],1)],1)],1),s("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":"",buttons:t.toolbarButtons},on:{save:function(e){return t.saveFn()}}}),s("q-drawer",{attrs:{width:500,persistent:"",overlay:"",bordered:"",elevated:"",breakpoint:1400,side:"right",behaviour:"mobile","content-class":"bg-grey-3"},model:{value:t.templateDialog,callback:function(e){t.templateDialog=e},expression:"templateDialog"}},[s("q-scroll-area",{staticClass:"fit"},[s("q-table",{attrs:{title:"Quotation Templates",data:t.quotation_templates,columns:[{name:"name",label:"Name",field:"name",align:"left",sortable:!0},{name:"actions",label:"Actions",field:"actions",align:"right",sortable:!1}],"row-key":"name"},scopedSlots:t._u([{key:"body",fn:function(e){return[s("q-tr",{attrs:{props:e}},[s("q-td",{key:"name",attrs:{props:e}},[t._v("\n              "+t._s(e.row.name)+"\n            ")]),s("q-td",{key:"actions",attrs:{props:e}},[s("q-btn",{attrs:{color:"primary",label:"Load"},on:{click:function(s){return t.loadTemplate(e.row.id)}}})],1)],1)]}}])}),s("q-btn",{staticClass:"q-mt-md",attrs:{label:"Close",color:"primary"},on:{click:function(e){t.templateDialog=!1}}})],1)],1),s("q-dialog",{model:{value:t.maskDialog,callback:function(e){t.maskDialog=e},expression:"maskDialog"}},[s("q-card",{staticStyle:{width:"500px"}},[s("q-card-section",[s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("q-checkbox",{attrs:{label:"Use Mask?"},model:{value:t.useMask,callback:function(e){t.useMask=e},expression:"useMask"}})],1)]),s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("q-input",{attrs:{filled:"",dense:"",label:"Mask Name"},model:{value:t.maskName,callback:function(e){t.maskName=e},expression:"maskName"}})],1)])]),s("q-card-section",[s("q-btn",{attrs:{flat:"",color:"primary","no-caps":"",label:"Ok"},on:{click:t.closeMaskDialog}})],1)],1)],1)],1)},l=[],o=(s("a434"),s("e6cf"),s("a79d"),s("ddb0"),s("b76a")),i=s.n(o),n=s("a89c"),r=s("b6c6"),d={name:"QuotationsCreate",components:{PageToolbar:n["a"],Breadcrumbs:r["a"],draggable:i.a},data(){return{maskDialog:!1,maskEditIndex:null,model:{bank:null,rate_includes_gst:!0,serial_no:null,customer:null,warehouse:null,pricelist:null,items:[],subtotal:0,total:0,round:0,discount:0,freight:0,flood_cess_included:0,representative:null,created_by:null,remarks:"",instructions:"",terms:"",status:"Draft",inco_term:"FOB",fob_point:null,po_number:null,contact_person:null,contact_phone:null,contact_email:null,ship_date:null,ship_via:null,billingAddress:null,type:"Standard",prev_balance:0,bank_charges:0},incoTerms:[{label:"Free On Board(FOB)",value:"FOB"},{label:"Free Alongside Ship (FAS)",value:"FOB"},{label:"Cost and Freight (CFR)",value:"CFR"},{label:"Cost, Insurance and Freight (CIF)",value:"CIF"},{label:"Free On Board(FOB)",value:"FOB"}],weight:0,bankAccounts:[],warehouses:[],pricelists:[],representativeOptions:[],search:null,table_loading:!1,product:null,useMask:!1,maskName:null,gst:null,qty:null,rate:null,row_taxable:null,row_tax_amount:null,row_total:null,prodLoading:!1,productOptions:[],gstOptions:[],createdByOptions:[],stock:null,valid_until:null,stockOptions:[],cost:null,min_margin:0,templateDialog:!1,quotation_templates:[],customerOptions:[]}},computed:{totalWeight(){return this.$_.sumBy(this.model.items,(t=>t.product?parseInt(t.qty)*parseFloat(t.product.weight):0))},toolbarButtons(){const t=[];return this.$store.getters.hasPermissionTo("create_quotation")&&!this.$route.params.id&&t.push({label:"Load Template",id:"load",type:"button",color:"blue-10",icon:"publish"}),this.$store.getters.hasPermissionTo("create_quotation")&&t.push({label:"Save as Draft",id:"save",type:"button",color:"teal",icon:"save"}),t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Quotations",link:"/quotations"},{label:this.$route.params.id?this.model.serial_no:"Create",link:"/quotations/view/"+this.$route.params.id}];return this.$route.params.id&&t.push({label:"Edit",link:""}),t},columns(){const t=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:"prouduct_name",label:"Product",sortable:!0,align:"left"},{name:"gst",field:"gst",label:"GST",sortable:!0},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"rate",field:"rate",label:"Rate",sortable:!0}];return this.rate_includes_gst||t.push({name:"taxable",field:"taxable",label:"Taxable",sortable:!0},{name:"tax_amount",field:"tax_amount",label:"Tax Amount",sortable:!0}),t.push({name:"total",field:"total",label:"Total",sortable:!0},{name:"actions",field:"actions",label:"",sortable:!1}),t},subtotal(){return this.model.subtotal},freight(){return this.model.freight},discount(){return this.model.discount},rate_includes_gst(){return this.model.rate_includes_gst}},mounted(){if(this.$store.getters.hasPermissionTo("create_quotation")){this.$q.loading.show();const t=["create_quotation","update_quotation"];Promise.all([this.$axios.get("warehouses").then((t=>{this.warehouses=t.data.model,1===this.warehouses.length&&(this.model.warehouse=this.warehouses[0])})),this.$axios.get("pricelists").then((t=>{this.pricelists=t.data.model,1===this.pricelists.length&&(this.model.pricelist=this.pricelists[0])})),this.$axios.get("gst_options").then((t=>{this.gstOptions=t.data})),this.$axios.get("quotation_templates").then((t=>{this.quotation_templates=t.data.model})),this.$axios.get("users/has_permission?permissions="+encodeURIComponent(JSON.stringify(t))).then((t=>{this.createdByOptions=t.data,this.model.created_by=this.$store.getters.user})),this.$axios.get("config/back_accounts").then((t=>{this.bankAccounts=t.data.value}))]).finally((()=>{this.$q.loading.hide()})),this.$route.params.id&&(this.$q.loading.show(),this.$axios.get("quotations/"+this.$route.params.id).then((t=>{this.model=t.data,this.model.remarks=t.data.remarks?t.data.remarks:"",this.model.instructions=t.data.instructions?t.data.instructions:"",this.model.terms=t.data.terms?t.data.terms:""})).finally((()=>{this.loadAddress(),this.$q.loading.hide()}))),this.$route.params.customer_id&&this.$axios.get("customers/"+this.$route.params.customer_id).then((t=>{this.model.customer={name:t.data.name,id:t.data.id},"OTC"!==t.data.name&&this.loadAddress()}))}else this.$router.push({name:"Error403"})},methods:{addRowValidation(){this.$refs.rowForm.validate().then((t=>{"object"===typeof this.product&&-1!==this.$_.findIndex(this.model.items,(t=>t.product_id===this.product.id))?this.$q.dialog({title:"Duplicate Entry !!",message:"This product is already entered. Would you like to continue?",cancel:!0,persistent:!0,ok:{label:"Continue"}}).onOk((()=>{this.addRow()})):this.addRow()}))},addRow(){const t=parseInt(this.qty),e=this.rate?parseFloat(this.rate):0,s=this.gst?parseFloat(this.gst):0;let a=0,l=0,o=0;this.model.rate_includes_gst?o=e*t:(a=e*t,l=a*(s/100),o=a+l),this.model.items.push({name:this.product.name,product_name:"object"===typeof this.product?this.product.name:this.product,product_id:"object"===typeof this.product?this.product.id:0,use_mask:this.useMask,mask_name:this.maskName,gst:this.gst,product:{weight:this.weight},qty:t,rate:this.rate,taxable:a.toFixed(2),tax_amount:l.toFixed(2),total:o.toFixed(2),stock:this.stock,cost:this.cost,min_margin:this.min_margin}),this.addtoSubtotal(o),this.product=null,this.gst=null,this.qty=null,this.rate=null,this.weight=0,this.useMask=!1,this.maskName=null,this.maskEditIndex=null,this.row_discount=null,this.row_taxable=null,this.row_tax_amount=null,this.row_total=null,this.stock=null,this.$nextTick((()=>{this.$refs.product.focus()}))},addtoSubtotal(t){this.model.subtotal=(parseFloat(this.model.subtotal)+parseFloat(t)).toFixed(2)},deleteFn(t){this.$q.dialog({title:"Confirm",message:"Would you like to delete this record?",cancel:!0,persistent:!0}).onOk((()=>{const e=parseFloat(this.model.items[t].total);this.model.subtotal=parseFloat(this.model.subtotal)-e,this.model.items.splice(t,1)})).onCancel((()=>{}))},updateRow(t){const e=parseFloat(this.model.items[t].total),s=parseInt(this.model.items[t].qty),a=parseFloat(this.model.items[t].rate),l=parseFloat(this.model.items[t].gst);let o=0;if(this.model.rate_includes_gst)o=a*s,this.model.items[t].total=o.toFixed(2);else{let e=0;e=a*s,this.model.items[t].taxable=e.toFixed(2);let i=0;i=e*(l/100),this.model.items[t].tax_amount=i.toFixed(2),o=e+i,this.model.items[t].total=o.toFixed(2)}const i=o-e;this.addtoSubtotal(i)},updateTotal(){const t=parseFloat(this.model.subtotal),e=parseFloat(this.model.discount),s=parseFloat(this.model.freight),a=parseFloat(this.model.bank_charges),l=parseFloat(this.model.prev_balance);let o=0;o=t-e+s+a+l,this.model.round=(Math.round(o)-o).toFixed(2),this.model.total=Math.round(o).toFixed(2)},customerFilterFn(t,e,s){t?this.$axios.get("customers_search?include_leads=1&billable=1&search="+encodeURIComponent(t)).then((t=>{e((()=>{this.customerOptions=t.data}))})):s()},qtyEditValidation(t){return t?isNaN(t)?"invalid":!!Number.isInteger(Number(t))||"Must be Integer":"Required"},rateEditValidation(t){return null===t||void 0===t||""===t?"Required":isNaN(t)?"Invalid":!(parseFloat(t)<0)||"Invalid"},rateValidation(t){return null===t||void 0===t||""===t?"Required":isNaN(t)?"Invalid":!(parseFloat(t)<0)||"Invalid"},updateFields(){const t=this.qty?parseInt(this.qty):0,e=this.rate?parseFloat(this.rate):0,s=this.gst?parseFloat(this.gst):0;if(this.model.rate_includes_gst)this.row_total=(e*t).toFixed(2);else{let a=0;a=e*t,this.row_taxable=a.toFixed(2);let l=0;l=a*(s/100),this.row_tax_amount=l.toFixed(2);const o=a+l;this.row_total=o.toFixed(2)}},productFilter(t,e,s){t?(this.prodLoading=!0,this.$axios.get("products/search?inclall=1&keyword="+encodeURIComponent(t)).then((t=>{e((()=>{this.productOptions=t.data}))})).finally((()=>{this.prodLoading=!1}))):s()},saveFn(t="Draft"){if(this.$refs.customer.validate()&this.$refs.representative.validate()&this.$refs.pricelist.validate()&this.$refs.warehouse.validate()&this.$refs.created_by.validate()&this.$refs.valid_until.validate()&this.$refs.contact_phone.validate()&this.$refs.contact_email.validate()&this.$refs.bank.validate()&this.validateItemsCount()){const t=this.model;t._method=this.$route.params.id?"PUT":"POST";let e="quotations";this.$route.params.id&&(e="quotations/"+this.$route.params.id),this.$q.loading.show(),this.$axios.post(e,t).then((t=>{"success"===t.data.message&&(this.$q.loading.hide(),this.$q.notify({type:"positive",message:"Saved Succesfully."}),this.$router.back())})).catch((t=>{this.$q.loading.hide(),422===t.response.status&&(this.$q.notify({type:"negative",message:t.response.data.message}),t.response.data.errors.forEach((t=>{this.$q.notify({type:"negative",message:t})})))})).finally((()=>{this.$q.loading.hide()}))}else this.$q.notify({type:"negative",message:"Invalid data given !!!"})},validateItemsCount(){return this.model.items.length>0||(this.$q.notify({type:"negative",message:"There should be atleast one item"}),!1)},capitaliseFn(t){if(t){const e=t.split(" ");for(let t=0;t<e.length;t++)e[t]=e[t].charAt(0).toUpperCase()+e[t].substring(1);return e.join(" ")}return""},loadTemplate(t){const e=this.quotation_templates.findIndex((e=>e.id===t));this.$q.dialog({title:"Caution",message:"Do you really want to load '"+this.quotation_templates[e].name+"' template ?. The data you entered will be replaced with the content of template. Click OK to continue",cancel:!0}).onOk((()=>{this.$q.loading.show(),this.$axios.get("quotation_templates/"+t).then((t=>{this.model=t.data,this.remarks=t.data.remarks?t.data.remarks:"",this.instructions=t.data.instructions?t.data.instructions:"",this.terms=t.data.terms?t.data.terms:""})).finally((()=>{this.loadAddress(),this.templateDialog=!1,this.$q.loading.hide()}))}))},loadAddress(){this.model.customer&&"OTC"!==this.model.customer.name&&(this.$q.loading.show(),this.model.billingAddress=null,this.$axios.get("customer_addresses/"+this.model.customer.id).then((t=>{1===t.data.representatives.length&&(this.model.representative=t.data.representatives[0]),t.data.addresses.forEach((t=>{const e=this.addressFormat(t);"billing"===t.tag_name&&(this.model.billingAddress=e)}))})).finally((()=>{this.$q.loading.hide()})))},addressFormat(t){let e="";return e+=t.line_1+", ",t.line_2&&(e+=t.line_2+", "),t.district&&(e+=t.district+", "),e+=t.state+", ",t.pin&&(e+="PIN: "+t.pin+", "),e+=t.country+", ",e+="Ph: ",t.phones.forEach((t=>{e+="("+t.country_code+")"+t.content+", "})),e=e.substr(0,e.length-2),e},showMaskDetails(t,e){this.maskName=t.mask_name,this.useMask=t.use_mask,this.maskEditIndex=e,this.maskDialog=!0},closeMaskDialog(){this.model.items[this.maskEditIndex].use_mask=this.useMask,this.model.items[this.maskEditIndex].mask_name=this.maskName,this.useMask=!1,this.maskName=null,this.maskEditIndex=null,this.maskDialog=!1},onTypeChange(t){"Export"===t&&(this.model.rate_includes_gst=!0)}},watch:{product(t,e){t&&"object"===typeof t?this.$axios.get("products/basic/"+t.id+"/"+this.model.warehouse.id).then((t=>{this.gst=t.data.gst,this.expirable=t.data.expirable,this.weight=t.data.weight,this.hsn=t.data.hsn,this.mrp=t.data.mrp,this.stock=t.data.available_stock,this.stockOptions=t.data.stock_options,this.cost=t.data.cost?t.data.cost:0,this.min_margin=t.data.min_margin?t.data.min_margin:0,this.maskName=t.data.mask_name})):(this.gst=null,this.stock=null)},qty(t,e){this.updateFields()},rate(t,e){this.updateFields()},row_discount(t,e){this.updateFields()},gst(t,e){this.updateFields()},subtotal(){this.updateTotal()},discount(){this.updateTotal()},freight(){this.updateTotal()},rate_includes_gst(t,e){if(t){this.$q.loading.show();let t=0;this.model.items.forEach(((e,s)=>{let a=0;const l=parseFloat(e.rate),o=parseInt(e.qty);a=o*l,t+=parseFloat(a.toFixed(2)),this.model.items[s].total=a.toFixed(2)})),this.subtotal=t,this.$q.loading.hide()}else{this.$q.loading.show();let t=0;this.model.items.forEach(((e,s)=>{let a=0,l=0,o=0;const i=parseFloat(e.rate),n=parseInt(e.qty),r=parseFloat(e.gst);l=i*n,o=l*(r/100),a=l+o,this.model.items[s].taxable=l.toFixed(2),this.model.items[s].tax_amount=o.toFixed(2),this.model.items[s].total=a.toFixed(2),t+=parseFloat(a)})),this.model.subtotal=t.toFixed(2),this.$q.loading.hide()}}}},c=d,u=s("2877"),m=s("9989"),p=s("f09f"),h=s("a370"),b=s("ddd8"),f=s("27f9"),_=s("66e5"),v=s("4074"),g=s("0016"),q=s("7cbe"),k=s("52ee"),x=s("9c40"),y=s("8f8e"),C=s("0170"),$=s("2bb1"),w=s("58a81"),F=s("05c0"),I=s("42a1"),N=s("0378"),T=s("d66b"),S=s("9404"),O=s("4983"),R=s("eaac"),E=s("bd08"),D=s("db86"),Q=s("24e8"),P=s("7f67"),M=s("eebe"),A=s.n(M),B=Object(u["a"])(c,a,l,!1,null,null,null);e["default"]=B.exports;A()(B,"components",{QPage:m["a"],QCard:p["a"],QCardSection:h["a"],QSelect:b["a"],QInput:f["a"],QItem:_["a"],QItemSection:v["a"],QIcon:g["a"],QPopupProxy:q["a"],QDate:k["a"],QBtn:x["a"],QCheckbox:y["a"],QItemLabel:C["a"],QMarkupTable:$["a"],QBadge:w["a"],QTooltip:F["a"],QPopupEdit:I["a"],QForm:N["a"],QEditor:T["a"],QDrawer:S["a"],QScrollArea:O["a"],QTable:R["a"],QTr:E["a"],QTd:D["a"],QDialog:Q["a"]}),A()(B,"directives",{ClosePopup:P["a"]})}}]);