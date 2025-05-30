<li class="breadcrumb-menu">
    <div class="btn-group" role="group" aria-label="Grupo de botões">
        <div class="dropdown">
            <a class="btn dropdown-toggle" href="#" role="button" id="breadcrumb-dropdown-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('menus.backend.access.users.main')</a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="breadcrumb-dropdown-1">
                <a class="dropdown-item" href="{{ route('admin.auth.user.index') }}">@lang('menus.backend.access.users.all')</a>
                @can('create', App\Models\Auth\User::class)
                <a class="dropdown-item" href="{{ route('admin.auth.user.create') }}">@lang('menus.backend.access.users.create')</a>
                @endcan
                <a class="dropdown-item" href="{{ route('admin.auth.user.deactivated') }}">@lang('menus.backend.access.users.deactivated')</a>
                <a class="dropdown-item" href="{{ route('admin.auth.user.deleted') }}">@lang('menus.backend.access.users.deleted')</a>
            </div>
        </div><!--dropdown-->

        <!--<a class="btn" href="#">Static Link</a>-->
    </div><!--btn-group-->
</li>
