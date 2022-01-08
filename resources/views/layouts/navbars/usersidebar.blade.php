<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="{{route('home')}}" class="simple-text logo-normal">
      {{ __('CA1 Management') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('My Activity') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('profile.edit')  }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('User profile') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'my-status' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('mystatus.index')  }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('My Status') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'my-logs' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('mylogs.index')  }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('My Logs') }}</p>
        </a>
      </li>
    </ul>
  </div>
</div>
