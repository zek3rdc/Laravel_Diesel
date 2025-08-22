<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <div class="navbar-brand m-0">
        <img src="{{ asset('assets/img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Pacheco Diesel</span>
      </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        @foreach(config('adminlte.menu') as $item)
            @if(isset($item['header']))
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">{{ $item['header'] }}</h6>
                </li>
            @elseif(isset($item['text']) && isset($item['url']))
                @if(!isset($item['can']) || (auth()->check() && auth()->user()->can($item['can'])))
                    <li class="nav-item">
                        <a class="nav-link text-white {{ Request::is(ltrim($item['url'], '/').'*') ? 'active' : '' }}" href="{{ url($item['url']) }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">{{ $item['icon'] ?? '' }}</i>
                            </div>
                            <span class="nav-link-text ms-1">{{ $item['text'] }}</span>
                        </a>
                    </li>
                @endif
            @endif
        @endforeach
      </ul>
    </div>
  </aside>
