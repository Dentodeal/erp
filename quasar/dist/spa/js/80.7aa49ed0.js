(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[80],{b6a7:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{createso:function(e){return t.createSaleOrder()},export:t.exportFn}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("q-tabs",{staticClass:"text-grey",attrs:{dense:"","active-color":"primary","indicator-color":"primary",align:"justify","narrow-indicator":""},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[a("q-tab",{attrs:{name:"details",label:"Details"}}),a("q-tab",{attrs:{name:"payments",label:"Payments"}}),a("q-tab",{attrs:{name:"documents",label:"Documents"}})],1),a("q-separator"),a("q-tab-panels",{attrs:{animated:""},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[a("q-tab-panel",{attrs:{name:"details"}},[a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Serial No")]),a("td",[t._v(t._s(t.model.serial_no))])]),a("tr",[a("td",[t._v("Customer/Lead")]),a("td",[a("router-link",{attrs:{to:t.getCustomerLink}},[t._v(t._s(this.model.customer.name))]),t.billingAddress?[a("br"),t._v(t._s(t.billingAddress)+"\n                      ")]:t._e()],2)]),a("tr",[a("td",[t._v("Warehouse")]),a("td",[t._v(t._s(t.model.warehouse.name))])]),a("tr",[a("td",[t._v("Pricelist")]),a("td",[t._v(t._s(t.model.pricelist.name))])]),a("tr",[a("td",[t._v("Inco Term")]),a("td",[t._v(t._s(t.model.inco_term))])]),a("tr",[a("td",[t._v("Shipping Point")]),a("td",[t._v(t._s(t.model.fob_point))])]),a("tr",[a("td",[t._v("PO Number")]),a("td",[t._v(t._s(t.model.po_number))])]),a("tr",[a("td",[t._v("Contact Person")]),a("td",[t._v(t._s(t.model.contact_person||t.model.representative.name))])]),a("tr",[a("td",[t._v("Contact Phone")]),a("td",[t._v(t._s(t.model.contact_phone))])]),a("tr",[a("td",[t._v("Bank")]),a("td",[t.model.bank?a("q-item",[a("q-item-section",[a("q-item-label",[t._v(t._s(t.model.bank.name))]),a("q-item-label",{attrs:{caption:""}},[t._v("Account Name: "+t._s(t.model.bank.acc_name))]),a("q-item-label",{attrs:{caption:""}},[t._v("Bank: "+t._s(t.model.bank.bank_name))]),a("q-item-label",{attrs:{caption:""}},[t._v("Acc No. "+t._s(t.model.bank.acc_no))]),a("q-item-label",{attrs:{caption:""}},[t._v("IFSC: "+t._s(t.model.bank.ifsc))]),a("q-item-label",{attrs:{caption:""}},[t._v("UPI: "+t._s(t.model.bank.upi))])],1)],1):t._e()],1)])])])],1),a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Status")]),a("td",[t._v(t._s(t.model.status))])]),a("tr",[a("td",[t._v("Created By")]),a("td",[t._v(t._s(t.model.created_by.name))])]),a("tr",[a("td",[t._v("Representative")]),a("td",[t._v(t._s(t.model.representative.name))])]),a("tr",[a("td",[t._v("Created At")]),a("td",[t._v(t._s(t.getLocaleString(t.model.created_at)))])]),a("tr",[a("td",[t._v("Valid Until")]),a("td",[t._v(t._s(t.getLocaleString(t.model.valid_until)))])]),a("tr",[a("td",[t._v("Ship Date")]),a("td",[t._v(t._s(t.getLocaleString(t.model.ship_date)))])]),a("tr",[a("td",[t._v("Ship Via")]),a("td",[t._v(t._s(t.model.ship_via))])]),a("tr",[a("td",[t._v("Contact Email")]),a("td",[t._v(t._s(t.model.contact_email))])]),t.model.sale_orders?t._l(t.model.sale_orders,(function(e,s){return a("tr",{key:s},[0===s?a("td",{attrs:{rowspan:t.model.sale_orders.length}},[t._v("Sale Orders")]):t._e(),a("td",{style:0!==s?{borderLeft:"1px solid rgba(0, 0, 0, 0.12)"}:""},[a("router-link",{attrs:{to:"/sale_orders/view/"+e.id}},[t._v(t._s(e.serial_no)+"\n                            "),a("q-badge",{staticClass:"q-ml-md",attrs:{align:"top",outline:"",color:"primary",label:e.status}})],1)],1)])})):t._e(),a("tr",[a("td",[t._v("Type")]),a("td",[t._v(t._s(t.model.type))])])],2)])],1)]),a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Rate Includes GST: "+t._s(t.model.rate_includes_gst?"Yes":"No"))]),a("td",[t._v("Include Flood Cess ?: "+t._s("Yes"==t.model.flood_cess_included?"Yes":"No"))])])])])],1)]),a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-table",{staticClass:"my-sticky-header-table",attrs:{columns:t.columns,title:"Items",data:t.model.items,"row-key":"sl_no","wrap-cells":"",loading:t.table_loading,"rows-per-page-options":[0],filter:t.search},scopedSlots:t._u([{key:"header",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-th",{attrs:{"auto-width":""}}),t._l(e.cols,(function(s){return a("q-th",{key:s.name,attrs:{props:e}},[t._v("\n                      "+t._s(s.label)+"\n                    ")])}))],2)]}},{key:"body",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-td",{attrs:{"auto-width":""}},[a("q-btn",{attrs:{round:"",dense:"",flat:"",icon:e.expand?"arrow_drop_down":"arrow_right"},on:{click:function(t){e.expand=!e.expand}}})],1),a("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),a("q-td",[t._v("\n                      "+t._s(e.row.product_id?e.row.product.name:e.row.product_name)+"\n                      "),e.row.product_id?t._e():a("q-badge",{attrs:{align:"top",outline:"",color:"primary",label:"New"}}),e.row.converted_qty==e.row.qty?a("q-badge",{attrs:{align:"top",outline:"",color:"green-7",label:"Converted"}}):t._e(),e.row.converted_qty>0&&e.row.converted_qty!=e.row.qty?a("q-badge",{attrs:{align:"top",outline:"",color:"orange-7",label:"Partially Converted"}}):t._e()],1),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.product_id?e.row.qty*e.row.product.weight:0)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[e.row.gst?a("div",[t._v("\n                        "+t._s(e.row.gst)+"%\n                      ")]):t._e()]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.qty)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(t.currencySymbol)+t._s(e.row.rate)+"\n                    ")]),t.model.rate_includes_gst?t._e():a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(t.currencySymbol)+t._s(e.row.taxable)+"\n                    ")]),t.model.rate_includes_gst?t._e():a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(t.currencySymbol)+t._s(e.row.tax_amount)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(t.currencySymbol)+t._s(e.row.total)+"\n                    ")])],1),a("q-tr",{directives:[{name:"show",rawName:"v-show",value:e.expand,expression:"props.expand"}],attrs:{props:e}},[a("q-td",{attrs:{colspan:"100%"}},[a("div",{staticClass:"text-left"},[t._v("Use Mask: "+t._s(e.row.use_mask?"Yes":"No")+", Mask Name: "+t._s(e.row.mask_name))])])],1)]}},{key:"top-right",fn:function(){return[a("q-input",{attrs:{borderless:"",dense:"",debounce:"300",placeholder:"Search",clearable:""},scopedSlots:t._u([{key:"append",fn:function(){return[a("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})]},proxy:!0},{key:"bottom-row",fn:function(){return[a("q-tr",{staticClass:"bg-blue-grey-3"},[a("td",{attrs:{colspan:"2"}}),a("td",{staticClass:"text-right"},[t._v("Total Weight:")]),a("td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.totalWeight).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"gm")]),a("td",{staticClass:"text-right"},[t._v("Total Qty:")]),a("td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.totalQty)))]),t.model.rate_includes_gst?t._e():a("td",{attrs:{colspan:"2"}}),a("td",{staticClass:"text-right"},[t._v("Subtotal")]),a("td",{staticClass:"text-right"},[t._v(t._s(t.currencySymbol)+t._s(parseFloat(t.model.subtotal).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])]),a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Discount")]),a("q-td",{staticClass:"text-right"},[t._v("-"+t._s(t.currencySymbol)+t._s(parseFloat(t.model.discount).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Freight")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(t.currencySymbol)+t._s(parseFloat(t.model.freight).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),"Export"===t.model.type?a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Bank Charges")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(t.currencySymbol)+t._s(parseFloat(t.model.bank_charges).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1):t._e(),"Export"===t.model.type?a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Previous Balance")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(t.currencySymbol)+t._s(parseFloat(t.model.prev_balance).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1):t._e(),a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Round")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(t.currencySymbol)+t._s(parseFloat(t.model.round).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),a("q-tr",{staticClass:"bg-blue-grey-3"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Total")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(t.currencySymbol)+t._s(parseFloat(t.model.total).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1)]},proxy:!0}])})],1)]),t.model.instructions?a("div",{staticClass:"row"},[a("div",{staticClass:"col q-px-md"},[a("div",{staticClass:"text-h6"},[t._v("Instructions")]),a("div",{domProps:{innerHTML:t._s(t.model.instructions)}})])]):t._e(),t.model.terms?a("div",{staticClass:"row"},[a("div",{staticClass:"col q-px-md"},[a("div",{staticClass:"text-h6"},[t._v("Terms")]),a("div",{domProps:{innerHTML:t._s(t.model.terms)}})])]):t._e(),t.model.remarks?a("div",{staticClass:"row"},[a("div",{staticClass:"col q-px-md"},[a("div",{staticClass:"text-h6"},[t._v("Remarks")]),a("div",{domProps:{innerHTML:t._s(t.model.remarks)}})])]):t._e()]),a("q-tab-panel",{attrs:{name:"payments"}},[a("q-card",[a("q-markup-table",[a("thead",[a("tr",[a("th",{staticClass:"text-left"},[t._v("#")]),a("th",{staticClass:"text-left"},[t._v("Payment Via")]),a("th",{staticClass:"text-left"},[t._v("Transaction ID")]),a("th",{staticClass:"text-right"},[t._v("Amount")]),a("th",{staticClass:"text-right"},[t._v("Created At")]),a("th",{staticClass:"text-right"})])]),a("tbody",[t.model.payments&&t.model.payments.length>0?t._l(t.model.payments,(function(e,s){return a("tr",{key:s},[a("td",{staticClass:"text-left"},[t._v(t._s(s+1))]),a("td",{staticClass:"text-left"},[t._v(t._s(e.payment_via))]),a("td",{staticClass:"text-left"},[t._v(t._s(e.transaction_id))]),a("td",{staticClass:"text-right"},[t._v(t._s(e.amount))]),a("td",{staticClass:"text-right"},[t._v(t._s(t.getLocaleString(new Date(1e3*e.id))))]),a("td",{staticClass:"text-right"},[a("q-btn",{attrs:{flat:"",round:"",icon:"delete"},on:{click:function(e){return t.deletePayment(s)}}})],1)])})):a("tr",[a("td",{staticClass:"text-center text-caption",attrs:{colspan:"100%"}},[t._v("No Data Available")])])],2)]),a("q-separator"),a("q-card-section",[a("q-btn",{attrs:{color:"primary",label:"Add Payment"},on:{click:t.openPaymentDialog}})],1)],1)],1),a("q-tab-panel",{attrs:{name:"documents"}},[a("q-card",[a("q-markup-table",[a("thead",[a("tr",[a("th",{staticClass:"text-left"},[t._v("#")]),a("th",{staticClass:"text-left"},[t._v("Document")]),a("th",{staticClass:"text-right"})])]),a("tbody",[t.model.documents&&t.model.documents.length>0?t._l(t.model.documents,(function(e,s){return a("tr",{key:s},[a("td",{staticClass:"text-left"},[t._v(t._s(s+1))]),a("td",{staticClass:"text-left"},[a("a",{attrs:{href:t.$store.getters.baseURL+"/storage/"+e.link,target:"_blank"}},[t._v(t._s(e.name))])]),a("td",{staticClass:"text-right"},[a("q-btn",{attrs:{flat:"",round:"",icon:"delete"},on:{click:function(e){return t.deleteDocument(s)}}})],1)])})):a("tr",[a("td",{staticClass:"text-center text-caption",attrs:{colspan:"100%"}},[t._v("No Data Available")])])],2)]),a("q-card-section",[a("q-form",{ref:"docForm"},[a("div",{staticClass:"row q-col-gutter-md"},[a("div",{staticClass:"col-6"},[a("q-input",{attrs:{filled:"",dense:"",rules:[function(t){return!!t||"Required"}],label:"Document Name"},model:{value:t.doc_name,callback:function(e){t.doc_name=e},expression:"doc_name"}})],1),a("div",{staticClass:"col-4"},[a("q-file",{ref:"docfile",attrs:{outlined:"",filled:"",dense:"",rules:[function(t){return!!t||"Required"}]},scopedSlots:t._u([{key:"prepend",fn:function(){return[a("q-icon",{attrs:{name:"attach_file"}})]},proxy:!0}]),model:{value:t.doc_file,callback:function(e){t.doc_file=e},expression:"doc_file"}})],1),a("div",{staticClass:"col-2"},[a("q-btn",{attrs:{color:"primary",label:"Upload"},on:{click:t.uploadDoc}})],1)])])],1)],1)],1)],1)],1)],1),a("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":"",buttons:t.toolbarButtons},on:{createso:function(e){return t.createSaleOrder()},export:t.exportFn}}),a("q-dialog",{attrs:{persistent:"",position:"top"},model:{value:t.exportDialog,callback:function(e){t.exportDialog=e},expression:"exportDialog"}},[a("q-card",{staticStyle:{width:"350px"}},[a("q-card-section",[a("div",{staticClass:"text-subtitle1"},[t._v("Select Export Mode")]),a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("q-radio",{attrs:{label:"pdf",val:"pdf"},model:{value:t.export_mode,callback:function(e){t.export_mode=e},expression:"export_mode"}}),a("q-radio",{attrs:{label:"excel(.xlsx)",val:"excel"},model:{value:t.export_mode,callback:function(e){t.export_mode=e},expression:"export_mode"}})],1)]),a("div",{staticClass:"row"},["Standard"==t.model.type?a("div",{staticClass:"col"},[a("q-checkbox",{attrs:{label:"Include GST Column ?"},model:{value:t.export_include_gst_column,callback:function(e){t.export_include_gst_column=e},expression:"export_include_gst_column"}})],1):t._e(),"Export"==t.model.type?a("div",{staticClass:"col"},[a("q-checkbox",{attrs:{label:"Use Mask Name ?"},model:{value:t.export_use_mask_name,callback:function(e){t.export_use_mask_name=e},expression:"export_use_mask_name"}}),a("q-checkbox",{attrs:{label:"Include HSN Column ?"},model:{value:t.export_include_hsn,callback:function(e){t.export_include_hsn=e},expression:"export_include_hsn"}}),a("q-checkbox",{attrs:{label:"Include Country of Origin ?"},model:{value:t.export_include_country_of_origin,callback:function(e){t.export_include_country_of_origin=e},expression:"export_include_country_of_origin"}})],1):t._e()])]),a("q-card-actions",[a("q-btn",{attrs:{label:"export",color:"primary",disable:!t.export_mode},on:{click:t.performExport}}),a("q-btn",{attrs:{label:"cancel",flat:"",color:"secondary"},on:{click:function(e){t.exportDialog=!1}}})],1)],1)],1),a("q-dialog",{attrs:{persistent:"",maximized:"","transition-show":"slide-up","transition-hide":"slide-down"},model:{value:t.convertDialog,callback:function(e){t.convertDialog=e},expression:"convertDialog"}},[a("convert-dialog",{attrs:{items:t.model.items}})],1),a("q-dialog",{attrs:{persistent:""},model:{value:t.paymentDialog,callback:function(e){t.paymentDialog=e},expression:"paymentDialog"}},[a("q-card",{staticStyle:{width:"500px"}},[a("q-card-section",[a("div",{staticClass:"text-h6"},[t._v("Add Payment Details")])]),a("q-separator"),a("q-card-section",[a("q-form",{ref:"paymentForm"},[a("div",{staticClass:"row q-col-gutter-md"},[a("div",{staticClass:"col-6 text-right q-mt-sm"},[t._v("Payment Via")]),a("div",{staticClass:"col-6"},[a("q-select",{ref:"payment_via",attrs:{options:["Cash","Bank","Cheque","UPI","Card"],dense:"",filled:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.paymentModel.payment_via,callback:function(e){t.$set(t.paymentModel,"payment_via",e)},expression:"paymentModel.payment_via"}})],1),a("div",{staticClass:"col-6 text-right q-mt-sm"},[t._v("Currency")]),a("div",{staticClass:"col-6"},[a("q-select",{ref:"currency",attrs:{options:t.currencyOptions,filled:"",dense:"","emit-value":"","map-options":"",rules:[function(t){return!!t||"Required"}]},model:{value:t.paymentModel.currency,callback:function(e){t.$set(t.paymentModel,"currency",e)},expression:"paymentModel.currency"}})],1),a("div",{staticClass:"col-6 text-right q-mt-sm"},[t._v("Transaction ID / Ref ID")]),a("div",{staticClass:"col-6"},[a("q-input",{attrs:{filled:"",dense:""},model:{value:t.paymentModel.transaction_id,callback:function(e){t.$set(t.paymentModel,"transaction_id",e)},expression:"paymentModel.transaction_id"}})],1),a("div",{staticClass:"col-6 text-right q-mt-sm"},[t._v("Amount")]),a("div",{staticClass:"col-6"},[a("q-input",{ref:"amount",staticClass:"input-right",attrs:{filled:"",dense:"",rules:[function(t){return!!t||"Required"}]},model:{value:t.paymentModel.amount,callback:function(e){t.$set(t.paymentModel,"amount",t._n(e))},expression:"paymentModel.amount"}})],1)])])],1),a("q-card-actions",[a("q-btn",{attrs:{label:"Submit",color:"primary"},on:{click:t.addPayment}}),a("q-btn",{attrs:{flat:"",label:"Cancel",color:"secondary"},on:{click:t.closePaymentDialog}})],1)],1)],1)],1)},o=[],l=(a("c975"),a("e6cf"),a("a79d"),a("5319"),a("ddb0"),a("2b3d"),a("a89c")),r=a("b6c6"),n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-card",{staticClass:"bg-primary"},[a("q-bar",[a("q-space"),a("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{dense:"",flat:"",icon:"close"}},[a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[t._v("Close")])],1)],1),a("q-card-section",[a("div",{staticClass:"text-h6 text-white"},[t._v("Partial Conversion")])]),a("q-card-section",{staticClass:"q-pt-none"},[a("q-stepper",{ref:"stepper",attrs:{color:"primary",animated:""},model:{value:t.step,callback:function(e){t.step=e},expression:"step"}},[a("q-step",{attrs:{name:1,title:"Selection",icon:"check"}},[a("q-table",{attrs:{columns:[{name:"ind",field:"ind",label:"#",align:"right"},{name:"product",field:"product",label:"Product",align:"left",sortable:!0},{name:"expiry_date",field:"expiry_date",label:"Expiry Date"}],data:t.datawithIndex,title:"Please select products to process",selection:"multiple",selected:t.selectedForConversion,"row-key":"index","rows-per-page-options":[0]},on:{selection:t.selectedFn,"update:selected":function(e){t.selectedForConversion=e}},scopedSlots:t._u([{key:"body-selection",fn:function(e){return[a("q-checkbox",{attrs:{disable:e.row.converted_qty===e.row.qty||!e.row.product_id},model:{value:e.selected,callback:function(a){t.$set(e,"selected",a)},expression:"scope.selected"}})]}},{key:"body-cell-ind",fn:function(e){return[a("q-td",{attrs:{props:e}},[t._v("\n              "+t._s(e.rowIndex+1)+"\n            ")])]}},{key:"body-cell-product",fn:function(e){return[a("q-td",{attrs:{props:e}},[t._v("\n              "+t._s(e.row.product_name)+"\n              "),e.row.product_id?t._e():a("q-badge",{attrs:{align:"top",outline:"",color:"primary",label:"New"}}),e.row.converted_qty==e.row.qty?a("q-badge",{attrs:{align:"top",outline:"",color:"green-7",label:"Converted"}}):t._e(),e.row.converted_qty>0&&e.row.converted_qty!=e.row.qty?a("q-badge",{attrs:{align:"top",outline:"",color:"orange-7",label:"Partially Converted"}}):t._e()],1)]}}])}),a("q-stepper-navigation",[a("q-btn",{attrs:{color:"primary",label:"Continue",disable:0==t.selectedForConversion.length},on:{click:function(e){t.step=2,t.sanitizeSelection()}}})],1)],1),a("q-step",{attrs:{name:2,title:"Details",icon:"save"}},[a("q-table",{attrs:{columns:t.columns,title:"Selected Items",data:t.selectedForConversion,"row-key":"index","wrap-cells":"","rows-per-page-options":[0]},scopedSlots:t._u([{key:"body",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),a("q-td",[t._v("\n                "+t._s(e.row.product_name)+"\n              ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(e.row.qty)+"\n                "),a("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close",validate:function(a){return!0===t.qtyEditValidation(a,t.selectedForConversion[e.rowIndex].qty)}},on:{hide:function(t){e.row.qty=parseInt(e.row.qty)}},model:{value:e.row.qty,callback:function(a){t.$set(e.row,"qty",a)},expression:"props.row.qty"}},[a("q-input",{ref:"qty_edit",attrs:{rules:[function(a){return t.qtyEditValidation(a,e.row.qty)}],dense:"",autofocus:""},on:{input:function(e){return t.$refs.qty_edit.resetValidation()}},model:{value:e.row.qty,callback:function(a){t.$set(e.row,"qty",a)},expression:"props.row.qty"}})],1)],1),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(e.row.expiry_date)+"\n              ")])],1)]}}])}),a("q-stepper-navigation",[a("q-btn",{attrs:{color:"primary",label:"back"},on:{click:function(e){t.step=1}}}),a("q-btn",{staticClass:"q-ml-md",attrs:{color:"green",label:"Submit"},on:{click:function(e){return t.submitQ()}}})],1)],1)],1)],1)],1)},i=[],d=a("ded3"),c=a.n(d),m={name:"ConvertDialog",props:["items"],data(){return{step:1,selectedForConversion:[],errorQty:!1,errorMessageQty:"",columns:[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:"name",label:"Product",sortable:!0,align:"left"},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"expiry_date",field:"expiry_date",label:"Exp Date",sortable:!0}]}},computed:{datawithIndex(){return this.items.map(((t,e)=>c()(c()({},t),{},{index:e})))}},methods:{selectedFn({rows:t,added:e,keys:a}){const s=t[0];e?parseInt(s.converted_qty)>0&&parseInt(s.converted_qty)!==parseInt(s.qty)&&(t[0].qty=parseInt(s.qty)-parseInt(s.converted_qty)):parseInt(s.converted_qty)>0&&parseInt(s.converted_qty)!==parseInt(s.qty)&&(t[0].qty=parseInt(s.qty)+parseInt(s.converted_qty))},qtyEditValidation(t,e){return t?!!Number.isInteger(Number(t))||"Qty must be an integer":"Required"},sanitizeSelection(){this.selectedForConversion=this.selectedForConversion.filter((t=>parseInt(t.converted_qty)!==parseInt(t.qty)&&0!==parseInt(t.product_id)))},submitQ(){this.$q.loading.show(),this.$axios.post("quotations/partial_convert/"+this.$route.params.id,{items:this.selectedForConversion}).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"success",message:"Quotation converted successfully"}),this.$router.push({path:"/sale_orders/view/"+t.data.id}))})).catch((t=>{this.$q.notify({type:"negative",message:t.response.data.message});let e="";Object.keys(t.response.data.errors).forEach((a=>{-1!==a.indexOf("items")&&(e+="<p>Line "+(parseInt(a.substr(a.indexOf(".")+1))+1)+":"+t.response.data.errors[a][0]+"</p><br>")})),this.$q.dialog({title:"Error in data submitted",message:e,html:!0})})).finally((()=>{this.$q.loading.hide()}))}}},u=m,p=a("2877"),_=a("f09f"),h=a("d847"),b=a("2c91"),g=a("9c40"),v=a("05c0"),y=a("a370"),q=a("f531"),x=a("87fe"),f=a("eaac"),C=a("8f8e"),w=a("db86"),k=a("58a81"),D=a("b19c"),S=a("bd08"),$=a("42a1"),Q=a("27f9"),F=a("7f67"),I=a("eebe"),P=a.n(I),T=Object(p["a"])(u,n,i,!1,null,null,null),L=T.exports;P()(T,"components",{QCard:_["a"],QBar:h["a"],QSpace:b["a"],QBtn:g["a"],QTooltip:v["a"],QCardSection:y["a"],QStepper:q["a"],QStep:x["a"],QTable:f["a"],QCheckbox:C["a"],QTd:w["a"],QBadge:k["a"],QStepperNavigation:D["a"],QTr:S["a"],QPopupEdit:$["a"],QInput:Q["a"]}),P()(T,"directives",{ClosePopup:F["a"]});var N={name:"QuotationsView",components:{PageToolbar:l["a"],Breadcrumbs:r["a"],ConvertDialog:L},data(){return{doc_name:null,doc_file:null,paymentDialog:!1,currencyOptions:[{label:"US Dollar",symbol:"$",value:"USD"},{label:"Indian Rupee",symbol:"₹",value:"INR"}],paymentModel:{payment_via:null,currency:"INR",transaction_id:null,amount:0},tab:"details",model:{inco_term:null,bank:null,serial_no:"",customer:{name:null},representative:{name:null},created_by:{name:null},created_at:null,status:null,remarks:"",instructions:"",terms:"",valid_until:null,fob_point:null,po_number:null,warehouse:{name:null},pricelist:{name:null},rate_includes_gst:null,flood_cess_included:null,items:[],subtotal:null,discount:null,freight:null,round:null,total:null,search:null,table_loading:!1,ship_date:null,ship_via:null,contact_person:null,contact_phone:null,contact_email:null,sale_orders:[],bank_charges:null,prev_balance:null,currency:"INR",payments:[],documents:[]},exportDialog:!1,export_mode:null,export_include_gst_column:!0,export_use_mask_name:!1,export_include_hsn:!1,export_include_country_of_origin:!1,convertDialog:!1,billingAddress:null,search:null,table_loading:!1}},mounted(){this.$q.loading.show(),this.$axios.get("quotations/"+this.$route.params.id).then((t=>{this.model=t.data})).finally((()=>{this.loadAddress(),this.$q.loading.hide()}))},methods:{uploadDoc(){this.$refs.docForm.validate().then((t=>{if(t){this.$q.loading.show();const t=new FormData;t.append("file",this.doc_file),t.append("name",this.doc_name),this.$axios.post("quotations/"+this.$route.params.id+"/documents",t,{headers:{"Content-Type":"multipart/form-data"}}).then((t=>{"success"===t.data.message&&(this.model.documents=t.data.documents,this.doc_name=null,this.doc_file=null)})).finally((()=>this.$q.loading.hide()))}else this.$q.notify({message:"Error in data submitted. Please check",type:"negative"})}))},deleteDocument(t){this.$q.dialog({title:"Confirmation",message:"Do you want to continue?",persistent:!0,cancel:!0}).onOk((()=>{this.$q.loading.show(),this.$axios.get("quotations/"+this.$route.params.id+"/delete_document/"+this.model.documents[t].id).then((t=>{"success"===t.data.message&&(this.model.documents=t.data.documents)})).finally((()=>{this.$q.loading.hide()}))}))},deletePayment(t){this.$q.dialog({title:"Confirmation",message:"Do you want to continue?",persistent:!0,cancel:!0}).onOk((()=>{this.$q.loading.show(),this.$axios.get("quotations/"+this.$route.params.id+"/delete_payment/"+this.model.payments[t].id).then((t=>{"success"===t.data.message&&(this.model.payments=t.data.payments)})).finally((()=>{this.$q.loading.hide()}))}))},addPayment(){this.$refs.paymentForm.validate().then((t=>{t?(this.$q.loading.show(),this.$axios.post("quotations/"+this.$route.params.id+"/add_payment",this.paymentModel).then((t=>{"success"===t.data.message&&(this.model.payments=t.data.payments,this.closePaymentDialog())})).finally((()=>this.$q.loading.hide()))):this.$q.notify({message:"Error in data submitted. Please check",type:"negative"})}))},openPaymentDialog(){this.paymentDialog=!0},closePaymentDialog(){this.paymentModel={payment_via:null,currency:"INR",transaction_id:"null",amount:0},this.paymentDialog=!1},getLocaleString(t){if(t){const e=new Date(t);return e.toLocaleString("en-IN")}return""},exportFn(){this.exportDialog=!0},performExport(){this.$q.loading.show();let t="Quotation_"+this.model.customer.name.replace(/[^a-zA-Z0-9]/g,"_")+"_"+this.model.serial_no.replace(/\//g,"");"pdf"===this.export_mode&&(t+=".pdf"),"excel"===this.export_mode&&(t+=".xlsx"),this.$axios({url:"quotations/export/"+this.$route.params.id,method:"POST",responseType:"blob",data:{include_gst_column:this.export_include_gst_column,use_mask_name:this.export_use_mask_name,include_hsn_column:this.export_include_hsn,include_country_of_origin:this.export_include_country_of_origin,mode:this.export_mode}}).then((e=>{const a=window.URL.createObjectURL(new Blob([e.data])),s=document.createElement("a");s.href=a,s.setAttribute("download",t),document.body.appendChild(s),s.click()})).finally((()=>{this.$q.loading.hide(),this.exportDialog=!1}))},createSaleOrder(){if(this.model.customer.is_lead)this.$q.dialog({title:"Alert",message:"You cannot create sale order for a lead. Please convert it to customer!!",dark:!0});else if("Active"!==this.model.customer.status&&"Approved"!==this.model.customer.status)this.$q.dialog({title:"Attention",message:"The customer selected is not active or approved by CRO. Please contact CRO."});else{let t=!1;this.model.items.forEach((e=>{(e.converted_qty>0||!e.product_id)&&(t=!0)})),t?this.convertDialog=!0:this.$q.dialog({title:"Select conversion mode",options:{type:"radio",model:"conversion_mode",items:[{label:"Partial Conversion",value:"partial"},{label:"Full Conversion",value:"full"}]},cancel:!0,persistent:!0}).onOk((t=>{"partial"===t?this.convertDialog=!0:"full"===t&&this.convert()}))}},convert(){const t=[];this.model.items.forEach(((e,a)=>{0===e.product_id&&t.push(e)})),t.length>0?this.$q.dialog({title:"Alert",message:"There are new products in the items list. Please replace them with products in store!!",dark:!0}):(this.$q.loading.show(),this.$axios.get("quotations/convert/"+this.$route.params.id).then((t=>{"success"===t.data.message&&this.$router.push({path:"/sale_orders/view/"+t.data.id})})).catch((t=>{if(422===t.response.status){this.$q.notify({type:"negative",message:t.response.data.message});let e="";Object.keys(t.response.data.errors).forEach((a=>{-1!==a.indexOf("items")&&(e+="<p>Line "+(parseInt(a.substr(a.indexOf(".")+1))+1)+":"+t.response.data.errors[a][0]+"</p><br>")})),this.$q.dialog({style:{width:"50%",maxWidth:"100%"},title:"Cannot convert this Quotation",message:e,html:!0})}})).finally((()=>{this.$q.loading.hide()})))},loadAddress(){this.model.customer&&"OTC"!==this.model.customer.name&&(this.$q.loading.show(),this.billingAddress=null,this.$axios.get("customer_addresses/"+this.model.customer.id).then((t=>{1===t.data.representatives.length&&(this.model.representative_id=t.data.representatives[0].id),t.data.addresses.forEach((t=>{"billing"===t.tag_name&&(this.billingAddress=this.addressFormat(t))}))})).finally((()=>{this.$q.loading.hide()})))},addressFormat(t){let e="";return e+=t.line_1+", ",t.line_2&&(e+=t.line_2+", "),t.district&&(e+=t.district+", "),e+=t.state+", ",t.pin&&(e+="PIN: "+t.pin+", "),e+=t.country+", ",e+="Ph: ",t.phones.forEach((t=>{e+="("+t.country_code+")"+t.content+", "})),e=e.substr(0,e.length-2),e}},computed:{currencySymbol(){return"INR"===this.model.currency?"₹":"$"},totalWeight(){return this.$_.sumBy(this.model.items,(t=>parseFloat(t.product?t.product.weight*t.qty:0)))},totalQty(){return this.$_.sumBy(this.model.items,(t=>parseFloat(t.qty)))},toolbarButtons(){const t=[];return t.push({label:"Download",id:"export",type:"button",color:"teal",icon:"get_app",flat:!0}),"Draft"!==this.model.status&&"Partially Converted"!==this.model.status||(t.push({label:"Convert to Sale Order",id:"createso",type:"button",color:"blue-10",icon:"forward"}),t.push({label:"Edit",id:"edit",type:"a",link:"/quotations/edit/"+this.$route.params.id,color:"teal",icon:"edit"})),t},columns(){const t=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"product_name",field:"product_name",label:"Product",sortable:!0,align:"left"},{name:"weight",field:"weight",label:"Weight",sortable:!0,align:"right"},{name:"gst",field:"gst",label:"GST",sortable:!0},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"rate",field:"rate",label:"Rate",sortable:!0}];return this.model.rate_includes_gst||t.push({name:"taxable",field:"taxable",label:"Taxable",sortable:!0},{name:"tax_amount",field:"tax_amount",label:"Tax Amount",sortable:!0}),t.push({name:"total",field:"total",label:"Total",sortable:!0}),t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Quotations",link:"/quotations"},{label:this.model.serial_no}];return t},getCustomerLink(){return this.model.customer.is_lead?"/leads/view/"+this.model.customer.id:"/customers/view/"+this.model.customer.id}}},A=N,E=a("9989"),O=a("429b"),M=a("7460"),R=a("eb85"),B=a("adad"),U=a("823b"),V=a("2bb1"),j=a("66e5"),W=a("4074"),Y=a("0170"),z=a("357e"),H=a("0016"),G=a("0378"),J=a("7d53"),Z=a("24e8"),K=a("3786"),X=a("4b7e"),tt=a("ddd8"),et=Object(p["a"])(A,s,o,!1,null,null,null);e["default"]=et.exports;P()(et,"components",{QPage:E["a"],QCard:_["a"],QTabs:O["a"],QTab:M["a"],QSeparator:R["a"],QTabPanels:B["a"],QTabPanel:U["a"],QMarkupTable:V["a"],QItem:j["a"],QItemSection:W["a"],QItemLabel:Y["a"],QBadge:k["a"],QTable:f["a"],QTr:S["a"],QTh:z["a"],QTd:w["a"],QBtn:g["a"],QInput:Q["a"],QIcon:H["a"],QCardSection:y["a"],QForm:G["a"],QFile:J["a"],QDialog:Z["a"],QRadio:K["a"],QCheckbox:C["a"],QCardActions:X["a"],QSelect:tt["a"]})}}]);