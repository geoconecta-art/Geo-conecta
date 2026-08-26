<style>
    #weather_down_bar{
        display: none;
        background: #D1D1D1;
    }

    #city_manager_logo_img, #atizapan_logo_img, #innovacion_logo_img{
        object-fit: cover;
        height: 62px;
    }

    #weather-container-li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #D1D1D1;
        color: black;
    }

    #weather-info-container {
        font-size: 1.5rem;
        line-height: 2rem;
    }

    #weather-container-li #val-time {
        font-weight: 500;
    }

    @media screen and (width < 925px){
        #weather_down_bar{
            display: flex;
            align-items: center;
            justify-content: space-around;
            background: #D1D1D1;
        }

        #city_manager_logo_img, #atizapan_logo_img, #innovacion_logo_img{
            object-fit: cover;
            height: 40px;
        }

        #weather-info-container-header-tools{
            display: none;
        }
    }

    @media screen and (width >= 1200px) and (width <= 1250px){
        #city_manager_logo_img, #atizapan_logo_img, #innovacion_logo_img{
            object-fit: cover;
            height: 48px;
        }
    }
</style>

<div class="nk-header nk-header-fixed is-light p-0" style="z-index: 100;">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>

            <div class="d-flex col-12 col-sm-9 col-md-8">
                {{-- <img src="{{ asset('assets/images/CITY-MAN-LOGO.png') }}" alt="City Manager Logo" class="mr-4" id="city_manager_logo_img"> --}}
                
                <div class="flex items-center">
                    <img src="{{ asset('assets/images/Logo Atizapan.png') }}" alt="Atizapán Logo" id="atizapan_logo_img" >
    
                    <img src="{{ asset('assets/images/Innovación_logo.png') }}" alt="Desarrollo Urbano Logo" id="innovacion_logo_img">
                </div>
            </div>

            <div class="nk-header-tools p-0" style="height: 65px;" id="weather-info-container-header-tools">
                <ul class="nk-quick-nav h-100">
                    <li class="h-100 p-2" id="weather-container-li">
                        
                        <div class="d-flex align-items-center me-2" id="weather-info-container">
                            <img src="{{ $weather['weatherImg'] }}" alt="{{ $weather['weatherDes'] }}" style="width: 80px">
                            <div>
                                {{ $weather['weather'] }}
                            </div>
                        </div>

                        <div class="date-time">
                            <div id="time">
                                <span class="val-time">
                                    
                                </span>
                            </div>
    
                            <div id="date">
                                <span id="val-date">
                                    {{ $date }}
                                </span>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->

    <div class="w-full" style="height: 1rem; background: linear-gradient(90deg, #B64F80 0%, #644080 35%, #79B0CC 100%);"></div>

    <div class="w-full" id="weather_down_bar">
        <div class="d-flex align-items-center">
            <img src="{{ $weather['weatherImg'] }}" alt="{{ $weather['weatherDes'] }}" style="width: 40px;">
            <div>
                {{ $weather['weather'] }}
            </div>
        </div>
        

        <div id="date">
            <span id="val-date">
                {{ $date }}
            </span>
        </div>

        <div id="time">
            <span class="val-time">
                
            </span>
        </div>

    </div>

</div>