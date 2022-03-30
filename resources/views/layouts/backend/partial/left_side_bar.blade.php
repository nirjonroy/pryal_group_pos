<div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
    <div class="app-brand demo">
        <span class="app-brand-logo demo">
        <img src="{{asset('backend/links/assets')}}/img/MS_Priyal_Trades.jpeg" alt="Brand Logo" class="img-fluid" width="50" style="border-radius:100px;">
        </span>
        <a href="{{ route('home') }}" class="app-brand-text demo sidenav-text font-weight-normal ml-2">M/S Priyal Trades
</a>
        <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
        </a>
    </div>
    <div class="sidenav-divider mt-0"></div>


    <ul class="sidenav-inner py-1">

        <li class="sidenav-item {{ request()->is('home') ? 'open active' : '' }}">
            <a href="{{ action('HomeController@index')}}" class="sidenav-link">
                <div>Dashboard</div>
            </a>
        </li>

        @if(auth()->user()->can('supplier.index') || auth()->user()->can('supplier.create')) 
        <li class="sidenav-item {{ request()->is('admin/supplier') || request()->is('admin/supplier/create') ||request()->is('admin/supplier-type') || Request::routeIs('admin.supplier.show') || Request::routeIs('admin.supplier.edit')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-users"></i>
                <div>Supplier</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/supplier-type') || Request::routeIs('supplier-type.create') || Request::routeIs('supplier-type.edit')? 'active' : ''  }}">
                    <a href="{{ route('supplier-type.index') }}" class="sidenav-link">
                        <div>Supplier Type</div>
                    </a>
                </li>

                <li class="sidenav-item {{ request()->is('admin/supplier')  || Request::routeIs('admin.supplier.show')  || Request::routeIs('admin.supplier.edit')? 'active' : ''}}">
                    <a href="{{ route('admin.supplier.index') }}" class="sidenav-link">
                        <div>Supplier List</div>
                    </a>
                </li>
                @if(auth()->user()->can('supplier.create'))
                <li class="sidenav-item {{ request()->is('admin/supplier/create') ? 'active' : '' }}">
                    <a href="{{ route('admin.supplier.create') }} " class="sidenav-link">
                    <div>Add Supplier</div>
                </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

         
         
         @if(auth()->user()->can('customer.index') || auth()->user()->can('customer.create')) 
         
        <li class="sidenav-item {{ request()->is('customer') || request()->is('create-customer') || request()->is('customer-type') ? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Customer</div>
            </a>
            <ul class="sidenav-menu">
                @if(auth()->user()->can('supplier.index'))
                <li  class="sidenav-item {{ request()->is('customer')  ? 'active' : '' }}">
                    <a href="{{ route('admin.customer') }}" class="sidenav-link">
                        <div>Customer List</div>
                    </a>
                </li>
                @endif
                
                
                <li class="sidenav-item {{ request()->is('customer-type')  ? 'active' : '' }}">
                    <a href="{{ route('customer-type.index') }}" class="sidenav-link">
                        <div>Customer Type</div>
                    </a>
                </li>
                @if(auth()->user()->can('supplier.create'))
                <li class="sidenav-item {{ request()->is('create-customer') ? 'active' : ''  }}">
                    <a href="{{route('admin.customer.create')}}" class="sidenav-link">
                    <div>Add Customer</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
       @endif

        @if(auth()->user()->can('product.index'))
        <li class="sidenav-item {{ request()->is('admin/product') || request()->is('admin/product/*')
        || request()->is('admin/unit') || request()->is('admin/unit/*')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-list"></i>
                <div>Product</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/unit')  || request()->is('admin/unit/create')  || request()->is('admin/unit/*/edit') ? 'active' : '' }}">
                    <a href="{{ route('unit.index') }}" class="sidenav-link">
                        <div>Unit List</div>
                    </a>
                </li>
                
                @if(auth()->user()->can('product.index'))
                <li class="sidenav-item {{ request()->is('admin/product')  || Request::routeIs('admin.product.show')|| Request::routeIs('admin.product.edit')? 'active' : '' }}">
                    <a href="{{ route('admin.product.index') }}" class="sidenav-link">
                        <div>Product List</div>
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->can('product.create'))
                <li class="sidenav-item {{ request()->is('admin/product/create') ? 'active' : '' }}">
                    <a href="{{ route('admin.product.create') }}" class="sidenav-link">
                    <div>Add Product</div>
                    </a>
                </li>
                @endif
                
            </ul>
        </li>
        @endif

        @if(auth()->user()->can('purchase.index') || auth()->user()->can('purchase.create')) 
         <li class="sidenav-item {{ request()->is('admin/stocks') || request()->is('admin/stocks/*') ? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Purchase</div>
            </a>
            <ul class="sidenav-menu">
                @if(auth()->user()->can('purchase.index'))
                <li  class="sidenav-item {{ request()->is('admin/stocks')  || Request::routeIs('admin.stocks.edit')  || Request::routeIs('admin.stocks.show') ? 'active' : '' }}">
                    <a href="{{ route('admin.stocks.index') }}" class="sidenav-link">
                        <div>Purchase List</div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->can('purchase.create'))

                <li class="sidenav-item {{ request()->is('admin/stocks/create') ? 'active' : ''  }}">
                    <a href="{{ route('admin.stocks.create') }}" class="sidenav-link">
                    <div>Add Purchase</div>
                    </a>
                </li>

                @endif
            </ul>
        </li>

        @endif


        @if(auth()->user()->can('sell.index') || auth()->user()->can('sell.create')) 
        <li class="sidenav-item {{ request()->is('sell') || request()->is('create-sell') ? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Sell</div>
            </a>
            <ul class="sidenav-menu">
                
                <li  class="sidenav-item {{ request()->is('sell')  || Request::routeIs('admin.sell.edit')  || Request::routeIs('admin.sell.show') ? 'active' : '' }}">
                    <a href="{{ route('admin.sell') }}" class="sidenav-link">
                        <div>Sell List</div>
                    </a>
                </li>
                @if(auth()->user()->can('sell.create'))
                <li class="sidenav-item {{ request()->is('create-sell') ? 'active' : ''  }}">
                    <a href="{{route('admin.sell.create')}}" class="sidenav-link">
                    <div>Add Sell</div>
                    </a>
                </li>
                @endif
                
            </ul>
        </li>
       @endif

       @if(auth()->user()->can('stock.index') || auth()->user()->can('stock.create')) 
        <li class="sidenav-item {{ request()->is('admin/stock_returns') || request()->is('admin/stock_returns/*') ? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Stock Return</div>
            </a>
            <ul class="sidenav-menu">
                @if(auth()->user()->can('stock.index'))
                <li  class="sidenav-item {{ request()->is('admin/stock_returns')  || Request::routeIs('admin/stock_returns')  || Request::routeIs('admin/stock_returns') ? 'active' : '' }}">
                    <a href="{{ route('stock_returns.index') }}" class="sidenav-link">
                        <div>Stock Return List</div>
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->can('stock.create'))
                <li class="sidenav-item {{ request()->is('admin/stock_returns/create')  || Request::routeIs('admin/stock_returns/create')  || Request::routeIs('admin/stock_returns/create') ? 'active' : '' }}">
                    <a href="{{route('stock_returns.create')}}" class="sidenav-link">
                    <div>Add Stock Return</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if(auth()->user()->can('bank_statement.index') || auth()->user()->can('bank_statement.create')) 
        <li class="sidenav-item {{ request()->is('admin/bank-in*')|| request()->is('admin/bank-out*') || request()->is('admin/bank-statement*') ? 'open active' : ''}}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-users"></i>
                <div>Bank History</div>
            </a>
            <ul class="sidenav-menu">
                @if(auth()->user()->can('bank_statement.index'))
                <li class="sidenav-item {{ request()->is('admin/bank-in') || request()->is('admin/bank-in/*/edit') ? 'active' : '' }} ">
                    <a href="{{ route('bank-in.index') }}" class="sidenav-link">
                        <div>In</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('bank_statement.create'))
                <li class="sidenav-item {{ request()->is('admin/bank-in/create') ? 'active' : '' }}">
                    <a href="{{ route('bank-in.create') }}" class="sidenav-link">
                        <div>In Create</div>
                    </a>
                </li>
                @endif


                @if(auth()->user()->can('bank_statement.index'))
                <li class="sidenav-item {{ request()->is('admin/bank-out') ? 'active' : '' }}">
                    <a href="{{ route('bank-out.index') }} " class="sidenav-link">
                        <div>Out</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('bank_statement.create'))
                <li class="sidenav-item {{ request()->is('admin/bank-out/create') ? 'active' : '' }}">
                    <a href="{{ route('bank-out.create') }} " class="sidenav-link">
                        <div>Out Create</div>
                    </a>
                </li>
                @endif


                @if(auth()->user()->can('bank_statement.index'))

                 <li class="sidenav-item {{ request()->is('admin/bank-statement') ? 'active' : '' }}">
                    <a href="{{ route('statement') }} " class="sidenav-link">
                        <div>Bank Statement</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('bank_statement.index'))

                 <li class="sidenav-item {{ request()->is('admin/bank-statement-by-day') ? 'active' : '' }}">
                    <a href="{{ route('statement-by-day') }} " class="sidenav-link">
                        <div>Last 5 Day's Statement</div>
                    </a>
                </li>
                @endif

            </ul>
        </li>

        @endif
        <!-- @if(auth()->user()->can('purchase.index') || auth()->user()->can('purchase.create')) 
         <li class="sidenav-item {{ request()->is('admin/purchase') || request()->is('admin/purchase/*') ? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-package"></i>
                <div>Purchase</div>
            </a>
            <ul class="sidenav-menu">
                @if(auth()->user()->can('purchase.index'))
                <li  class="sidenav-item {{ request()->is('admin/purchase')  || Request::routeIs('admin.purchase.edit')  || Request::routeIs('admin.purchase.show') ? 'active' : '' }}">
                    <a href="{{ route('admin.purchase.index') }}" class="sidenav-link">
                        <div>Purchase List</div>
                    </a>
                </li>
                @endif
                @if(auth()->user()->can('purchase.create'))
                <li class="sidenav-item {{ request()->is('admin/purchase/create') ? 'active' : ''  }}">
                    <a href="{{ route('admin.purchase.create') }}" class="sidenav-link">
                    <div>Add Purchase</div>
                    </a>
                </li>

                

                @endif
            </ul>
        </li>

        @endif -->


        
        <!--@if(auth()->user()->can('payment.index'))-->

        <!--<li class="sidenav-item {{ request()->is('admin/project/received-payment') || request()->is('admin/received-payment-list') || request()->is('admin/supplier/supplier-payment') || request()->is('admin/supplier-payment-edit*') || request()->is('admin/received-payment-edit*') || request()->is('admin/received-payment-details*') || request()->is('admin/supplier-payment-details*') || request()->is('admin/supplier-payment-list') || request()->is('admin/customer-payment-list') || request()->is('customer/customer-payment') ? 'open active' : '' }}">-->
        <!--    <a href="javascript:" class="sidenav-link sidenav-toggle">-->
        <!--        <i class="sidenav-icon feather icon-layers"></i>-->
        <!--        <div>Payments</div>-->
        <!--    </a>-->
        <!--    <ul class="sidenav-menu">-->
        <!--        @if(auth()->user()->can('supplier_payment.index'))-->
        <!--        <li class=" {{ request()->is('admin/supplier-payment-list')  || request()->is('admin/supplier-payment-details/*')  || request()->is('admin/supplier-payment-edit/*') ? 'active' : '' }} sidenav-item">-->
        <!--            <a href="{{ action('PaymentController@supplierPaymentList') }}" class="sidenav-link">-->
        <!--                <div>Supplier Payment List</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        @endif-->

        <!--        @if(auth()->user()->can('supplier_payment.create'))-->
        <!--         <li class=" {{ request()->is('admin/supplier/supplier-payment') ? 'active' : '' }} sidenav-item">-->
        <!--                <a href="{{ action('Backend\Admin\Supplier\SupplierController@supplierPayment') }}" class="sidenav-link">-->
        <!--                <div>Supplier Payment</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        @endif-->

        <!--        @if(auth()->user()->can('supplier_payment.index'))-->
        <!--        <li class=" {{ request()->is('admin/customer-payment-list') ? 'active' : '' }} sidenav-item">-->
        <!--            <a href="{{ action('PaymentController@customerPaymentList') }}" class="sidenav-link">-->
        <!--                <div>Customer Payment List</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        @endif-->

        <!--        @if(auth()->user()->can('supplier_payment.create'))-->
        <!--         <li class="{{ request()->is('customer/customer-payment') ? 'active' : '' }}  sidenav-item">-->
        <!--                <a href="{{ action('CustomerController@customerPayment') }}" class="sidenav-link">-->
        <!--                <div>Customer Payment</div>-->
        <!--            </a>-->
        <!--        </li>-->
        <!--        @endif-->

        <!--    </ul>-->
        <!--</li>-->

        <!--@endif-->
        


        

        
        @if(auth()->user()->can('expense.index') || auth()->user()->can('expense.create')) 

        <li class="sidenav-item {{ request()->is('admin/expense') || request()->is('admin/expense/*') ||request()->is('admin/expense-type') || request()->is('admin/expense-type/*') || request()->is('admin/expense-category')  || request()->is('admin/expense-category/*')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-sliders"></i>
                <div>Expense</div>
            </a>
            <ul class="sidenav-menu">
                @if(auth()->user()->can('expense.index'))
                <li  class="sidenav-item {{ request()->is('admin/expense-category')  || request()->is('admin/expense-category/*') ? 'active' : '' }}">
                    <a href="{{ route('expense-category.index') }}" class="sidenav-link">
                        <div>Expense Category</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('expense.index'))
                <li  class="sidenav-item {{ request()->is('admin/expense-type') ? 'active' : '' }} || {{ request()->is('admin/expense-type/*') ? 'active' : '' }}">
                    <a href="{{ route('expense-type.index') }}" class="sidenav-link">
                        <div>Expense Type</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('expense.index'))
                <li  class="sidenav-item {{ request()->is('admin/expense')  || Request::routeIs('admin.expense.show')  || Request::routeIs('admin.expense.edit') ? 'active' : '' }} ">
                    <a href="{{ route('admin.expense.index') }}" class="sidenav-link">
                        <div>Expense List</div>
                    </a>
                </li>
                @endif
                 @if(auth()->user()->can('expense.create'))
                <li class="sidenav-item {{ request()->is('admin/expense/create') ? 'active' : '' }}">
                    <a href="{{ route('admin.expense.create') }}" class="sidenav-link">
                    <div>Add Expense</div>
                </a>
                </li>
                @endif
            </ul>
        </li>

        @endif
        
      <!--   @if(auth()->user()->can('company.index') || auth()->user()->can('company.create')) 
        <li class="sidenav-item {{ request()->is('admin/company') || request()->is('admin/company/*') ||request()->is('admin/company-type') ||request()->is('admin/company-type/*')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-align-justify"></i>
                <div>Company</div>
            </a>
            <ul class="sidenav-menu">
                <li class="{{ request()->is('admin/company-type')  || Request::routeIs('company-type.edit')  || Request::routeIs('company-type.create') ? 'active' : '' }} sidenav-item">
                    <a href="{{ route('company-type.index') }}" class="sidenav-link">
                        <div>Company Type</div>
                    </a>
                </li>

                <li class="{{ Request::routeIs('admin.company.index')  || Request::routeIs('admin.company.show')  || Request::routeIs('admin.company.edit') ? 'active' : '' }} sidenav-item">
                    <a href="{{ route('admin.company.index') }}" class="sidenav-link">
                        <div>Company List</div>
                    </a>
                </li>
                <li class="{{ request()->is('admin/compay/create') || request()->is('admin/company/create') ? 'active' : '' }} sidenav-item">
                    <a href="{{ route('admin.company.create') }}" class="sidenav-link">
                    <div>Add Company</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif -->

      <!--   @if(auth()->user()->can('project.index') || auth()->user()->can('project.create')) 
        <li class="sidenav-item {{ request()->is('admin/project') || request()->is('admin/project-type') || request()->is('admin/project/create') || request()->is('admin/project-type/*') || request()->is('admin/project/*/edit')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-layers"></i>
                <div>Project</div>
            </a>
            <ul class="sidenav-menu">
                <li class="{{ request()->is('admin/project-type')  || Request::routeIs('project-type.create')  || Request::routeIs('project-type.edit') ? 'active' : '' }} sidenav-item">
                    <a href="{{ route('project-type.index') }}" class="sidenav-link">
                        <div>Project Type</div>
                    </a>
                </li>

                <li class="{{ request()->is('admin/project') ||  Request::routeIs('admin.project.edit') ? 'active' : '' }} sidenav-item">
                    <a href="{{ route('admin.project.index') }}" class="sidenav-link">
                        <div>Project List</div>
                    </a>
                </li>
                <li class=" {{ request()->is('admin/project/create') ? 'active' : '' }} sidenav-item">
                    <a href="{{ route('admin.project.create') }}" class="sidenav-link">
                    <div>Add Project</div>
                </a>
                </li>

            </ul>
        </li>


        @endif -->

        

        


        


        @if(auth()->user()->can('dashboard.view'))
        <li class="sidenav-item {{ request()->is('admin/info')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-users"></i>
                <div>Company Info</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/info') ? 'active' : '' }}">
                    <a href="{{ route('info.index') }}" class="sidenav-link">
                        <div>Company Info</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif


        @if(auth()->user()->can('store.index'))
        <li class="sidenav-item {{ request()->is('admin/stores')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-users"></i>
                <div>Store</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/stores') ? 'active' : '' }}">
                    <a href="{{ route('stores.index') }}" class="sidenav-link">
                        <div>Store Manage</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif


        <!--@if(auth()->user()->can('productStock.index'))-->
        <!--<li class="sidenav-item {{ request()->is('product-stock') ? 'active' : '' }}">-->
        <!--    <a href="{{route('product-stock')}}" class="sidenav-link">-->

        <!--    <i class="sidenav-icon feather icon-list"></i>-->
        <!--    <div>Product Stock List</div>-->
        <!--    </a>-->
        <!--</li>-->
        <!--@endif-->

        @if(auth()->user()->can('report.index'))

        <li class="sidenav-item {{ request()->is('reports/*') ||  request()->is('product-stock') ? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-list"></i>
                <div>Report</div>
            </a>
            <ul class="sidenav-menu">
                <!-- @if(auth()->user()->can('project_wise_report.index'))
                <li  class="sidenav-item {{ request()->is('reports/project-wise') ? 'active' : '' }} || {{ request()->is('reports/project-details*') ? 'active' : '' }}">
                    <a href="{{ action('Backend\ReportController@ProjectWise') }}" class="sidenav-link">
                        <div>Project Wise Report</div>
                    </a>
                </li>
                @endif -->
                
                @if(auth()->user()->can('supplier_wise_report.index'))
                <li  class="sidenav-item {{ request()->is('reports/supplier-wise') ? 'active' : '' }} || {{ request()->is('reports/supplier-wise-report-view*') ? 'active' : ''  }} || {{ request()->is('reports/supplier-wise-report*') ? 'active' : ''  }}">
                    <a href="{{ action('Backend\ReportController@supplierWise') }}" class="sidenav-link">
                        <div>Supplier Wise Report</div>
                    </a>
                </li>
                @endif

                <li  class="sidenav-item ">
                    <a href="{{ action('Backend\ReportController@customerWise') }}" class="sidenav-link">
                        <div>Customer Wise Report</div>
                    </a>
                </li>
                
                @if(auth()->user()->can('productStock.index'))
        <li class="sidenav-item {{ request()->is('product-stock') ? 'active' : '' }}">
            <a href="{{route('product-stock')}}" class="sidenav-link">

            
            <div>Product Stock List</div>
            </a>
        </li>
        @endif
                
                
                <!--  @if(auth()->user()->can('purchase_wise_report.index'))
                <li  class="sidenav-item {{ ( ( \Request::is('reports/purchase-wise') || Request::is('reports/purchase-wise-report-view/*') ) ? ' active' : '' ) }}">

                    <a href="{{ action('Backend\ReportController@purchaseWise') }}" class="sidenav-link">
                        <div>Purchase Wise Report</div>
                    </a>
                </li>
                @endif -->

                 <!-- @if(auth()->user()->can('company_running_project_report.index'))

                <li  class="sidenav-item {{ request()->is('reports/company-wise')  ||  request()->is('reports/company-running-details*') ? 'active' : ''}}">
                    <a href="{{ action('Backend\ReportController@companyWise') }}" class="sidenav-link">
                        <div>Company Running Project Report</div>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('company_complete_project_report.index'))

                <li  class="sidenav-item {{ request()->is('reports/company-wise-complete')  || request()->is('reports/company-complete-details*') ? 'active' : '' }}">
                    <a href="{{ action('Backend\ReportController@companyWiseComplete') }}" class="sidenav-link">
                        <div>Company Complete Project Report</div>
                    </a>
                </li>

                @endif

                @if(auth()->user()->can('company_work_done_report.index'))
                <li  class="sidenav-item {{ request()->is('reports/company-wise-work') ? 'active' : '' }} || {{ request()->is('reports/company-work-done-details*') ? 'active' : '' }}">
                    <a href="{{ action('Backend\ReportController@companyWiseWork') }}" class="sidenav-link">
                        <div>Company Wrok Done Report</div>
                    </a>
                </li>
                @endif

                 @if(auth()->user()->can('company_partner_investment_report.index'))
                <li  class="sidenav-item {{ request()->is('reports/company-wise-partner') ? 'active' : '' }} || {{ request()->is('reports/company-details*') ? 'active' : '' }}">
                    <a href="{{ action('Backend\ReportController@companyWisePartner') }}" class="sidenav-link">
                        <div>Company Partner Investment Report</div>
                    </a>
                </li>
                @endif -->
                
                @if(auth()->user()->can('product_wise_report.index'))
                <li  class="sidenav-item {{ request()->is('reports/product-wise') ? 'active' : '' }}">

                    <a href="{{ action('Backend\ReportController@productWise') }}" class="sidenav-link">
                        <div>Product Wise Report</div>
                    </a>
                </li>
                @endif

                 @if(auth()->user()->can('daily_statement_report.index'))

                <li  class="sidenav-item {{ request()->is('reports/daily-statement') ? 'active' : '' }}">

                    <a href="{{ action('Backend\ReportController@dailyStatement') }}" class="sidenav-link">
                        <div>Daily Statement</div>
                    </a>
                </li>
                @endif
                
               <!--  @if(auth()->user()->can('yearly_complete_project_report.index'))
                <li  class="sidenav-item {{ request()->is('reports/yearly-project') ? 'active' : '' }} || {{ request()->is('reports/yearly-project-details*') ? 'active' : '' }} || {{ request()->is('reports/project-details*') ? 'active' : '' }} ">

                    <a href="{{ action('Backend\ReportController@yearlyProject') }}" class="sidenav-link">
                        <div>Yearly Complete Project Report</div>
                    </a>
                </li>

                @endif -->




            </ul>
        </li>

        @endif


        @if(auth()->user()->can('user.view'))

        <li class="sidenav-item {{ request()->is('admin/user') || request()->is('admin/user/create')  || request()->is('admin/user/*/edit')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-users"></i>
                <div>User</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/user') || request()->is('admin/user/create')  || request()->is('admin/user/*/edit')? 'open active' : '' }}">
                    <a href="{{ route('user.index') }}" class="sidenav-link">
                        <div>User Manage</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->user()->can('role.view'))
        <li class="sidenav-item {{ request()->is('admin/role') || request()->is('admin/role/create')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-chevrons-right"></i>
                <div>Role</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/role') || request()->is('admin/role/create')? 'active' : '' }}">
                    <a href="{{ route('role.index') }}" class="sidenav-link">
                        <div>Role Manage</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        @if(auth()->user()->can('permission.view'))
        <li class="sidenav-item {{ request()->is('admin/permission')? 'open active' : '' }}">
            <a href="javascript:" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-chevrons-right"></i>
                <div>Permission</div>
            </a>
            <ul class="sidenav-menu">
                <li class="sidenav-item {{ request()->is('admin/permission') ? 'active' : '' }}">
                    <a href="{{ route('permission.index') }}" class="sidenav-link">
                        <div>Permission Manage</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>



    </ul>
</div>
