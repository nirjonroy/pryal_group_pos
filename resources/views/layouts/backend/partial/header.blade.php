<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-dark container-p-x" id="layout-navbar">

    <!-- maybe its not working-->
    <!---top left side bar logo--->
    <a href="index.html" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
        <span class="app-brand-logo demo">
        <img src="{{asset('backend/links/assets')}}/img/MS_Priyal_Trades.jpeg" alt="Brand Logo" class="img-fluid">
        </span>
        <span class="app-brand-text demo font-weight-normal ml-2">
M/S Priyal Trades</span>
    </a>
    <!---top left side bar logo--->
    <!-- maybe its not working-->



    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
        <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:">
        <i class="ion ion-md-menu text-large align-middle"></i>
        </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="layout-navbar-collapse">

        <!---Search--->
        <hr class="d-lg-none w-100 my-2">
        <div class="navbar-nav align-items-lg-center">
            <label class="nav-item navbar-text navbar-search-box p-0 active">
                <i class="feather icon-search navbar-icon align-middle"></i>
                <span class="navbar-search-input pl-2">
                    <input type="text" class="form-control navbar-text mx-2" placeholder="Search...">
                </span>
            </label>
        </div>
        <!---Search--->

        <div class="navbar-nav align-items-lg-center ml-auto">

            <!---Header Notification--->
            @include('layouts.backend.partial.header_notification')
            <!---Header Notification--->

            <!---Header Message--->
            @include('layouts.backend.partial.header_message')
            <!---Header Message--->


        <!---Top Profile and logout--->
        <div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>
            
            

        
            <div class="demo-navbar-user nav-item dropdown">
                <div class="dropdown">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    {{auth()->user()->name}}
                  </button>
                  <div class="dropdown-menu">
                     <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        <i class="feather icon-power text-danger"></i>
                        &nbsp; Log Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  </div>
                </div>
                
            </div>
            
            
        </div>
    </div>
</nav>
<!-->
<style type="text/css">
        @media print {
      body * {
        visibility: hidden;
      }
      #print, #print * {
        visibility: visible;
      }
      #print {
        position: absolute;
        left: 0;
        top: 0;
      }
    }
</style>