
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue'), name: 'Dashboard' },
      {
        path: 'products',
        component: () => import('pages/Products.vue'),
        children: [
          { path: '', component: () => import('pages/Products/Index.vue'), name: 'ProductsIndex' },
          { path: 'create', component: () => import('pages/Products/Create.vue'), name: 'ProductsCreate' },
          { path: 'view/:id', component: () => import('pages/Products/View.vue'), name: 'ProductsView' },
          { path: 'edit/:id', component: () => import('pages/Products/Create.vue'), name: 'ProductsEdit' },
          { path: 'import', component: () => import('pages/Products/Import.vue'), name: 'ProductsImport' },
          { path: 'cost/:id', component: () => import('pages/Products/Cost.vue'), name: 'ProductsCost' },
          { path: 'stock/:id', component: () => import('pages/Products/Stock.vue'), name: 'ProductsStock' },
          { path: 'sales/:id', component: () => import('pages/Products/Sales.vue'), name: 'ProductsSales' },
          { path: 'purchases/:id', component: () => import('pages/Products/Purchases.vue'), name: 'ProductsPurchases' }
        ]
      },
      {
        path: 'product_bundles',
        component: () => import('pages/ProductBundles.vue'),
        children: [
          { path: '', component: () => import('pages/ProductBundles/Index.vue') },
          { path: 'create', component: () => import('pages/ProductBundles/Create.vue') },
          { path: 'view/:id', component: () => import('pages/ProductBundles/View.vue') },
          { path: 'edit/:id', component: () => import('pages/ProductBundles/Create.vue') }
        ]
      },
      {
        path: 'product_types',
        component: () => import('pages/ProductTypes.vue'),
        children: [
          { path: '', component: () => import('pages/ProductTypes/Index.vue'), name: 'ProductTypesIndex' }
        ]
      },
      {
        path: 'product_departments',
        component: () => import('pages/ProductDepartments.vue'),
        children: [
          { path: '', component: () => import('pages/ProductDepartments/Index.vue'), name: 'ProductDepartmentsIndex' }
        ]
      },
      {
        path: 'product_categories',
        component: () => import('pages/ProductCategories.vue'),
        children: [
          { path: '', component: () => import('pages/ProductCategories/Index.vue'), name: 'ProductCategoriesIndex' }
        ]
      },
      {
        path: 'product_sub_categories',
        component: () => import('pages/ProductSubCategories.vue'),
        children: [
          { path: '', component: () => import('pages/ProductSubCategories/Index.vue'), name: 'ProductSubCategoriesIndex' }
        ]
      },
      {
        path: 'product_brands',
        component: () => import('pages/ProductBrands.vue'),
        children: [
          { path: '', component: () => import('pages/ProductBrands/Index.vue'), name: 'ProductBrandsIndex' }
        ]
      },
      {
        path: 'users',
        component: () => import('pages/Users.vue'),
        children: [
          { path: '', component: () => import('pages/Users/Index.vue'), name: 'UsersIndex' },
          { path: 'create', component: () => import('pages/Users/Create.vue'), name: 'UsersCreate' },
          { path: 'edit/:id', component: () => import('pages/Users/Create.vue'), name: 'UsersEdit' }
        ]
      },
      {
        path: 'user_roles',
        component: () => import('pages/Users/Roles.vue'),
        children: [
          { path: '', component: () => import('pages/Users/Roles/Index.vue'), meta: { permission: 'viewAny_user_role' }, name: 'UserRolesIndex' },
          { path: 'create', component: () => import('pages/Users/Roles/Create.vue'), name: 'UserRolesCreate' },
          { path: 'edit/:id', component: () => import('pages/Users/Roles/Create.vue'), name: 'UserRolesEdit' }
        ]
      },
      {
        path: 'customers',
        component: () => import('pages/Customers.vue'),
        children: [
          { path: '', component: () => import('pages/Customers/Index.vue'), name: 'CustomersIndex' },
          { path: 'create', component: () => import('pages/Customers/Create.vue'), name: 'CustomersCreate' },
          { path: 'view/:id/:name?', component: () => import('pages/Customers/View.vue'), name: 'CustomersView' },
          { path: 'edit/:id', component: () => import('pages/Customers/Create.vue'), name: 'CustomersEdit' },
          { path: 'sales/:id', component: () => import('pages/Customers/Sales.vue'), name: 'CustomersSales' },
          { path: 'ledger/:id', component: () => import('pages/Customers/Ledger.vue'), name: 'CustomersLedger' }
        ]
      },
      {
        path: 'leads',
        component: () => import('pages/Leads.vue'),
        children: [
          { path: '', component: () => import('pages/Leads/Index.vue'), name: 'LeadsIndex' },
          { path: 'create', component: () => import('pages/Leads/Create.vue'), name: 'LeadsCreate' },
          { path: 'view/:id/:name?', component: () => import('pages/Leads/View.vue'), name: 'LeadsView' },
          { path: 'edit/:id', component: () => import('pages/Leads/Create.vue'), name: 'LeadsEdit' }
        ]
      },
      {
        path: 'sale_orders',
        component: () => import('pages/SaleOrders.vue'),
        children: [
          { path: '', component: () => import('pages/SaleOrders/Index.vue'), name: 'SaleOrdersIndex' },
          { path: 'create/:customer_id?', component: () => import('pages/SaleOrders/Create.vue'), name: 'SaleOrdersCreate' },
          { path: 'view/:id', component: () => import('pages/SaleOrders/View.vue'), name: 'SaleOrdersView' },
          { path: 'edit/:id', component: () => import('pages/SaleOrders/Create.vue'), name: 'SaleOrdersEdit' },
          { path: 'returns/:id', component: () => import('pages/SaleOrders/ReturnList.vue'), name: 'SaleOrdersReturns' }
        ]
      },
      {
        path: 'packaging/:so_id',
        component: () => import('pages/PackagingList.vue'),
        children: [
          { path: '', component: () => import('pages/PackagingList/BoxIndex.vue') },
          { path: 'items', component: () => import('pages/PackagingList/ItemIndex.vue') },
          { path: 'create', component: () => import('pages/PackagingList/Create.vue') },
          { path: 'edit/:id', component: () => import('pages/PackagingList/Create.vue') }
        ]
      },
      {
        path: 'sale_order_revisits',
        component: () => import('pages/SaleOrders.vue'),
        children: [
          { path: 'view/:id', component: () => import('pages/SaleOrders/RevisitView.vue'), name: 'SaleOrdersRevisitsView' },
          { path: '', component: () => import('pages/SaleOrders/RevisitIndex.vue'), name: 'SaleOrdersRevisitsIndex' }
        ]
      },

      {
        path: 'sale_returns',
        component: () => import('pages/SaleReturns.vue'),
        children: [
          { path: '', component: () => import('pages/SaleReturns/Index.vue'), name: 'SaleReturnsIndex' },
          { path: 'view/:id', component: () => import('pages/SaleReturns/View.vue'), name: 'SaleReturnsView' },
          { path: 'edit/:id', component: () => import('pages/SaleReturns/Edit.vue'), name: 'SaleReturnsEdit' }
        ]
      },

      {
        path: 'shipments',
        component: () => import('pages/Suppliers.vue'),
        children: [
          { path: '', component: () => import('pages/Shipments/Index.vue'), name: 'ShipmentsIndex' },
          { path: 'view/:id', component: () => import('pages/Shipments/View.vue'), name: 'ShipmentsView' },
          { path: 'edit/:id', component: () => import('pages/Shipments/Edit.vue'), name: 'ShipmentsEdit' }
        ]
      },

      {
        path: 'logistic_partners',
        component: () => import('pages/SaleOrders.vue'),
        children: [
          { path: '', component: () => import('pages/LogisticPartners/Index.vue'), name: 'LogisticPartnersIndex' },
          { path: 'create', component: () => import('pages/LogisticPartners/Create.vue'), name: 'LogisticPartnersCreate' },
          { path: 'view/:id', component: () => import('pages/LogisticPartners/View.vue'), name: 'LogisticPartnersView' },
          { path: 'edit/:id', component: () => import('pages/LogisticPartners/Create.vue'), name: 'LogisticPartnersEdit' }
        ]
      },

      {
        path: 'quotations',
        component: () => import('pages/Quotations.vue'),
        children: [
          { path: '', component: () => import('pages/Quotations/Index.vue'), name: 'QuotationsIndex' },
          { path: 'create/:customer_id?', component: () => import('pages/Quotations/Create.vue'), name: 'QuotationsCreate' },
          { path: 'view/:id', component: () => import('pages/Quotations/View.vue'), name: 'QuotationsView' },
          { path: 'edit/:id', component: () => import('pages/Quotations/Create.vue'), name: 'QuotationsEdit' }
        ]
      },
      {
        path: 'quotation_templates',
        component: () => import('pages/QuotationTemplates.vue'),
        children: [
          { path: '', component: () => import('pages/QuotationTemplates/Index.vue'), name: 'QuotationTemplatesIndex' },
          { path: 'create', component: () => import('pages/QuotationTemplates/Create.vue'), name: 'QuotationTemplatesCreate' },
          { path: 'view/:id', component: () => import('pages/QuotationTemplates/View.vue'), name: 'QuotationTemplatesView' },
          { path: 'edit/:id', component: () => import('pages/QuotationTemplates/Create.vue'), name: 'QuotationTemplatesEdit' }
        ]
      },
      {
        path: 'purchases',
        component: () => import('pages/Purchases.vue'),
        children: [
          { path: '', component: () => import('pages/Purchases/Index.vue'), name: 'PurchasesIndex' },
          { path: 'create', component: () => import('pages/Purchases/Create.vue'), name: 'PurchasesCreate' },
          { path: 'view/:id', component: () => import('pages/Purchases/View.vue'), name: 'PurchasesView' },
          { path: 'edit/:id', component: () => import('pages/Purchases/Create.vue'), name: 'PurchasesEdit' },
          { path: 'returns/:id', component: () => import('pages/Purchases/ReturnList.vue') }
        ]
      },
      {
        path: 'goods_receive_notes',
        component: () => import('pages/GoodsReceiveNotes.vue'),
        children: [
          { path: '', component: () => import('pages/GoodsReceiveNotes/Index.vue'), name: 'GoodsReceiveNotesIndex' },
          { path: 'create', component: () => import('pages/GoodsReceiveNotes/Create.vue'), name: 'GoodsReceiveNotesCreate' },
          { path: 'view/:id', component: () => import('pages/GoodsReceiveNotes/View.vue'), name: 'GoodsReceiveNotesView' },
          { path: 'edit/:id', component: () => import('pages/GoodsReceiveNotes/Create.vue'), name: 'GoodsReceiveNotesEdit' }
        ]
      },
      {
        path: 'suppliers',
        component: () => import('pages/Suppliers.vue'),
        children: [
          { path: '', component: () => import('pages/Suppliers/Index.vue'), name: 'SuppliersIndex' },
          { path: 'create', component: () => import('pages/Suppliers/Create.vue'), name: 'SuppliersCreate' },
          { path: 'view/:id', component: () => import('pages/Suppliers/View.vue'), name: 'SuppliersView' },
          { path: 'edit/:id', component: () => import('pages/Suppliers/Create.vue'), name: 'SuppliersEdit' }
        ]
      },
      {
        path: 'warehouses',
        component: () => import('pages/Warehouses.vue'),
        children: [
          { path: '', component: () => import('pages/Warehouses/Index.vue'), name: 'WarehousesIndex' }
        ]
      },
      {
        path: 'stock_entries',
        component: () => import('pages/StockEntries.vue'),
        children: [
          { path: '', component: () => import('pages/StockEntries/Index.vue'), name: 'StockEntriesIndex' },
          { path: 'create', component: () => import('pages/StockEntries/Create.vue'), name: 'StockEntriesCreate' },
          { path: 'edit/:id', component: () => import('pages/StockEntries/Create.vue'), name: 'StockEntriesEdit' },
          { path: 'view/:id', component: () => import('pages/StockEntries/View.vue'), name: 'StockEntriesView' }
        ]
      },
      {
        path: 'pricelists',
        component: () => import('pages/Pricelists.vue'),
        children: [
          { path: '', component: () => import('pages/Pricelists/Index.vue'), name: 'PricelistsIndex' }
        ]
      },
      {
        path: 'phones',
        component: () => import('pages/Phones.vue'),
        children: [
          { path: '', component: () => import('pages/Phones/Index.vue'), name: 'PhonesIndex' },
          { path: 'view/:id', component: () => import('pages/Phones/View.vue'), name: 'PhonesView' }
        ]
      },
      {
        path: 'exports',
        component: () => import('pages/Exports.vue'),
        name: 'Exports'
      },
      {
        path: 'permission-denied',
        component: () => import('pages/Error403.vue'),
        name: 'Error403'
      },

      {
        path: 'purchase_returns',
        component: () => import('pages/PurchaseReturns.vue'),
        children: [
          { path: '', component: () => import('pages/PurchaseReturns/Index.vue'), name: 'PurchaseReturnsIndex' },
          { path: 'view/:id', component: () => import('pages/PurchaseReturns/View.vue'), name: 'PurchaseReturnsView' }
        ]
      }
    ]

  },
  { path: '/login', component: () => import('pages/Auth/Login.vue'), name: 'LoginPage' },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue'),
    name: 'Error404'
  }
]

export default routes
