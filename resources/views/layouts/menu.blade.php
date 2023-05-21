<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link active">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('tasks.index') }}"
       class="nav-link {{ Request::is('tasks*') ? 'active' : '' }}">
        <p>Tasks</p>
    </a>
</li>


