<div class="user-avatar">
    @if ( isset( $image ) )
        <img src="{{ asset($image) }}" alt="Imagen de Usuario" class="icon">
    @else
        <span>{{ \App\Tools\Tools::getInitials($name) }}</span>
    @endif
</div>